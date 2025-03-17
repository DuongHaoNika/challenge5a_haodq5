<?php
session_start();
include('config.php');

// Chỉ giáo viên mới được xem bài làm
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

$assignment_id = intval($_GET['assignment_id']);
$sql = "SELECT s.*, u.full_name as student_name 
        FROM submissions s 
        JOIN users u ON s.student_id = u.id 
        WHERE s.assignment_id = $assignment_id 
        ORDER BY s.submitted_at DESC";
$result = $conn->query($sql);
?>
<html>
  <body>
    <h3>Submissions for Assignment ID <?php echo $assignment_id; ?></h3>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          Student: <?php echo $row['student_name']; ?><br>
          Submitted At: <?php echo $row['submitted_at']; ?><br>
          <a href="<?php echo $row['file_path']; ?>" download>Download Submission</a>
        </li>
      <?php endwhile; ?>
    </ul>
  </body>
</html>
