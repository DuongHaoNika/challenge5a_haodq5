<?php
session_start();
include('config.php');

// Nếu chưa đăng nhập thì chuyển hướng đến trang login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

// Biến $view xác định đang xem phần nào: assignments / challenge / default
$view = isset($_GET['view']) ? $_GET['view'] : '';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang Chủ</title>
  <!-- Liên kết tới file CSS trong thư mục public -->
  <link rel="stylesheet" href="public/style.css">
  <style>
    /* Style cho 2 ô (cards) hiển thị Assignment/Challenge */
    .card-container {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      flex: 1;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: background 0.3s;
    }
    .card:hover {
      background: #f9f9f9;
    }
    .card h3 {
      margin-bottom: 10px;
      font-size: 18px;
    }
    .card a.button {
      margin-top: 10px;
      display: inline-block;
    }
    .challenge-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    .challenge-table th,
    .challenge-table td {
      border: 1px solid #ddd;
      padding: 10px;
    }
    .challenge-table th {
      background: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Chào mừng, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
      <nav>
        <ul>
          <li><a href="index.php">Trang chủ</a></li>
          <li><a href="profile.php?id=<?php echo $_SESSION['user']['id'] ?>">Hồ sơ</a></li>
          <li><a href="logout.php">Đăng xuất</a></li>
        </ul>
      </nav>
    </header>
    <div class="content">
      <?php if ($user['role'] == 'student'): ?>
        
        <!-- Kiểm tra xem đang xem phần nào qua $_GET['view'] -->
        <?php if ($view == 'assignments'): ?>
          <!-- Hiển thị danh sách Bài tập -->
          <h2>Danh sách Bài tập</h2>
          <?php
            $student_id = $user['id'];
            $sql = "SELECT a.*, s.id AS submission_id
                    FROM assignments a
                    LEFT JOIN submissions s 
                           ON a.id = s.assignment_id
                           AND s.student_id = $student_id
                    ORDER BY a.created_at DESC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0):
          ?>
            <table class="assignment-table">
              <tr>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Deadline</th>
                <th>File</th>
                <th>Thao tác</th>
                <th>Trạng thái</th>
              </tr>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['title']); ?></td>
                  <td><?php echo htmlspecialchars($row['description']); ?></td>
                  <td><?php echo htmlspecialchars($row['deadline']); ?></td>
                  <td>
                    <?php if (!empty($row['file_path'])): ?>
                      <a class="button" href="<?php echo $row['file_path']; ?>" download>Tải về</a>
                    <?php else: ?>
                      <em>Không có file</em>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a class="button" href="submission_upload.php?assignment_id=<?php echo $row['id']; ?>">
                      Nộp bài
                    </a>
                  </td>
                  <td>
                    <?php if ($row['submission_id']): ?>
                      <span style="color: green; font-weight: bold;">Đã nộp</span>
                    <?php else: ?>
                      <span style="color: red; font-weight: bold;">Chưa nộp</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </table>
          <?php else: ?>
            <p>Hiện không có bài tập nào.</p>
          <?php endif; ?>
        
        <?php elseif ($view == 'challenge'): ?>
          <!-- Hiển thị danh sách Challenge -->
          <h2>Danh sách Challenge</h2>
          <?php
            // Ví dụ: Lấy danh sách challenge
            $sql_challenge = "SELECT * FROM challenges ORDER BY created_at DESC";
            $result_challenge = $conn->query($sql_challenge);
            
            if ($result_challenge && $result_challenge->num_rows > 0):
          ?>
            <table class="challenge-table">
              <tr>
                <th>ID</th>
                <th>Gợi ý</th>
                <th>Thao tác</th>
              </tr>
              <?php while ($row_ch = $result_challenge->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row_ch['id']); ?></td>
                  <td><?php echo nl2br(htmlspecialchars($row_ch['challenge_hint'])); ?></td>
                  <td>
                    <!-- Link sang trang solve challenge, ví dụ: challenge_solve.php?challenge_id= -->
                    <a class="button" href="challenge_solve.php?challenge_id=<?php echo $row_ch['id']; ?>">
                      Giải Challenge
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </table>
          <?php else: ?>
            <p>Chưa có challenge nào.</p>
          <?php endif; ?>
        
        <?php else: ?>
          <!-- Màn hình mặc định (hiển thị 2 ô: Assignments và Challenge) -->
          <h2>Chọn Chức năng</h2>
          <div class="card-container">
            <div class="card">
              <h3>Assignments</h3>
              <p>Xem danh sách bài tập và nộp bài</p>
              <a class="button" href="index.php?view=assignments">Xem</a>
            </div>
            <div class="card">
              <h3>Challenge</h3>
              <p>Giải các Challenge do giáo viên tạo</p>
              <a class="button" href="index.php?view=challenge">Xem</a>
            </div>
          </div>
        <?php endif; ?>
        
      <?php elseif ($user['role'] == 'teacher'): ?>
        <h2>Công cụ quản lý</h2>
        <ul class="management-list">
          <li><a class="button" href="teacher_assignments.php">Giao bài tập</a></li>
          <li><a class="button" href="manage_student.php">Quản lý sinh viên</a></li>
          <li><a class="button" href="teacher_view_submissions.php">Xem bài làm</a></li>
          <li><a class="button" href="teacher_challenges.php">Challenges</a></li>
          <!-- Bạn có thể thêm các link khác theo nhu cầu -->
        </ul>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
