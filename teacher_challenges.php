<?php
session_start();
include('config.php');

// Chỉ giáo viên mới được vào
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

$message = '';

// Xử lý xóa challenge (nếu có)
if (isset($_POST['delete_challenge']) && isset($_POST['challenge_id'])) {
    $challenge_id = intval($_POST['challenge_id']);
    // Xóa challenge
    $sql_delete = "DELETE FROM challenges WHERE id = $challenge_id";
    if ($conn->query($sql_delete)) {
        $message = "Đã xóa challenge thành công.";
    } else {
        $message = "Lỗi khi xóa challenge: " . $conn->error;
    }
}

// Lấy danh sách challenge
$sql_ch = "SELECT * FROM challenges ORDER BY created_at DESC";
$result_ch = $conn->query($sql_ch);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Challenge</title>
    <link rel="stylesheet" href="public/style.css">
    <style>
        .challenge-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .challenge-table th,
        .challenge-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .challenge-table th {
            background: #f2f2f2;
        }
        .action-cell {
            white-space: nowrap;
        }
        .action-cell form {
            display: inline-block;
            margin: 0;
        }
        .action-cell form input[type="submit"] {
            background: #dc3545;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
            transition: background 0.3s;
        }
        .action-cell form input[type="submit"]:hover {
            background: #c82333;
        }
        /* Nút "Thêm Challenge" bên trái */
        .top-bar {
            display: flex;
            justify-content: flex-start; /* canh trái */
            align-items: center;
            margin-top: 20px;
        }
        .top-bar a.button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Quản lý Challenge</h1>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="teacher_assignment_upload.php">Giao bài tập</a></li>
                <li><a href="manage_student.php">Quản lý SV</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="content">
        <div class="top-bar">
            <!-- Nút Thêm Challenge nằm bên trái -->
            <a class="button" href="challenge_create.php">Thêm Challenge</a>
        </div>

        <h2>Danh sách Challenge</h2>
        <?php if ($result_ch && $result_ch->num_rows > 0): ?>
            <table class="challenge-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gợi ý</th>
                        <th>File Path</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result_ch->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['challenge_hint'])); ?></td>
                        <td><?php echo htmlspecialchars($row['file_path']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td class="action-cell">
                            <!-- Nút Sửa -->
                            <a class="button" 
                               href="challenge_edit.php?challenge_id=<?php echo $row['id']; ?>">
                               Sửa
                            </a>
                            <!-- Nút Xóa (form POST) -->
                            <form method="post" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa challenge này?');">
                                <input type="hidden" name="challenge_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete_challenge" value="Xóa">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Chưa có challenge nào.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
