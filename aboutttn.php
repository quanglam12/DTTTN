<?php
require "../config/db_connect.php";
include "auto_login.php";
$user = autoLogin($conn);

?>
<!DOCTYPE html>
<html lang="vi-vn">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đoàn TNCS Hồ Chí Minh trường Đại học Tây Nguyên</title>
    <link href="/logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="/css/index.css" type="text/css">
    <link rel="stylesheet" href="/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/css/slider.css" type="text/css">
    <link rel="stylesheet" href="/css/topictab.css" type="text/css">
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
                                <a href="/">
                                    <img src="/logo.ico" alt="Logo">
                                </a>
                                <p> Cổng thông tin điện tử</p>
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
            <?php include('./src/navbar.html'); ?>
        </div>
        <div class="main">
            <div class="container">
                <style>
                    .history h2 {
                        font-size: 1.75rem;
                        font-weight: bold;
                        text-align: center;
                        margin-bottom: 1.25rem;
                        color: #2c3e50;
                        position: relative;
                    }

                    .history h2::after {
                        content: "";
                        display: block;
                        width: 5rem;
                        height: 0.1875rem;
                        background: #3498db;
                        margin: 0.625rem auto 0 auto;
                        border-radius: 0.125rem;
                    }

                    .history p {
                        font-size: 1.125rem;
                        line-height: 1.8;
                        color: #444;
                        margin-bottom: 1rem;
                        text-align: justify;
                    }
                </style>
                <div class="history">
                    <h2>
                        Lịch sử hình thành và phát triển
                    </h2>
                    <p style="text-align: justify;">Trường Đại học Tây Nguyên được thành lập theo Quyết định số 298/CP
                        ngày
                        11/11/1977 của Hội đồng Chính phủ với nhiệm vụ đào tạo cán bộ có trình độ đại học phục vụ yêu
                        cầu
                        phát triển kinh tế, văn hóa, xã hội toàn vùng Tây Nguyên.</p>
                    <p style="text-align: justify;">Trường Đại học Tây Nguyên được thành lập là một sự kiện có ý nghĩa
                        lịch
                        sử đối với đồng bào các dân tộc Tây Nguyên. Đây là điều kiện thuận lợi để con em các dân tộc
                        thiểu
                        số được đào tạo trình độ đại học và sau đại học ngay trên quê hương mình.</p>
                    <p style="text-align: justify;">Là một trường đại học đứng chân trên địa bàn, Trường Đại học Tây
                        Nguyên
                        đã đóng góp đáng kể vào sự nghiệp phát triển kinh tế xã hội của các tỉnh Tây Nguyên. Nhà trường
                        đã
                        đào tạo cho các địa phương Tây Nguyên và cho đất nước hơn 25.000 bác sĩ, cử nhân, kỹ sư các
                        ngành: Y
                        khoa, Sư phạm, Công nghệ thông tin, Nông - Lâm nghiệp, Kinh tế, Giáo dục chính trị... Nhiều
                        người
                        trong số họ đã giữ các cương vị chủ chốt trong các hoạt động lãnh đạo, quản lý trong các cơ quan
                        Đảng, Nhà nước, các cơ sở sản xuất và cơ quan, đơn vị khoa học kỹ thuật của các tỉnh Tây Nguyên
                        và
                        nhiều vùng trong cả nước.</p>
                    <p style="text-align: justify;">Được sự quan tâm rất lớn của Đảng và Nhà nước, sự lãnh đạo, chỉ đạo
                        của
                        Bộ Giáo dục và Đào tạo, sự động viên của các cấp ủy Đảng, chính quyền của các địa phương khu vực
                        Tây
                        Nguyên, Trường Đại học Tây Nguyên từ một cơ sở đào tạo nhỏ bé nay đã trở thành một trường đại
                        học đa
                        ngành, đa cấp và đa lĩnh vực với đội ngũ cán bộ khoa học kỹ thuật khá mạnh, cơ sở vật chất đã
                        từng
                        bước được đầu tư hiện đại. Quy mô đào tạo của Nhà trường ngày càng tăng và ngày càng đa dạng về
                        ngành nghề đào tạo, có khả năng đáp ứng ngày càng nhiều nguồn nhân lực có trình độ khoa học kỹ
                        thuật
                        cao cho các ngành, các cấp ở các địa phương khu vực Tây Nguyên. Với những điều kiện đó, trong
                        tương
                        lai không xa, Tây Nguyên sẽ trở thành một vùng kinh tế trọng điểm của đất nước, một vùng có vị
                        trí
                        chiến lược quan trọng trong tam giác phát triển của khu vực Đông Dương.</p>
                </div>
            </div>
            <footer>
                <?php include "/src/footer.html"; ?>
            </footer>
        </div>