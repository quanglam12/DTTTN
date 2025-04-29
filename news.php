<?php
require "../config/db_connect.php";
include "auto_login.php";
include "settings.php";
$user = autoLogin($conn);

$slug = $_GET['slug'] ?? "";
$sql = "SELECT posts.content, posts.title, posts.type, posts.create_at ,fullname FROM posts INNER JOIN user_account ON posts.author_id = user_account.user_id
        WHERE posts.slug = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$contentArray = json_decode($row['content'], true);

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
    <title><?php
    if (isset($row['title'])) {
        echo $row['title'];
    } else {
        echo "Xảy ra lỗi";
    }
    ?></title>
    <link href="/logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="/css/index.css" type="text/css">
    <link rel="stylesheet" href="/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/css/news.css" type="text/css">
    <link rel="stylesheet" href="/css/render.css" type="text/css">
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
                                <li><span>/</span> <?php
                                switch ($row['type']) {
                                    case '1':
                                        echo "<a href='/tintucchung'>Tin tức chung</a>";
                                        break;
                                    case '2':
                                        echo "<a href='/thongbao'>Thông báo</a>";
                                        break;
                                    case '3':
                                        echo "<a href='/sukien'>Sự kiện</a>";
                                        break;
                                    case '4':
                                        echo "<a href='/tinnoibat'>Tin nổi bật</a>";
                                        break;
                                    case '5':
                                        echo "<a href='/lichtuan'>Lịch tuần</a>";
                                        break;
                                    case '6':
                                        echo "<a href='/thidua'>Thi đua</a>";
                                        break;
                                    case '7':
                                        echo "<a href='/doanvien'>Đoàn viên</a>";
                                        break;
                                    default:
                                        echo "<a href='/tintuchung'>Tin tức chung</a>";
                                        break;
                                }
                                ?></li>
                                <li class="active"><span>/</span>
                                    <a>
                                        <?php
                                        if (isset($row['title'])) {
                                            echo $row['title'];
                                        } else {
                                            echo "Xảy ra lỗi";
                                        }
                                        ?>
                                    </a>
                                </li>
                            </ul>

                        </div>
                        <div class="new-title">
                            <p><?php
                            if (isset($row['title'])) {
                                echo $row['title'];
                            } else {
                                echo "Xảy ra lỗi";
                            }
                            ?></p>
                        </div>
                        <div class="new-content">
                            <p class="date"><?php
                            if (isset($row['create_at'])) {
                                echo date("d/m/Y", strtotime($row['create_at']));
                            } else {
                                echo "Xảy ra lỗi";
                            }
                            ?></p>
                            <p class="author"><?php
                            if (isset($row['fullname'])) {
                                echo $row['fullname'];
                            } else {
                                echo "Xảy ra lỗi";
                            }
                            ?></p>
                            <div class="detail-content">
                                <div class="chitietbaiviet" id="chitietbaiviet">

                                </div>
                            </div>

                        </div>

                    </div>
                    <script>
                        const savedData = <?php echo $row['content'] ?>;
                        renderContent(savedData);
                    </script>
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
    <script src="/js/slider.js"></script>
    <script src="/js/topictab.js"></script>
</body>

</html>