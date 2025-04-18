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
    <title>Đoàn Thanh Niên Trường Đại Học Tây Nguyên</title>
    <link href="./logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="./css/index.css" type="text/css">
    <link rel="stylesheet" href="./css/responsive.css" type="text/css">
    <link rel="stylesheet" href="./css/slider.css" type="text/css">
    <link rel="stylesheet" href="./css/topictab.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uicons@1.0.0/css/uicons.css">
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
                    <img src="./img/B12.jpg" alt="Banner">
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
                    <?php
                    if ($user != null && $user['role'] == 'Admin') {
                        echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./server/post.php?type=3">Viết bài</a>
                                        </div>
                                    </div>';
                    }
                    ?>
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
                                            <a href="./server/post.php?type=4">Viết bài</a>
                                        </div>
                                    </div>';
                                        }
                                        ?>
                                        <?php
                                        $first = true;
                                        foreach ($data['tin_noi_bat'] as $post) {
                                            $slug = $post['slug'];
                                            $image = $post['image'];
                                            echo "<a href='./news.php?slug=$slug'>";
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
                                        echo "<div><a href='./news.php?slug=$slug'>";
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
                                            <a href="./server/post.php?type=7">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($doanvienright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./news.php?slug=$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>

                                    </ul>
                                    <a href="./general.php?type=7" class="other-news">Xem các tin khác</a>
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
                                        echo "<div><a href='./news.php?slug=$slug'>";
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
                                            <a href="./server/post.php?type=5">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($lichtuanright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./news.php?slug=$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <a href="./general.php?type=5" class="other-news">Xem các tin khác</a>
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
                                        echo "<div><a href='./news.php?slug=$slug'>";
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
                                            <a href="./server/post.php?type=6">Viết bài</a>
                                        </div>
                                    </div>';
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        foreach ($thiduaright as $post) {
                                            $img = $post['image'];
                                            $slug = $post['slug'];
                                            echo "<li><a href='./news.php?slug=$slug'>";
                                            echo $post['title'];
                                            echo "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <a href="./general.php?type=6" class="other-news">Xem các tin khác</a>
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
                                            <a href="./server/post.php?type=2">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
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
                            <?php
                            if ($user != null && $user['role'] == 'Admin') {
                                echo '<div class="expand_wrapper">
                                        <button class="expand_option">☰</button>
                                        <div class="expand_dropdown">
                                            <a href="./server/post.php?type=1">Viết bài</a>
                                        </div>
                                    </div>';
                            }
                            ?>
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