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
                        if ($user != null && $user['role'] != 'User') {
                            echo '<a href="/admin">Quản lí</a>';
                            echo '<a href="/taikhoan">Tài khoản</a>';
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