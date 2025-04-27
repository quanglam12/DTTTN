<?php
require "../config/db_connect.php";
include "auto_login.php";
include "settings.php";
$user = autoLogin($conn);

$type_id = filter_var($_GET['type'] ?? 1, FILTER_VALIDATE_INT) ?: 1;
$posts_per_page = 12;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

$offset = ($page - 1) * $posts_per_page;

$total_query = "SELECT COUNT(*) as total FROM posts";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];

$total_pages = ceil($total_posts / $posts_per_page);

$sql = "SELECT type_name, post_id, title, slug, image, last_update 
        FROM type_of_post 
        INNER JOIN posts ON type_of_post.type_id = posts.type 
        WHERE type_id = ? 
        ORDER BY last_update DESC 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $type_id, $offset, $posts_per_page);
$stmt->execute();
$result = $stmt->get_result();
$config = [
    'tin_tuc_chung' => [
        'limit' => 4,
        'condition' => "type = 1 AND status = 'Posted'",
    ],
    'thong_bao' => [
        'limit' => 4,
        'condition' => "type = 2 AND status = 'Posted'",
    ],
    'su_kien' => [
        'limit' => 4,
        'condition' => "type = 3 AND status = 'Posted'",
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

// Lấy dữ liệu
$data = fetchNews($conn, $config);
?>

<!DOCTYPE html>
<html lang="vi-vn">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đoàn TNCS Hồ Chí Minh trường Đại học Tây Nguyên</title>
    <link href="/logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="/css/index.css" type="text/css">
    <link rel="stylesheet" href="/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/css/news.css" type="text/css">
    <link rel="stylesheet" href="/css/render.css" type="text/css">
    <link rel="stylesheet" href="/css/general.css" type="text/css">
    <link href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" rel="stylesheet">

    <script src="/js/render.js"></script>
</head>

<body>
    <section id="wrapper">
        <header>
            <div class="main">
                <div class="container">
                    <div class="taskbar">
                        <div class="left">
                            <div class="logo">
                                <img src="/logo.ico" alt="Logo">
                               <p>Cổng thông tin điện tử</p>
                            </div>
                        </div>
                        <div class="right">
                            <nav>
                                <a href="https://www.ttn.edu.vn/">Đại học Tây Nguyên</a>
                                <?php
                                if ($user == null) {
                                    echo '<a href="/auth.php">Đăng nhập</a>';
                                } else {
                                    echo '<a href="/logout.php">Đăng xuất</a>';
                                }
                                ?>
                                <?php
                                if ($user != null && $user['role'] == 'Admin') {
                                    echo '<a href="/edit.php">Quản lí</a>';
                                }
                                ?>
                                <div class="search-box">
                                    <input type="text" placeholder="Search...">
                                    <button>🔍</button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="main">
            <div class="container">
                <section class="banner">
                    <img src="/img/B12.jpg" alt="Banner">
                    <div class="banner-text">
                        <p>Đoàn TNCS Hồ Chí Minh</p>
                        <p>Trường Đại Học Tây Nguyên</p>
                    </div>
                </section>
            </div>
        </div>
        <div class="main">
            <?php include('/src/navbar.html'); ?>
            <div class="container">
                <div class="events">
                    <h2 class="event-title">
                        <a href="/sukien">
                            Sự Kiện
                        </a>
                    </h2>
                    <ul>
                        <?php
                        foreach ($data['su_kien'] as $post) {
                            $slug = $post['slug'];
                            echo "<li><a href='/baiviet/$slug'>";
                            echo htmlspecialchars($post['title']);
                            echo "</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="container">
                <div class="main-container">
                    <div class="left-column">
                        <div class="list_arrow_breakumb">
                            <ul>
                                <li><a href="/" id="homepage-url" class="home">Trang Chủ</a></li>
                                <li class="active"><span>/</span><a href=""><?php
                                $row = $result->fetch_assoc();

                                if ($row && isset($row['type_name'])) {
                                    echo htmlspecialchars($row['type_name']);
                                } else {
                                    echo "Xảy ra lỗi!";
                                }
                                ?></a></li>
                            </ul>

                        </div>
                        <div class="new-title">
                        </div>
                        <div class="new-content">
                            <p class="date"></p>
                            <div class="detail-content">
                                <div class="listnews">
                                    <?php if ($result->num_rows > 0): ?>
                                        <ul>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <li>
                                                    <a href="<?php echo htmlspecialchars($row['slug']); ?>">
                                                        <img src="<?php echo htmlspecialchars($row['image']); ?>"
                                                            alt="<?php echo htmlspecialchars($row['title']); ?>">
                                                        <p class="title"><?php echo htmlspecialchars($row['title']); ?></p>
                                                        <small>
                                                            <?php echo date("d/m/Y", strtotime($row['last_update'])); ?></small>
                                                    </a>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Chưa có bài viết nào.</p>
                                    <?php endif; ?>

                                    <?php if ($total_pages > 1): ?>
                                        <div class="pagination">
                                            <?php if ($page > 1): ?>
                                                <a href="?page=<?php echo $page - 1; ?>&type_id=<?php echo $type_id; ?>">«
                                                    Trước</a>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <a href="?page=<?php echo $i; ?>&type_id=<?php echo $type_id; ?>" <?php echo $i == $page ? 'style="font-weight:bold;"' : ''; ?>>
                                                    <?php echo $i; ?>
                                                </a>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <a href="?page=<?php echo $page + 1; ?>&type_id=<?php echo $type_id; ?>">Sau
                                                    »</a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="right-column">
                        <div class="notifications">
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
                            <a href="/tintucchung">
                                <h2>Tin tức chung</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['tin_tuc_chung'] as $post) {
                                    $img = $post['image'];
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='/baiviet/$slug'>";
                                    echo "<img src='$img'>";
                                    echo htmlspecialchars($post['title']);
                                    echo "</a>";
                                    echo "</div></li>";
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>



            </div>
            <footer>
                <?php include "/src/footer.html"; ?>
            </footer>
        </div>
        </div>
    </section>
    <button id="backToTop" class="back-to-top">
        <i class="fi fi-sr-arrow-to-top"></i>
    </button>
    <script src="/js/main.js"></script>
    <script src="/js/topictab.js"></script>
</body>

</html>