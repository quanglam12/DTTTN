<?php
session_start();
require 'db_connect.php';

if (isset($_COOKIE['token'])) {
    $token = hash('sha256', $_COOKIE['token']);

    $sql = "UPDATE user_account SET session_token = NULL WHERE session_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();

    setcookie('token', '', time() - 3600, '/', "", true, true);
}

$_SESSION = [];
session_unset();
session_destroy();
header('Location: http://localhost/');
exit;