<?php
require "../config/db_connect.php";
include "./auto_login.php";

$user = autoLogin($conn);
if ($user['role'] != 'Admin') {
    exit("Không có quyền truy cập");
}

$posts_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Filter and search parameters
$where_conditions = "1=1"; // Base condition
$params = array();

// Chỉ thêm điều kiện status nếu có giá trị
$status = isset($_GET['status']) ? $_GET['status'] : 'Pending';
if (!empty($status)) {
    $where_conditions .= " AND p.status = ?";
    $params[] = $status;
}

if (!empty($_GET['author_id'])) {
    $where_conditions .= " AND p.author_id = ?";
    $params[] = $_GET['author_id'];
}

if (!empty($_GET['title'])) {
    $where_conditions .= " AND p.title LIKE ?";
    $params[] = "%" . $_GET['title'] . "%";
}

if (!empty($_GET['type'])) {
    $where_conditions .= " AND p.type = ?";
    $params[] = $_GET['type'];
}

// Sort parameter
$sort_order = isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC']) ? $_GET['sort'] : 'DESC';
$order_by = "ORDER BY p.create_at $sort_order";

// Count total posts
$total_query = "SELECT COUNT(*) as total FROM posts p WHERE $where_conditions";
$stmt = $conn->prepare($total_query);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Fetch posts for current page
$query = "SELECT p.*, u.fullname AS author_name 
          FROM posts p 
          LEFT JOIN user_account u ON p.author_id = u.user_id 
          WHERE $where_conditions 
          $order_by 
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

$param_types = str_repeat('s', count($params)) . 'ii';
$bind_params = $params;
$bind_params[] = $posts_per_page;
$bind_params[] = $offset;

$stmt->bind_param($param_types, ...$bind_params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý bài viết</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        .pagination { margin: 20px 0; }
        .filter-form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Quản lý bài viết</h1>

    <!-- Filter Form -->
    <div class="filter-form">
        <form method="GET">
            <select name="status">
                <option value="">Tất cả trạng thái</option>
                <option value="Pending" <?php if ($status === 'Pending') echo 'selected'; ?>>Chờ xử lí</option>
                <option value="Writing" <?php if ($status === 'Writing') echo 'selected'; ?>>Bản nháp</option>
                <option value="Posted" <?php if ($status === 'Posted') echo 'selected'; ?>>Đã đăng</option>
                <option value="Deny" <?php if ($status === 'Deny') echo 'selected'; ?>>Từ chối</option>
            </select>

            <input type="text" name="title" placeholder="Tìm theo tiêu đề" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
            <input type="number" name="author_id" placeholder="ID tác giả" value="<?php echo htmlspecialchars($_GET['author_id'] ?? ''); ?>">

            <select name="type">
                <option value="">Tất cả thể loại</option>
                <option value="1" <?php if (($_GET['type'] ?? '') === '1') echo 'selected'; ?>>Tin tức chung</option>
                <option value="2" <?php if (($_GET['type'] ?? '') === '2') echo 'selected'; ?>>Thông báo</option>
                <option value="3" <?php if (($_GET['type'] ?? '') === '3') echo 'selected'; ?>>Sự kiện</option>
                <option value="4" <?php if (($_GET['type'] ?? '') === '4') echo 'selected'; ?>>Tin nổi bật</option>
                <option value="5" <?php if (($_GET['type'] ?? '') === '5') echo 'selected'; ?>>Lịch tuần</option>
                <option value="6" <?php if (($_GET['type'] ?? '') === '6') echo 'selected'; ?>>Thi đua</option>
                <option value="7" <?php if (($_GET['type'] ?? '') === '7') echo 'selected'; ?>>Đoàn viên</option>
            </select>

            <select name="sort">
                <option value="DESC" <?php if ($sort_order === 'DESC') echo 'selected'; ?>>Mới nhất trước</option>
                <option value="ASC" <?php if ($sort_order === 'ASC') echo 'selected'; ?>>Cũ nhất trước</option>
            </select>

            <button type="submit">Lọc</button>
        </form>
    </div>

    <!-- Posts Table -->
    <form method="POST" id="posts-form">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th style="display:none">ID</th>
                    <th>Tiêu đề</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Cập nhật cuối</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="post_ids[]" value="<?php echo $row['post_id']; ?>"></td>
                        <td style="display:none"><?php echo $row['post_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author_name'] ?? ''); ?></td>
                        <td><?php 
                            $types = [
                                1 => 'Tin tức chung',
                                2 => 'Thông báo',
                                3 => 'Sự kiện',
                                4 => 'Tin nổi bật',
                                5 => 'Lịch tuần',
                                6 => 'Thi đua',
                                7 => 'Đoàn viên'
                            ];
                            echo $types[$row['type']] ?? $row['type'];
                        ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['create_at']; ?></td>
                        <td><?php echo $row['last_update'] ?? 'Chưa cập nhật'; ?></td>
                        <td>
                            <a href="./server/edit_post.php?id=<?php echo $row['post_id']; ?>">Sửa</a>
                            <a href="./server/delete_post.php?id=<?php echo $row['post_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Bulk Actions -->
        <div style="margin: 10px 0;">
            <select name="bulk_action">
                <option value="">Chọn hành động</option>
                <option value="publish">Đăng bài</option>
                <option value="draft">Chuyển thành nháp</option>
                <option value="deny">Từ chối</option>
            </select>
            <button type="submit" name="apply_bulk">Áp dụng</button>
        </div>
    </form>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query($_GET); ?>">Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&<?php echo http_build_query($_GET); ?>" <?php if ($page == $i) echo 'style="font-weight: bold;"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query($_GET); ?>">Sau</a>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('post_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>
</body>
</html>

<?php
// Handle bulk actions
if (isset($_POST['apply_bulk']) && !empty($_POST['post_ids']) && !empty($_POST['bulk_action'])) {
    $post_ids = implode(',', array_map('intval', $_POST['post_ids']));
    switch ($_POST['bulk_action']) {
        case 'publish':
            $res = $conn->query("UPDATE posts SET status = 'Posted', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
        case 'draft':
            $res = $conn->query("UPDATE posts SET status = 'Writing', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
        case 'deny':
            $res = $conn->query("UPDATE posts SET status = 'Deny', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
    }
    if ($result) {
        echo "Có: " . $conn->affected_rows . " bài viết đã được cập nhật!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    exit;
}

$conn->close();
?>