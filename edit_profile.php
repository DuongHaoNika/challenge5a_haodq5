<?php
session_start();
include('config.php');

if (!isset($_SESSION['user'])) {
    die("Access denied.");
}

$user_id = $_SESSION['user']['id'];
$message = '';

if (isset($_POST['update_profile'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Cập nhật email và số điện thoại
    $sql = "UPDATE users SET email='$email', phone='$phone' WHERE id=$user_id";
    $conn->query($sql);
    
    // Xử lý upload avatar từ file
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $upload_dir = 'uploads/avatars/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['avatar']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $sql = "UPDATE users SET avatar='$target' WHERE id=$user_id";
            $conn->query($sql);
        }
    } else if (!empty($_POST['avatar_url'])) {
        // Cập nhật avatar theo URL
        $avatar_url = $conn->real_escape_string($_POST['avatar_url']);
        $sql = "UPDATE users SET avatar='$avatar_url' WHERE id=$user_id";
        $conn->query($sql);
    }
    
    $message = "Profile updated.";
    // (Bạn có thể cập nhật lại session nếu cần)
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Sinh Viên</title>
    <!-- Liên kết tới file CSS chung -->
    <link rel="stylesheet" href="public/style.css">
    <!-- CSS riêng cho trang student_profile -->
    <style>
        .profile-form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .profile-form h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .profile-form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .profile-form input[type="email"],
        .profile-form input[type="text"],
        .profile-form input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .profile-form input[type="submit"] {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .profile-form input[type="submit"]:hover {
            background: #0056b3;
        }
        .message {
            max-width: 600px;
            margin: 10px auto;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .message.success {
            background: #dff0d8;
            border: 1px solid #c3e6cb;
            color: #3c763d;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Profile</h1>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="assignment_list.php">Bài tập</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <?php if (!empty($message)): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <div class="profile-form">
        <h2>Cập nhật thông tin cá nhân</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
            
            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone']); ?>" required>
            
            <label for="avatar">Avatar Upload File:</label>
            <input type="file" id="avatar" name="avatar">
            
            <label for="avatar_url">Avatar URL:</label>
            <input type="text" id="avatar_url" name="avatar_url" placeholder="Nhập URL nếu không upload file">
            
            <input type="submit" name="update_profile" value="Cập nhật hồ sơ">
        </form>
    </div>
</div>
</body>
</html>
