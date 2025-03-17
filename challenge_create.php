<?php
session_start();
include('config.php');

// Chỉ giáo viên mới được tạo challenge
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

if (isset($_POST['create_challenge'])) {
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
            // Lưu thông tin challenge mà không lưu đáp án (đáp án là tên file, không dấu)
            $sql = "INSERT INTO challenges (teacher_id, challenge_hint, file_path)
                    VALUES ($teacher_id, '$challenge_hint', '$target')";
            $conn->query($sql);
            echo "Challenge created.";
        } else {
            echo "File upload failed.";
        }
    }
}
?>
<html>
  <body>
    <h3>Create Challenge</h3>
    <form method="post" enctype="multipart/form-data">
      Challenge Hint: <textarea name="challenge_hint"></textarea><br>
      Challenge File (txt): <input type="file" name="challenge_file"><br>
      <input type="submit" name="create_challenge" value="Create Challenge">
    </form>
  </body>
</html>
