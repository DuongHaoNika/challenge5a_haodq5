<?php
session_start();
include('config.php');

// Chỉ cho sinh viên truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die("Access denied.");
}

// Lấy danh sách bài tập
$sql = "SELECT * FROM assignments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Assignment List</title>
    <!-- Liên kết CSS chung -->
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <div class="container">
        <!-- Header (tùy chọn) -->
        <header>
            <h1>Danh sách Bài Tập</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="student_profile.php">Hồ sơ</a></li>
                    <li><a href="logout.php">Đăng xuất</a></li>
                </ul>
            </nav>
        </header>
        
        <div class="content">
            <h2>Các Bài Tập</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="assignment-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                            <?php echo htmlspecialchars($row['description']); ?><br>
                            <em>Deadline: <?php echo htmlspecialchars($row['deadline']); ?></em><br><br>
                            <a class="button" href="<?php echo $row['file_path']; ?>" download>Tải Bài Tập</a>
                            <a class="button" href="submission_upload.php?assignment_id=<?php echo $row['id']; ?>">Nộp Bài</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Hiện chưa có bài tập nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
