<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require "../config/db_connect.php";
include "./auto_login.php";

$user = autoLogin($conn);
if ($user['role'] != 'Admin') {
    exit("Không có quyền truy cập");
}

$posts_per_page = 10;
$page_raw = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = isset($page_raw) ? (int) $page_raw : 1;
$offset = ($page - 1) * $posts_per_page;

// Filter and search parameters
$where_conditions = "1=1";
$params = array();

$status_raw = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
$status = isset($status_raw) ? $status_raw : 'Pending';

if (!empty($status)) {
    $where_conditions .= " AND p.status = ?";
    $params[] = $status;
}

if (!empty(filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_NUMBER_INT))) {
    $where_conditions .= " AND p.author_id = ?";
    $params[] = filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_NUMBER_INT);
}

if (!empty(filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING))) {
    $where_conditions .= " AND p.title LIKE ?";
    $params[] = "%" . filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING) . "%";
}

if (!empty(filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT))) {
    $where_conditions .= " AND p.type = ?";
    $params[] = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);
}

if (!empty(filter_input(INPUT_GET, 'unit_id', FILTER_SANITIZE_NUMBER_INT))) {
    $where_conditions .= " AND p.unit_id = ?";
    $params[] = filter_input(INPUT_GET, 'unit_id', FILTER_SANITIZE_NUMBER_INT);
}

$sort_raw = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
$sort_order = (isset($sort_raw) && in_array($sort_raw, ['ASC', 'DESC'])) ? $sort_raw : 'DESC';

$order_by = "ORDER BY p.create_at $sort_order";

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

// Fetch units for dropdown
$unitOptions = [];
$unitResult = $conn->query("SELECT unit_id, unit_name FROM unit");
while ($row = $unitResult->fetch_assoc()) {
    $unitOptions[] = $row;
}
$selected_unit_id = filter_input(INPUT_GET, 'unit_id', FILTER_SANITIZE_NUMBER_INT) ?? '';

// Fetch posts
$query = "SELECT p.*, u.fullname AS author_name, d.unit_name
          FROM posts p
          LEFT JOIN user_account u ON p.author_id = u.user_id
          LEFT JOIN unit d ON p.unit_id = d.unit_id
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

<div class="filter-form">
    <form method="GET">
        <select name="status">
            <option value="">Tất cả trạng thái</option>
            <option value="Pending" <?php if ($status === 'Pending') echo 'selected'; ?>>Chờ xử lí</option>
            <option value="Writing" <?php if ($status === 'Writing') echo 'selected'; ?>>Bản nháp</option>
            <option value="Posted" <?php if ($status === 'Posted') echo 'selected'; ?>>Đã đăng</option>
            <option value="Deny" <?php if ($status === 'Deny') echo 'selected'; ?>>Từ chối</option>
        </select>

        <input type="text" name="title" placeholder="Tìm theo tiêu đề"
            value="<?php echo htmlspecialchars(filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING) ?? ''); ?>">
        <input type="number" name="author_id" placeholder="ID tác giả"
            value="<?php echo htmlspecialchars(filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_NUMBER_INT) ?? ''); ?>">

        <select name="type">
            <option value="">Tất cả thể loại</option>
            <?php
            $typeList = [1=>'Tin tức chung', 2=>'Thông báo', 3=>'Sự kiện', 4=>'Tin nổi bật', 5=>'Lịch tuần', 6=>'Thi đua', 7=>'Đoàn viên'];
            foreach ($typeList as $id => $name) {
                $selected = ((filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) ?? '') == $id) ? 'selected' : '';
                echo "<option value='$id' $selected>$name</option>";
            }
            ?>
        </select>

        <select name="unit_id">
            <option value="">Tất cả đơn vị</option>
            <?php foreach ($unitOptions as $unit): ?>
                <option value="<?php echo $unit['unit_id']; ?>" <?php if ($selected_unit_id == $unit['unit_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($unit['unit_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="sort">
            <option value="DESC" <?php if ($sort_order === 'DESC') echo 'selected'; ?>>Mới nhất trước</option>
            <option value="ASC" <?php if ($sort_order === 'ASC') echo 'selected'; ?>>Cũ nhất trước</option>
        </select>

        <button type="submit">Lọc</button>
    </form>
</div>

<form method="POST" id="posts-form">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <table>
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Đơn vị</th>
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
                <td><?php echo $row['post_id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author_name'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['unit_name'] ?? ''); ?></td>
                <td><?php echo $typeList[$row['type']] ?? $row['type']; ?></td>
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

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query($_GET); ?>">Trước</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&<?php echo http_build_query($_GET); ?>" <?php if ($page == $i) echo 'style="font-weight:bold;"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query($_GET); ?>">Sau</a>
    <?php endif; ?>
</div>

<script>
    document.getElementById('select-all').onclick = function () {
        var checkboxes = document.getElementsByName('post_ids[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
</body>
</html>

<?php
// Bulk Actions
$apply_bulk = filter_input(INPUT_POST, 'apply_bulk', FILTER_SANITIZE_STRING);
$bulk_action = filter_input(INPUT_POST, 'bulk_action', FILTER_SANITIZE_STRING);
if (isset($apply_bulk) && !empty($_POST['post_ids']) && !empty($bulk_action)) {
    $post_ids = implode(',', array_map('intval', $_POST['post_ids']));
    switch ($bulk_action) {
        case 'publish':
            $conn->query("UPDATE posts SET status = 'Posted', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
        case 'draft':
            $conn->query("UPDATE posts SET status = 'Writing', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
        case 'deny':
            $conn->query("UPDATE posts SET status = 'Deny', last_update = NOW() WHERE post_id IN ($post_ids)");
            break;
    }
}
$conn->close();
?>