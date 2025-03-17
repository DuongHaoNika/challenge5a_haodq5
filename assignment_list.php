<?php
session_start();
include('config.php');

// Chỉ cho sinh viên truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die("Access denied.");
}

$sql = "SELECT * FROM assignments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<html>
  <body>
    <h3>Assignment List</h3>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?php echo $row['title']; ?></strong><br>
          <?php echo $row['description']; ?><br>
          Deadline: <?php echo $row['deadline']; ?><br>
          <a href="<?php echo $row['file_path']; ?>" download>Download Assignment</a><br>
          <a href="submission_upload.php?assignment_id=<?php echo $row['id']; ?>">Submit Assignment</a>
        </li>
      <?php endwhile; ?>
    </ul>
  </body>
</html>
