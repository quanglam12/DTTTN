<?php
require "../config/db_connect.php";
include "auto_login.php";
$user = autoLogin($conn);

if (!$user) {
    header("Location: /auth.php");
    exit();
}

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }

    $sql = "UPDATE user_account SET fullname = ?, email = ?, phone_number = ?, date_of_birth = ?, address = ?, gender = ?" .
        (!empty($new_password) ? ", password = ?" : "") .
        " WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssssssi", $fullname, $email, $phone_number, $date_of_birth, $address, $gender, $hashed_password, $user['user_id']);
    } else {
        $stmt->bind_param("ssssssi", $fullname, $email, $phone_number, $date_of_birth, $address, $gender, $user['user_id']);
    }

    if ($stmt->execute()) {
        $success = true;
        $user = autoLogin($conn); // reload
    } else {
        $errors[] = "Cập nhật thất bại: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi-vn">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin cá nhân</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/logo.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <link rel="stylesheet" href="/css/render.css">

    <style>
        .edit-profile-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        .edit-profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .edit-profile-container label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        .edit-profile-container input,
        .edit-profile-container select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .edit-profile-container button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .edit-profile-container button:hover {
            background-color: #0052a3;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <section id="wrapper">
        <?php include('./src/header.php'); ?>
        <?php include('./src/banner.html'); ?>

        <div class="main">
            <?php include('./src/navbar.html'); ?>
            <div class="container">
                <div class="edit-profile-container">
                    <h2>Chỉnh sửa thông tin cá nhân</h2>

                    <?php if ($success): ?>
                        <p class="success-message">Cập nhật thành công!</p>
                    <?php endif; ?>

                    <?php foreach ($errors as $error): ?>
                        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>

                    <form method="POST">
                        <label>Tên hiển thị:
                            <input type="text" name="fullname"
                                value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                        </label>

                        <label>Email:
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                                required>
                        </label>

                        <label>Số điện thoại:
                            <input type="text" name="phone_number"
                                value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                        </label>

                        <label>Ngày sinh:
                            <input type="date" name="date_of_birth"
                                value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">
                        </label>

                        <label>Địa chỉ:
                            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                        </label>

                        <label>Giới tính:
                            <select name="gender">
                                <option value="Male" <?php if ($user['gender'] == 'Male')
                                    echo "selected"; ?>>Nam</option>
                                <option value="Female" <?php if ($user['gender'] == 'Female')
                                    echo "selected"; ?>>Nữ
                                </option>
                                <option value="Other" <?php if ($user['gender'] == 'Other')
                                    echo "selected"; ?>>Khác
                                </option>
                            </select>
                        </label>

                        <label>Mật khẩu mới (bỏ trống nếu không đổi):
                            <input type="password" name="password">
                        </label>

                        <button type="submit">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="/js/main.js"></script>
</body>

</html>