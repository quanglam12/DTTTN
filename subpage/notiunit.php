<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";
$user = autoLogin($conn);

// Lấy unit_id từ GET
$unit_id = filter_var($_GET['unit'] ?? 1, FILTER_VALIDATE_INT) ?: 1;
$posts_per_page = 12;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

$offset = ($page - 1) * $posts_per_page;

// Lấy tên đơn vị
$unit_stmt = $conn->prepare("SELECT unit_name FROM unit WHERE unit_id = ?");
$unit_stmt->bind_param("i", $unit_id);
$unit_stmt->execute();
$unit_result = $unit_stmt->get_result();
$unit = $unit_result->fetch_assoc();
$unit_name = $unit['unit_name'] ?? 'Không xác định';

// Đếm bài viết
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM posts WHERE type = 1 AND status = 'Posted' AND unit_id = ?");
$count_stmt->bind_param("i", $unit_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_posts = $count_result->fetch_assoc()['total'] ?? 0;

$total_pages = ceil($total_posts / $posts_per_page);

// Lấy danh sách bài viết
$sql = "SELECT post_id, title, slug, image, last_update 
        FROM posts 
        WHERE type = 1 AND status = 'Posted' AND unit_id = ?
        ORDER BY last_update DESC 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $unit_id, $offset, $posts_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Lấy dữ liệu thông báo và tin tức chung
$config = [
    'tin_tuc_chung' => [
        'limit' => 4,
        'condition' => "type = 1 AND status = 'Posted'",
    ],
    'thong_bao' => [
        'limit' => 4,
        'condition' => "type = 2 AND status = 'Posted'",
    ],
];
function fetchNews($conn, $config)
{
    $results = [];
    foreach ($config as $type => $settings) {
        $limit = $settings['limit'];
        $condition = $settings['condition'];
        $query = "SELECT title, slug, image, last_update FROM posts WHERE $condition ORDER BY last_update DESC LIMIT ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $results[$type] = [];
        while ($row = $result->fetch_assoc()) {
            $results[$type][] = $row;
        }
        $stmt->close();
    }
    return $results;
}
$data = fetchNews($conn, $config);
?>
<!DOCTYPE html>
<html lang="vi-vn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài viết - <?php echo htmlspecialchars($unit_name); ?></title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <link rel="stylesheet" href="/css/news.css">
    <link rel="stylesheet" href="/css/render.css">
    <link rel="stylesheet" href="/css/general.css">
    <link href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" rel="stylesheet">
    <script src="/js/render.js"></script>
</head>

<body>
    <section id="wrapper">
        <?php include('../src/header.php'); ?>
        <?php include('../src/banner.html'); ?>
        <div class="main">
            <?php include('../src/navbar.html'); ?>
            <div class="container">
                <div class="main-container">
                    <div class="left-column">
                        <div class="list_arrow_breakumb">
                            <ul>
                                <li><a href="/" class="home">Trang Chủ</a></li>
                                <li><span>/</span><a href="/tintucchung">Tin tức chung</a></li>
                                <li class="active"><span>/</span><a
                                        href="#"><?php echo htmlspecialchars($unit_name); ?></a></li>
                            </ul>
                        </div>
                        <div class="new-title">
                            <h2>Bài viết <?php echo htmlspecialchars($unit_name); ?></h2>
                        </div>
                        <div class="new-content">
                            <div class="detail-content">
                                <div class="listnews">
                                    <?php if ($result->num_rows > 0): ?>
                                        <ul>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <li>
                                                    <a href="/baiviet/<?php echo htmlspecialchars($row['slug']); ?>">
                                                        <img src="<?php echo htmlspecialchars($row['image'] ?? '/img/default.png'); ?>"
                                                            alt="<?php echo htmlspecialchars($row['title']); ?>">
                                                        <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                                                        <small><?php echo date("d/m/Y", strtotime($row['last_update'])); ?></small>
                                                    </a>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Chưa có bài viết nào cho đơn vị này.</p>
                                    <?php endif; ?>

                                    <?php if ($total_pages > 1): ?>
                                        <div class="pagination">
                                            <?php if ($page > 1): ?>
                                                <a href="?unit=<?php echo $unit_id; ?>&page=<?php echo $page - 1; ?>">«
                                                    Trước</a>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <a href="?unit=<?php echo $unit_id; ?>&page=<?php echo $i; ?>" <?php echo $i == $page ? 'style="font-weight:bold;"' : ''; ?>>
                                                    <?php echo $i; ?>
                                                </a>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <a href="?unit=<?php echo $unit_id; ?>&page=<?php echo $page + 1; ?>">Sau »</a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right-column">
                        <div class="notifications">
                            <?php
                            if ($user != null && ($user['role'] == 'Admin' || $user['role'] == 'Manager')) {
                                echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="/vietbai/thongbao">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
                            <a href="/thongbao">
                                <h2>Thông báo</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['thong_bao'] as $post) {
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='/baiviet/$slug'>";
                                    echo htmlspecialchars($post['title']);
                                    echo "</a>";
                                    echo "<p>" . date('d/m/Y', strtotime($post['last_update']));
                                    echo "</p></div></li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="news">
                            <?php
                            if ($user != null && ($user['role'] == 'Admin' || $user['role'] == 'Manager' || $user['role'] == 'Author')) {
                                echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="/vietbai/tintucchung">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
                            <a href="/tintucchung">
                                <h2>Tin tức chung</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['tin_tuc_chung'] as $post) {
                                    $img = $post['image'];
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='/baiviet/$slug'>";
                                    echo "<img src='" . htmlspecialchars($img ?? '/img/default.png') . "'>";
                                    echo htmlspecialchars($post['title']);
                                    echo "</a></div></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <?php include('../src/footer.html'); ?>
            </footer>
        </div>
    </section>
    <butto