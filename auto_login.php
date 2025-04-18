<?php
require(__DIR__ . '/../config/db_connect.php');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
session_regenerate_id(true);
function autoLogin($conn)
{
    if (isset($_COOKIE['token'])) {
        $token = hash('sha256', $_COOKIE['token']);
        $sql = "SELECT * FROM user_account WHERE session_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $newToken = bin2hex(random_bytes(32));
            $hashedNewToken = hash('sha256', $newToken);

            $updateSql = "UPDATE user_account SET session_token = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $hashedNewToken, $user['user_id']);
            $updateStmt->execute();

            setcookie('token', $newToken, time() + (30 * 24 * 60 * 60), "/", "", true, true);

            return $user;
        }
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM user_account WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
    }
    return null;
}