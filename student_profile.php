<?php
session_start();
include('config.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die("Access denied.");
}

$user_id = $_SESSION['user']['id'];

if (isset($_POST['update_profile'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Cập nhật email và số điện thoại
    $sql = "UPDATE users SET email='$email', phone='$phone' WHERE id=$user_id";
    $conn->query($sql);
    
    // Xử lý upload avatar từ file
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $upload_dir = 'uploads/avatars/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['avatar']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $sql = "UPDATE users SET avatar='$target' WHERE id=$user_id";
            $conn->query($sql);
        }
    } else if (!empty($_POST['avatar_url'])) {
        // Cập nhật avatar theo URL
        $avatar_url = $conn->real_escape_string($_POST['avatar_url']);
        $sql = "UPDATE users SET avatar='$avatar_url' WHERE id=$user_id";
        $conn->query($sql);
    }
    echo "Profile updated.";
}
?>
<html>
  <body>
    <form method="post" enctype="multipart/form-data">
      Email: <input type="text" name="email" value="<?php echo $_SESSION['user']['email']; ?>"><br>
      Phone: <input type="text" name="phone" value="<?php echo $_SESSION['user']['phone']; ?>"><br>
      Avatar Upload File: <input type="file" name="avatar"><br>
      Avatar URL: <input type="text" name="avatar_url"><br>
      <input type="submit" name="update_profile" value="Update Profile">
    </form>
  </body>
</html>
