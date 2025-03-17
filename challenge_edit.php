<?php
session_start();
include('config.php');

// Chỉ giáo viên mới được tạo challenge
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

$message = '';

if(!isset($_GET['challenge_id'])) {
    die("Challenge id?");
}

$chall_id = $_GET["challenge_id"];


if (isset($_POST['challenge_edit'])) {
    $teacher_id = $_SESSION['user']['id'];
    $challenge_hint = $conn->real_escape_string($_POST['challenge_hint']);
    if (isset($_FILES['challenge_file']) && $_FILES['challenge_file']['error'] == 0) {
        $upload_dir = 'uploads/challenges/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['challenge_file']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['challenge_file']['tmp_name'], $target)) {
            // Đọc nội dung file txt
            $file_content_raw = file_get_contents($target);
            // Escape chuỗi để an toàn trong SQL
            $file_content = $conn->real_escape_string($file_content_raw);
            
            $sql = "UPDATE challenges WHERE id=$chall_id SET challenge_hint=$challenge_hint, file_path='$target', file_content='$file_content')";
            if ($conn->query($sql)) {
                $message = "Challenge updated successfully!";
            } else {
                $message = "Database error: " . $conn->error;
            }
        } else {
            $message = "File upload failed.";
        }
    } else {
        $message = "Please select a challenge file to upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Create Challenge</title>
    <!-- Liên kết tới file CSS chung -->
    <link rel="stylesheet" href="public/style.css">
    <!-- CSS riêng cho trang challenge_create -->
    <style>
        .challenge-form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .challenge-form h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .challenge-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .challenge-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .challenge-form input[type="file"] {
            margin-top: 5px;
        }
        .challenge-form input[type="submit"] {
            display: block;
            width: 200px;
            margin: 20px auto 0;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .challenge-form input[type="submit"]:hover {
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
        .message.error {
            background: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Create Challenge</h1>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="teacher_assignment_upload.php">Giao bài tập</a></li>
                <li><a href="teacher_edit_student.php">Quản lý SV</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="challenge-form">
        <h2>Tạo Challenge</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="id" value="<?php echo $chall_id ?>" hidden>
            <label for="challenge_hint">Gợi ý Challenge:</label>
            <textarea name="challenge_hint" id="challenge_hint" rows="4" placeholder="Nhập gợi ý cho challenge" required></textarea>
            
            <label for="challenge_file">File Challenge (txt):</label>
            <input type="file" name="challenge_file" id="challenge_file" accept=".txt" required>
            
            <input type="submit" name="challenge_edit" value="Submit">
        </form>
    </div>
</div>
</body>
</html>
