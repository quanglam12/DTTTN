<?php
// 1. Khởi tạo Session và Include file cấu hình ngay đầu file
session_start(); 
require '../config/db_connect.php';
// require 'auto_login.php'; // Tạm ẩn nếu không cần thiết lúc debug
include 'settings.php';

// Kiểm tra nếu biến $Domain chưa tồn tại thì gán mặc định để tránh lỗi
if (!isset($Domain)) {
    $Domain = 'Home.php'; // Hoặc đường dẫn trang chủ của bạn
}

// 2. Xử lý Logic Đăng Nhập (Đưa lên đầu file)
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    $userInput = trim($_POST['username']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Validate Input cơ bản
    if (!preg_match('/^[a-zA-Z0-9@._-]+$/', $userInput)) {
        $error_message = "Tên đăng nhập hoặc email không hợp lệ.";
    } else {
        // Kiểm tra số lần đăng nhập sai
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if ($_SESSION['login_attempts'] > 5) {
            $error_message = "Bạn đã nhập sai quá nhiều lần, vui lòng thử lại sau.";
        } else {
            // Query DB
            if (filter_var($userInput, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM user_account WHERE email = ?";
            } else {
                $sql = "SELECT * FROM user_account WHERE username = ?";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $userInput);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    // --- ĐĂNG NHẬP THÀNH CÔNG ---
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['login_attempts'] = 0; // Reset số lần sai
                    
                    // Lưu Login Time để debug nếu cần
                    $_SESSION['login_time'] = time();

                    if ($rememberMe) {
                        $token = bin2hex(random_bytes(32));
                        $hashedToken = hash('sha256', $token);

                        $sqlUpdate = "UPDATE user_account SET session_token = ? WHERE user_id = ?";
                        $stmtUpdate = $conn->prepare($sqlUpdate);
                        $stmtUpdate->bind_param("si", $hashedToken, $user['user_id']);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();

                        // Set Cookie
                        setcookie('token', $token, time() + (30 * 24 * 60 * 60), "/", "", true, true);
                    } else {
                        session_regenerate_id(true);
                    }
                    
                    $stmt->close();
                    $conn->close();

                    // Chuyển hướng
                    header('Location: ' . $Domain);
                    exit(); 

                } else {
                    $_SESSION['login_attempts']++;
                    $error_message = "Mật khẩu không chính xác!";
                }
            } else {
                $error_message = "Username hoặc Email không tồn tại!";
            }
            $stmt->close();
        }
    }
}
// Kết thúc xử lý PHP
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="icon" href="./logo.ico" type="image/x-icon">
    <style>
        /* (Giữ nguyên CSS của bạn ở đây) */
        /* --- Reset & cơ bản --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Tahoma, sans-serif; background-color: #f5f5f5; color: #333; min-height: 100vh; display: flex; flex-direction: column; }
        .toolbar { background-color: #d32f2f; padding: 10px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); display: flex; align-items: center; }
        .toolbar .logo { height: 45px; transition: transform 0.2s; }
        .toolbar .logo:hover { transform: scale(1.1); }
        .form-container { flex: 1; display: flex; justify-content: center; align-items: center; }
        .login-form { background-color: #ffffff; border: 2px solid #c62828; border-radius: 12px; width: 100%; max-width: 400px; padding: 40px 30px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); text-align: center; animation: fadeInUp 0.6s ease; }
        .login-form h2 { color: #d32f2f; margin-bottom: 25px; font-size: 24px; }
        .login-form input[type="text"], .login-form input[type="password"] { width: 100%; padding: 10px 14px; margin-bottom: 18px; border: 1px solid #aaa; border-radius: 8px; font-size: 15px; transition: border-color 0.2s; }
        .login-form input[type="text"]:focus, .login-form input[type="password"]:focus { border-color: #d32f2f; outline: none; }
        .login-form label { display: block; text-align: left; font-size: 14px; color: #555; margin-bottom: 6px; }
        .login-form label input[type="checkbox"] { margin-right: 6px; }
        .login-form button { background-color: #388e3c; color: white; border: none; border-radius: 8px; padding: 12px; font-size: 16px; cursor: pointer; width: 100%; transition: background-color 0.3s, transform 0.1s; }
        .login-form button:hover { background-color: #2e7d32; }
        .login-form button:active { transform: scale(0.98); }
        .error { color: #c62828; margin-top: 10px; display: block;}
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 480px) { .login-form { padding: 30px 20px; width: 90%; } }
    </style>
</head>
<body>

<div class="toolbar">
    <a href="Home.php">
        <img src="./img/logo/doan.ico" alt="Logo" class="logo">
    </a>
</div>

<div class="form-container">
    <div class="login-form" id="loginForm">
        <h2>Đăng Nhập</h2>
        
        <form action="" method="POST">
            <input type="hidden" name="action" value="login">
            
            <label for="username">Username hoặc Email:</label>
            <input type="text" id="usernameLogin" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            <br>

            <label for="password">Password:</label>
            <input type="password" id="passwordLogin" name="password" required><br>
            
            <label>
                <input type="checkbox" name="remember_me"> Lưu đăng nhập
            </label><br>
            
            <button type="submit">Đăng Nhập</button>

            <?php if (!empty($error_message)): ?>
                <span class="error"><?php echo htmlspecialchars($error_message); ?></span>
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
</html>