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
    <title>Đoàn Thanh Niên Trường Đại Học Tây Nguyên</title>
    <link href="./logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="./css/index.css" type="text/css">
    <link rel="stylesheet" href="./css/responsive.css" type="text/css">
    <link rel="stylesheet" href="./css/news.css" type="text/css">
    <link rel="stylesheet" href="./css/render.css" type="text/css">
    <link rel="stylesheet" href="./css/general.css" type="text/css">
    <link href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" rel="stylesheet">

    <script src="./js/render.js"></script>
</head>

<body>
    <section id="wrapper">
        <header>
            <div class="main">
                <div class="container">
                    <div class="taskbar">
                        <div class="left">
                            <div class="logo">
                                <img src="https://placehold.co/50x50" alt="Logo">
                                Đoàn thanh niên ĐH Tây Nguyên
                            </div>
                        </div>
                        <div class="right">
                            <nav>
                                <a href="https://www.ttn.edu.vn/">ĐH Tây Nguyên</a>
                                <?php
                                if ($user == null) {
                                    echo '<a href="./auth.php">Đăng nhập</a>';
                                } else {
                                    echo '<a href="./logout.php">Đăng xuất</a>';
                                }
                                ?>
                                <?php
                                if ($user != null && $user['role'] == 'Admin') {
                                    echo '<a href="./edit.php">Quản lí</a>';
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
                    <div class="banner-text">Đoàn Thanh Niên ĐHTN</div>
                </section>
            </div>
        </div>
        <div class="main">
            <nav class="navbar">
                <div class="navbar-container">
                    <div class="navbar-content">
                        <div id="menu-toggle" class="menu-toggle" aria-label="Toggle Menu">☰</div>
                        <ul id="menu" class="menu">
                            <li><a href="./">Home</a></li>
                            <li class="dropdown">
                                <a>Giới thiệu</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Giới thiệu chung</a></li>
                                    <li><a href="./updating.php">Các thành tích đạt được</a></li>
                                    <li><a href="./updating.php">Cơ cấu tổ chức</a></li>
                                    <li><a href="./updating.php">Liên hệ</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a>Đơn vị trực thuộc</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Liên chi Đoàn, Chi đoàn trực thuộc</a></li>
                                    <li><a href="./updating.php">CLB, Tổ, Đội</a></li>
                                    <li><a href="./updating.php">Đăng ký lịch tuần</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a>Chương trình sự kiện</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Hoạt động đoàn trường</a></li>
                                    <li><a href="./updating.php">Hoạt động LCĐ, Chi đoàn trực thuộc</a></li>
                                    <li><a href="./updating.php">Hoạt động CLB, Tổ, Đội trực thuộc</a></li>
                                    <li><a href="./updating.php">Hoạt động tình nguyện</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a>Tổ chức kiểm tra</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Thông báo</a></li>
                                    <li><a href="./updating.php">Khen thưởng-Kỷ luật</a></li>
                                    <li><a href="./updating.php">Kiện toàn nhân sự</a></li>
                                    <li><a href="./updating.php">Quy chế đánh giá đoàn viên, chi đoàn, liên chi đoàn</a>
                                    </li>
                                </ul>
                            <li class="dropdown">
                                <a>Công tác Đảng</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Thông báo</a></li>
                                    <li><a href="./updating.php">Hoạt động chi bộ Sinh viên</a></li>
                                    <li><a href="./updating.php">Văn bản hướng dẫn</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>NCKH Sinh viên</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Thông báo</a></li>
                                    <li><a href="./updating.php">Hoạt động NCKH</a></li>
                                    <li><a href="./updating.php">Công trình tiêu biểu</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>Hoạt động Tình nguyện</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Thông báo</a></li>
                                    <li><a href="./updating.php">Chương trình tình nguyện</a></li>
                                </ul>
                            <li class="dropdown">
                                <a>Văn bản</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Văn bản Đoàn cấp trên</a></li>
                                    <li><a href="./updating.php">Văn bản Đoàn trường</a></li>
                                    <li><a href="./updating.php">Biểu mẫu văn bản</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>TTN Book</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Danh mục sách</a></li>
                                    <li><a href="./updating.php">Giới thiệu sách</a></li>
                                    <li><a href="./updating.php">Đặt mua sách</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>Đại hội các cấp</a>
                                <ul class="dropdown-menu">
                                    <li><a href="./updating.php">Kế hoạch - Hướng dẫn</a></li>
                                    <li><a href="./updating.php">Thông tin Đại hội Đoàn các cấp</a></li>

                                </ul>
                            <li> <a>About TTN</a> </li>
                            </li>
                            <li><a href="./updating.php">Liên hệ</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container">
                <div class="events">
                    <h2 class="event-title">
                        <a href="./general.php?type=3">
                            Sự Kiện
                        </a>
                    </h2>
                    <ul>
                        <?php
                        foreach ($data['su_kien'] as $post) {
                            $slug = $post['slug'];
                            echo "<li><a href='./news.php?slug=$slug'>";
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
                            <a href="./general.php?type=2">
                                <h2>Thông báo</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['thong_bao'] as $post) {
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='./news.php?slug=$slug'>";
                                    echo htmlspecialchars($post['title']);
                                    echo "</a>";
                                    echo "<p>" . date('d/m/Y', strtotime($post['last_update']));
                                    echo "</p></div></li>";
                                }
                                ?>

                            </ul>
                        </div>
                        <div class="news">
                            <a href="./general.php?type=1">
                                <h2>Tin tức chung</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['tin_tuc_chung'] as $post) {
                                    $img = $post['image'];
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='./news.php?slug=$slug'>";
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
                <div class="footer-content">
                    <div class="left-content">
                        <p>Đoàn thanh niên trường ĐH Tây Nguyên</p>
                        <div>
                            <p>&#128204; Địa chỉ: 567 Lê Duẩn TP. Buôn Ma Thuột - Đăk Lăk</p>
                            <p>&#128383; SĐT: (0262)3825185</p>
                            <p>&#128386; Email: dhtn@ttn.edu.vn</p>
                        </div>
                    </div>
                    <div class="right-content">
                        <p>Mạng Xã Hội</p>
                        <div class="social">
                            <a href="./updating.php" class="social-icon">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/yT/r/aGT3gskzWBf.ico?_nc_eui2=AeEYIIQ-Kmjep1NfAQPVImFKrSiY817De8atKJjzXsN7xos_Ow8tqDmAAVBL2IrprG6cBF5DK_r47yJqGTexFO3v"
                                    alt="Facebook">
                            </a>
                            <a href="./updating.php" class="social-icon">
                                <img src="https://www.youtube.com/s/desktop/e208051c/img/logos/favicon.ico"
                                    alt="Facebook">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bottom-content">
                    <div class="left-content">
                        <div>
                            Copyright &reg; 2025
                        </div>
                    </div>
                    <div class="right-content">
                        <div>
                            Designed and Developed by <a id="DevName">Quang Lâm</a>
                        </div>
                    </div>
                </div>
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