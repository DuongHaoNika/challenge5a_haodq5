<?php
session_start();
include('config.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die("Access denied.");
}

$assignment_id = intval($_GET['assignment_id']);

if (isset($_POST['submit_assignment'])) {
    $student_id = $_SESSION['user']['id'];
    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
        $upload_dir = 'uploads/submissions/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['submission_file']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $target)) {
            $sql = "INSERT INTO submissions (assignment_id, student_id, file_path)
                    VALUES ($assignment_id, $student_id, '$target')";
            $conn->query($sql);
            echo "Submission uploaded.";
        } else {
            echo "File upload failed.";
        }
    }
}
?>
<html>
  <body>
    <h3>Submit Assignment</h3>
    <form method="post" enctype="multipart/form-data">
      Submission File: <input type="file" name="submission_file"><br>
      <input type="submit" name="submit_assignment" value="Submit">
    </form>
  </body>
</html>
