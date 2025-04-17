<?php
require '../config/db_connect.php';

require 'auto_login.php';

$user = autoLogin($conn);
/*
if ($user) {
    header('Location: Home.php');
    exit;
}
*/

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký & Đăng Nhập</title>
    <link rel="icon" href="./logo.ico" type="image/x-icon">
</head>

<div class="toolbar">
    <a href="Home.php">
        <img src="./logo.ico" alt="Logo" class="logo">
    </a>
</div>
<div class="form-container">
    <div class="login-form" id="loginForm">
        <h2>Đăng Nhập</h2>
        <form action="auth.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="action" value="login">
            <label for="username">Username hoặc Email:</label>
            <input type="text" id="usernameLogin" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="passwordLogin" name="password" required><br>
            <label>
                <input type="checkbox" name="remember_me"> Lưu đăng nhập
            </label><br>
            <button type="submit">Đăng Nhập</button>
        </form>
    </div>
</div>

<?php

if (isset($_POST['action']) && $_POST['action'] == 'login') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token không hợp lệ.");
    }
    $userInput = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);
    $userInput = trim($userInput);
    if (!preg_match('/^[a-zA-Z0-9@._-]+$/', $userInput)) {
        echo htmlspecialchars("Tên đăng nhập hoặc email không hợp lệ.", ENT_QUOTES, 'UTF-8');
        return;
    }
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if ($_SESSION['login_attempts'] > 5) {
        die("Bạn đã nhập sai quá nhiều lần, vui lòng thử lại sau 10 phút.");
    }
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
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['login_attempts'] = 0;

            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $token);

                $sqlUpdate = "UPDATE user_account SET session_token = ? WHERE user_id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("si", $hashedToken, $user['user_id']);
                $stmtUpdate->execute();

                setcookie('token', $token, time() + (30 * 24 * 60 * 60), "/", "", true, true);
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['login_time'] = time();
            }
            unset($_SESSION['csrf_token']);
            header('Location: http://localhost/');
        } else {
            $_SESSION['login_attempts']++;
            echo htmlspecialchars("Mật khẩu không chính xác!", ENT_QUOTES, 'UTF-8');
        }
    } else {
        echo htmlspecialchars("Username hoặc Email không tồn tại!", ENT_QUOTES, 'UTF-8');
    }
}


$conn->close();
?>
</body>

</html>