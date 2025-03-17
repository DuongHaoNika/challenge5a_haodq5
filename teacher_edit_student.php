<?php
session_start();
include('config.php');

// Kiểm tra quyền: chỉ giáo viên mới được truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    die("Access denied.");
}

// Xử lý xóa sinh viên
if (isset($_POST['delete'])) {
    $student_id = intval($_POST['student_id']);
    $sql = "DELETE FROM users WHERE id = $student_id AND role='student'";
    $conn->query($sql);
    echo "Student deleted.";
    exit;
}

// Xử lý cập nhật thông tin sinh viên (chỉ cho phép sửa email, số điện thoại)
if (isset($_POST['update'])) {
    $student_id = intval($_POST['student_id']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $sql = "UPDATE users SET email='$email', phone='$phone' WHERE id = $student_id AND role='student'";
    $conn->query($sql);
    echo "Student info updated.";
    exit;
}
?>
<html>
  <body>
    <!-- Form cập nhật thông tin sinh viên -->
    <form method="post">
      <input type="hidden" name="student_id" value="STUDENT_ID_HERE">
      Email: <input type="text" name="email" value="example@example.com"><br>
      Phone: <input type="text" name="phone" value="0123456789"><br>
      <input type="submit" name="update" value="Update Student Info">
    </form>
    <!-- Form xóa sinh viên -->
    <form method="post">
      <input type="hidden" name="student_id" value="STUDENT_ID_HERE">
      <input type="submit" name="delete" value="Delete Student">
    </form>
  </body>
</html>
