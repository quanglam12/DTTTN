<?php
require "../config/db_connect.php";
include "auto_login.php";
$user = autoLogin($conn);

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
    'tin_noi_bat' => [
        'limit' => 5,
        'condition' => "type = 4 AND status = 'Posted'",
    ],
    'doan_vien' => [
        'limit' => 8,
        'display' => [
            'main_card' => 3,
            'sub_card' => 5
        ],
        'condition' => "type = 7 AND status = 'Posted'",
    ],
    'lich_tuan' => [
        'limit' => 8,
        'display' => [
            'main_card' => 3,
            'sub_card' => 5
        ],
        'condition' => "type = 5 AND status = 'Posted'",
    ],
    'thi_dua' => [
        'limit' => 8,
        'display' => [
            'main_card' => 3,
            'sub_card' => 5
        ],
        'condition' => "type = 6 AND status = 'Posted'",
    ]
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
    <link href="./logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="./css/index.css" type="text/css">
    <link rel="stylesheet" href="./css/responsive.css" type="text/css">
    <link rel="stylesheet" href="./css/slider.css" type="text/css">
    <link rel="stylesheet" href="./css/topictab.css" type="text/css">
    <link href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body>
    <section id="wrapper">
        <header>
            <div class="main">
                <div class="container">
                    <div class="taskbar">
                        <div class="left">
                            <div class="logo">
                                <img src="./logo.ico" alt="Logo">
                               <p> Cổng thông tin điện tử</p>
                            </div>
                        </div>
                        <div class="right">
                            <nav>
                                <a href="https://www.ttn.edu.vn/">Đại học Tây Nguyên</a>
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
                    <img src="./img/B12.jpg" alt="Banner">
                    <div class="banner-text">
                        <p>Đoàn TNCS Hồ Chí Minh</p>
                        <p>Trường Đại Học Tây Nguyên</p>
                </div>
                </section>
            </div>
        </div>
        <div class="main">
            <?php include('./src/navbar.html');?>
            <div class="container">
                <div class="events">
                    <?php
                    if ($user != null && $user['role'] == 'Admin') {
                        echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./sukien">Viết bài</a>
                                        </div>
                                    </div>';
                    }
                    ?>
                    <h2 class="event-title">
                        <a href="./sukien">
                            Sự Kiện
                        </a>
                    </h2>
                    <ul>
                        <?php
                        foreach ($data['su_kien'] as $post) {
                            $slug = $post['slug'];
                            echo "<li><a href='./baiviet/$slug'>";
                            echo htmlspecialchars($post['title']);
                            echo "</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="footer" class="span1">
            <div class="btn-desktop">
                <a class="btn-dktuyensinh" href="https://tuyensinh.ttn.edu.vn/"> <span class="mb-text">TƯ VẤN TUYỂN SINH
                        TRỰC TUYẾN</span> </a>
            </div>
        </div>
        <div class="main">
            <div class="container">
                <div class="main-container">
                    <div class="left-column">

                        <div class="banner">
                            <div class="slider-wrapper">
                                <div class="slide-container">
                                    <div class="slides">
                                        <?php
                                        if ($user != null && $user['role'] == 'Admin') {
                                            echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/tinnoibat">Viết bài</a>
                                        </div>
                                    </div>';
                                        }
                                        ?>
                                        <?php
                                        $first = true;
                                        foreach ($data['tin_noi_bat'] as $post) {
                                            $slug = $post['slug'];
                                            $image = $post['image'];
                                            echo "<a href='./baiviet/$slug'>";
                                            if ($first) {
                                                echo "<img src='$image' class='imgslider active'>";
                                                $first = false;
                                            } else {
                                                echo "<img src='$image' class='imgslider'>";
                                            }
                                            echo "<div class='slide-title'>" . $post['title'] . "</div>";
                                            echo "</a>";
                                        }
                                        ?>
                                    </div>



                                    <div class="buttons">
                                        <span class="next">&#10095;</span>
                                        <span class="prev">&#10094;</span>
                                    </div>

                                    <div class="dotsContainer">
                                        <div class="dot active" attr='0' onclick="switchImage(this)"></div>
                                        <div class="dot" attr='1' onclick="switchImage(this)"></div>
                                        <div class="dot" attr='2' onclick="switchImage(this)"></div>
                                        <div class="dot" attr='3' onclick="switchImage(this)"></div>
                                        <div class="dot" attr='4' onclick="switchImage(this)"></div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="tab-container">
                            <div class="custom-tabs">
                                <button class="tab-link active" data-tab="tab1">Đoàn viên tiêu biểu</button>
                                <button class="tab-link" data-tab="tab2">Lịch tuần</button>
                                <button class="tab-link" data-tab="tab3">Thi đua LCĐ</button>
                            </div>

                            <div id="tab1" class="tab-content active">
                                <?php
                                $doanvien = $data['doan_vien'];
                                $doanvienleft = array_slice($doanvien, 0, 3);
                                $doanvienright = array_slice($doanvien, 3, 5);

                                ?>
                                <div class="tab-left">
                                    <?php
                                    foreach ($doanvienleft as $post) {
                                        $img = $post['image'];
                                        $slug = $post['slug'];
                                        echo "<div><a href='./baiviet/$slug'>";
                                        echo "<img src='$img'>";
                                        echo $post['title'];
                                        echo "</a></div>";
                                    }
                                    ?>
                                </div>
                                <div class="tab-right">
                                    <?php
                                    if ($user != null && $user['role'] == 'Admin') {
                                        echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/doanvien">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($doanvienright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./baiviet/$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>

                                    </ul>
                                    <a href="./doanvien" class="other-news">Xem các tin khác</a>
                                </div>
                            </div>

                            <div id="tab2" class="tab-content">
                                <?php
                                $lichtuan = $data['lich_tuan'];
                                $lichtuanleft = array_slice($lichtuan, 0, 3);
                                $lichtuanright = array_slice($lichtuan, 3, 5);

                                ?>
                                <div class="tab-left">
                                    <?php
                                    foreach ($lichtuanleft as $post) {
                                        $img = $post['image'];
                                        $slug = $post['slug'];
                                        echo "<div><a href='./baiviet/$slug'>";
                                        echo "<img src='$img'>";
                                        echo $post['title'];
                                        echo "</a></div>";
                                    }
                                    ?>
                                </div>
                                <div class="tab-right">
                                    <?php
                                    if ($user != null && $user['role'] == 'Admin') {
                                        echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/lichtuan">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($lichtuanright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./baiviet/$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <a href="./lichtuan" class="other-news">Xem các tin khác</a>
                                </div>
                            </div>

                            <div id="tab3" class="tab-content">
                                <?php
                                $thidua = $data['thi_dua'];
                                $thidualeft = array_slice($thidua, 0, 3);
                                $thiduaright = array_slice($thidua, 3, 5);

                                ?>
                                <div class="tab-left">
                                    <?php
                                    foreach ($thidualeft as $post) {
                                        $img = $post['image'];
                                        $slug = $post['slug'];
                                        echo "<div><a href='./baiviet/$slug'>";
                                        echo "<img src='$img'>";
                                        echo $post['title'];
                                        echo "</a></div>";
                                    }
                                    ?>
                                </div>
                                <div class="tab-right">
                                    <?php
                                    if ($user != null && $user['role'] == 'Admin') {
                                        echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/thidua">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($thiduaright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./baiviet/$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <a href="./thidua" class="other-news">Xem các tin khác</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right-column">
                        <div class="notifications">
                            <?php
                            if ($user != null && $user['role'] == 'Admin') {
                                echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/thongbao">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
                            <a href="./thongbao">
                                <h2>Thông báo</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['thong_bao'] as $post) {
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='./baiviet/$slug'>";
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
                            if ($user != null && $user['role'] == 'Admin') {
                                echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./vietbai/tintucchung">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
                            <a href="./tintucchung">
                                <h2>Tin tức chung</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['tin_tuc_chung'] as $post) {
                                    $img = $post['image'];
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='./baiviet/$slug'>";
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

                <div class="bottom-content">
                    <div class="swiper mySwiper">
                        <h2>Liên kết</h2>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x200" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x250" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x200" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x200" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x200" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://placehold.co/150x200" alt="">
                            </div>
                        </div>

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>


            </div>
            <footer>
                <?php include "./src/footer.html";?>
            </footer>
        </div>
        </div>
    </section>
    <button id="backToTop" class="back-to-top">
        <i class="fi fi-sr-arrow-to-top"></i>
    </button>
    <?php
    if ($user != null && $user['role'] == 'Admin') {
        echo '    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".expand_option").forEach(button => {
                button.addEventListener("click", function (event) {
                    event.stopPropagation();
                    let dropdown = this.nextElementSibling;

                    document.querySelectorAll(".expand_dropdown").forEach(menu => {
                        if (menu !== dropdown) {
                            menu.classList.remove("show");
                        }
                    });

                    dropdown.classList.toggle("show");
                });
            });

            document.addEventListener("click", function () {
                document.querySelectorAll(".expand_dropdown").forEach(menu => {
                    menu.classList.remove("show");
                });
            });
        });
    </script>';
    }
    ?>
    <script src="./js/main.js"></script>
    <script src="./js/slider.js"></script>
    <script src="./js/topictab.js"></script>
</body>

</html>