<?php
require "../../config/db_connect.php";
include "../auto_login.php";

$user = autoLogin($conn);
if ($user['role'] != 'Admin') {
    exit("Không có quyền truy cập");
}
$type = $_GET['type'] ?? 1;
$type = filter_var($type, FILTER_SANITIZE_NUMBER_INT);
$type = (int) $type;

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
    <base href="/server/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đoàn Thanh Niên Trường Đại Học Tây Nguyên</title>
    <link href="../logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="/css/index.css" type="text/css">
    <link rel="stylesheet" href="/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/css/news.css" type="text/css">
    <link rel="stylesheet" href="/css/editor.css" type="text/css">
    <link href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/attaches@latest"></script>
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
                                    echo '<a href="../auth.php">Đăng nhập</a>';
                                } else {
                                    echo '<a href="../logout.php">Đăng xuất</a>';
                                }
                                ?>
                                <?php
                                if ($user != null && $user['role'] == 'Admin') {
                                    echo '<a href="../edit.php">Quản lí</a>';
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
                            <li><a href="/">Home</a></li>
                            <li class="dropdown">
                                <a>Giới thiệu</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Giới thiệu chung</a></li>
                                    <li><a href="../updating.php">Các thành tích đạt được</a></li>
                                    <li><a href="../updating.php">Cơ cấu tổ chức</a></li>
                                    <li><a href="../updating.php">Liên hệ</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a>Đơn vị trực thuộc</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Liên chi Đoàn, Chi đoàn trực thuộc</a></li>
                                    <li><a href="../updating.php">CLB, Tổ, Đội</a></li>
                                    <li><a href="../updating.php">Đăng ký lịch tuần</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a>Chương trình sự kiện</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Hoạt động đoàn trường</a></li>
                                    <li><a href="../updating.php">Hoạt động LCĐ, Chi đoàn trực thuộc</a></li>
                                    <li><a href="../updating.php">Hoạt động CLB, Tổ, Đội trực thuộc</a></li>
                                    <li><a href="../updating.php">Hoạt động tình nguyện</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a>Tổ chức kiểm tra</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Thông báo</a></li>
                                    <li><a href="../updating.php">Khen thưởng-Kỷ luật</a></li>
                                    <li><a href="../updating.php">Kiện toàn nhân sự</a></li>
                                    <li><a href="../updating.php">Quy chế đánh giá đoàn viên, chi đoàn, liên chi
                                            đoàn</a></li>
                                </ul>
                            <li class="dropdown">
                                <a>Công tác Đảng</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Thông báo</a></li>
                                    <li><a href="../updating.php">Hoạt động chi bộ Sinh viên</a></li>
                                    <li><a href="../updating.php">Văn bản hướng dẫn</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>NCKH Sinh viên</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Thông báo</a></li>
                                    <li><a href="../updating.php">Hoạt động NCKH</a></li>
                                    <li><a href="../updating.php">Công trình tiêu biểu</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>Hoạt động Tình nguyện</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Thông báo</a></li>
                                    <li><a href="../updating.php">Chương trình tình nguyện</a></li>
                                </ul>
                            <li class="dropdown">
                                <a>Văn bản</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Văn bản Đoàn cấp trên</a></li>
                                    <li><a href="../updating.php">Văn bản Đoàn trường</a></li>
                                    <li><a href="../updating.php">Biểu mẫu văn bản</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>TTN Book</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Danh mục sách</a></li>
                                    <li><a href="../updating.php">Giới thiệu sách</a></li>
                                    <li><a href="../updating.php">Đặt mua sách</a></li>

                                </ul>
                            <li class="dropdown">
                                <a>Đại hội các cấp</a>
                                <ul class="dropdown-menu">
                                    <li><a href="../updating.php">Kế hoạch - Hướng dẫn</a></li>
                                    <li><a href="../updating.php">Thông tin Đại hội Đoàn các cấp</a></li>

                                </ul>
                            <li> <a>About TTN</a> </li>
                            </li>
                            <li><a href="../updating.php">Liên hệ</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container">
                <div class="events">
                    <h2 class="event-title">
                        <a href="../sukien">
                            Sự Kiện
                        </a>
                    </h2>
                    <ul>
                        <?php
                        foreach ($data['su_kien'] as $post) {
                            $slug = $post['slug'];
                            echo "<li><a href='../baiviet/$slug'>";
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
                                <li><span>/</span>
                                    <?php
                                    switch ($type) {
                                        case '1':
                                            echo "<a href='../tintucchung'>Tin tức chung</a>";
                                            break;
                                        case '2':
                                            echo "<a href='../thongbao'>Thông báo</a>";
                                            break;
                                        case '3':
                                            echo "<a href='../sukien'>Sự kiện</a>";
                                            break;
                                        case '4':
                                            echo "<a href='../tinnoibat'>Tin nổi bật</a>";
                                            break;
                                        case '5':
                                            echo "<a href='../lichtuan'>Lịch tuần</a>";
                                            break;
                                        case '6':
                                            echo "<a href='../thidua'>Thi đua</a>";
                                            break;
                                        case '7':
                                            echo "<a href='../doanvien'>Đoàn viên</a>";
                                            break;
                                        default:
                                            echo "<a href='../tintucchung'>Tin tức chung</a>";
                                            break;
                                    }
                                    ?>
                                </li>
                                <li class="active"><span>/</span>
                                    <a>Viết bài</a>
                                </li>
                            </ul>

                        </div>
                        <label class="form-label" for="titleInput">Tiêu đề:</label>
                        <input class="form-input" type="text" id="titleInput" placeholder="Nhập tiêu đề bài viết">
                        <div class="editorjs" id="editorjs">

                        </div>

                        <button class="btn btn-primary" id="saveButton">Đăng bài</button>
                        <button class="btn btn-secondary" id="savedraftButton">Lưu bản nháp</button>
                        <script>
                            let isSaved = true;
                            let tempFiles = [];
                            let previousBlocks = [];
                            function addTempFile(url) {
                                console.log(url);
                                const match = url.match(/uploads\/.+$/);
                                console.log("===" + match)
                                if (!tempFiles.includes(match)) {
                                    tempFiles.push(match);
                                    console.log('Added to tempFiles:', match);
                                }
                            }
                            async function checkNewFileBlocks() {
                                try {
                                    const outputData = await editor.save();
                                    const currentBlocks = outputData.blocks;

                                    currentBlocks.forEach((block) => {
                                        if (
                                            (block.type === 'image' || block.type === 'attaches') &&
                                            block.data.file && block.data.file.url
                                        ) {
                                            const isNewBlock = !previousBlocks.some(
                                                (prevBlock) =>
                                                    prevBlock.type === block.type &&
                                                    prevBlock.data.file.url === block.data.file.url
                                            );
                                            if (isNewBlock) {
                                                addTempFile(block.data.file.url);
                                            }
                                        }
                                    });

                                    previousBlocks = [...currentBlocks];
                                } catch (error) {
                                    console.error('Lỗi khi kiểm tra khối mới:', error);
                                }
                            }
                            function toggleExitWarning(enable) {
                                if (enable) {
                                    window.addEventListener('beforeunload', handleBeforeUnload);
                                } else {
                                    window.removeEventListener('beforeunload', handleBeforeUnload);
                                }
                            }

                            function handleBeforeUnload(event) {
                                if (isSaved) return;
                                event.preventDefault();
                                event.returnValue = '';
                                return 'Bạn có chắc chắn muốn thoát? Dữ liệu chưa lưu sẽ bị xóa.';
                            }

                            async function deleteServerData() {
                                try {
                                    const response = await fetch('./delete_temp_post.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({ files: tempFiles }),
                                    });
                                    const result = await response.json();
                                    if (result.success) {
                                        console.log('Dữ liệu tạm trên server đã được xóa.');
                                        tempFiles = [];
                                    } else {
                                        console.error('Lỗi khi xóa dữ liệu:', result.message);
                                    }
                                } catch (error) {
                                    console.error('Lỗi khi gửi yêu cầu xóa:', error);
                                }
                            }

                            const editor = new EditorJS({
                                holder: 'editorjs',
                                tools: {
                                    header: {
                                        class: Header,
                                        inlineToolbar: true,
                                    },
                                    image: {
                                        class: ImageTool,
                                        config: {
                                            endpoints: {
                                                byFile: './upload.php',
                                                byUrl: './fetch.php'
                                            },
                                            features: {
                                                border: true,
                                                caption: 'optional',
                                                stretch: false
                                            }
                                        },
                                    },
                                    attaches: {
                                        class: AttachesTool,
                                        config: {
                                            endpoint: './upload_attaches.php',
                                            buttonText: 'Tải tệp lên',
                                            errorMessage: 'Tải tệp thất bại',

                                        }
                                    },
                                    list: {
                                        class: EditorjsList,
                                        inlineToolbar: true,
                                    },
                                    quote: {
                                        class: Quote,
                                        inlineToolbar: true
                                    },
                                    code: {
                                        class: CodeTool
                                    },
                                    table: {
                                        class: Table,
                                        inlineToolbar: true
                                    },
                                    delimiter: Delimiter,
                                    embed: Embed,
                                    warning: {
                                        class: Warning,
                                        inlineToolbar: true
                                    },

                                    marker: Marker
                                },
                                onChange: async () => {
                                    isSaved = false;
                                    toggleExitWarning(true);
                                    await checkNewFileBlocks();

                                },
                            });

                            var type_post = <?php echo json_encode($type); ?>;
                            document.getElementById('saveButton').addEventListener('click', () => {
                                const title = document.getElementById('titleInput').value;
                                if (!title) {
                                    alert('Vui lòng nhập tiêu đề!');
                                    return;
                                }
                                editor.save().then((outputData) => {
                                    const dataToSave = {
                                        type: type_post,
                                        title: title,
                                        content: outputData
                                    };
                                    fetch('./save.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify(dataToSave)
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            isSaved = true;
                                            toggleExitWarning(false);
                                            tempFiles = [];
                                            alert(data.message);
                                        })
                                        .catch(error => {
                                            alert('Lỗi: ', error);
                                            console.log(error);
                                        });
                                }).catch((error) => {
                                    alert('Lỗi khi lưu', error)
                                });
                            });
                            document.getElementById('savedraftButton').addEventListener('click', () => {
                                const title = document.getElementById('titleInput').value;
                                if (!title) {
                                    alert('Vui lòng nhập tiêu đề!');
                                    return;
                                }
                                editor.save().then((outputData) => {
                                    const dataToSave = {
                                        status: "Writing",
                                        type: type_post,
                                        title: title,
                                        content: outputData
                                    };
                                    fetch('./save.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify(dataToSave)
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            isSaved = true;
                                            toggleExitWarning(false);
                                            tempFiles = [];
                                            alert(data.message);
                                        })
                                        .catch(error => {
                                            alert('Lỗi: ', error)
                                        });
                                }).catch((error) => {
                                    alert('Lỗi khi lưu', error)
                                });
                            });
                            window.addEventListener('unload', () => {
                                if (!isSaved) {
                                    navigator.sendBeacon('./delete_temp_post.php', JSON.stringify({ files: tempFiles }));
                                }
                            });
                        </script>
                    </div>

                    <div class="right-column">
                        <div class="notifications">
                            <a href="../general.php?type=2">
                                <h2>Thông báo</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['thong_bao'] as $post) {
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='../baiviet/$slug'>";
                                    echo htmlspecialchars($post['title']);
                                    echo "</a>";
                                    echo "<p>" . date('d/m/Y', strtotime($post['last_update']));
                                    echo "</p></div></li>";
                                }
                                ?>

                            </ul>
                        </div>
                        <div class="news">
                            <a href="../tintucchung">
                                <h2>Tin tức chung</h2>
                            </a>
                            <ul>
                                <?php
                                foreach ($data['tin_tuc_chung'] as $post) {
                                    $img = $post['image'];
                                    $slug = $post['slug'];
                                    echo "<li><div><a href='../baiviet/$slug'>";
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
                            <a href="../updating.php" class="social-icon">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/yT/r/aGT3gskzWBf.ico?_nc_eui2=AeEYIIQ-Kmjep1NfAQPVImFKrSiY817De8atKJjzXsN7xos_Ow8tqDmAAVBL2IrprG6cBF5DK_r47yJqGTexFO3v"
                                    alt="Facebook">
                            </a>
                            <a href="../updating.php" class="social-icon">
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