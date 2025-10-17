<?php
$servername = "localhost";
$dbUsername = "admin"; 
$dbPassword = "password";  // Sửa lại pass và dbname để kết nối
$dbname = "db";

//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


