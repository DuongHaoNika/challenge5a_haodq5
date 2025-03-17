<?php
session_start();
include('config.php');

// Chỉ giáo viên mới có quyền upload bài tập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

if (isset($_POST['upload_assignment'])) {
    $teacher_id = $_SESSION['user']['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $deadline = $_POST['deadline'];
    
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
        $upload_dir = 'uploads/assignments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['assignment_file']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $target)) {
            $sql = "INSERT INTO assignments (teacher_id, title, description, file_path, deadline)
                    VALUES ($teacher_id, '$title', '$description', '$target', '$deadline')";
            $conn->query($sql);
            echo "Assignment uploaded.";
        } else {
            echo "File upload failed.";
        }
    }
}
?>
<html>
  <body>
    <h3>Upload Assignment</h3>
    <form method="post" enctype="multipart/form-data">
      Title: <input type="text" name="title"><br>
      Description: <textarea name="description"></textarea><br>
      Deadline: <input type="datetime-local" name="deadline"><br>
      Assignment File: <input type="file" name="assignment_file"><br>
      <input type="submit" name="upload_assignment" value="Upload">
    </form>
  </body>
</html>
