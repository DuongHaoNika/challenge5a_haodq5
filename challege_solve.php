<?php
session_start();
include('config.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die("Access denied.");
}

$challenge_id = intval($_GET['challenge_id']);

// Lấy thông tin challenge
$sql = "SELECT * FROM challenges WHERE id = $challenge_id";
$result = $conn->query($sql);
$challenge = $result->fetch_assoc();

if (!$challenge) {
    die("Challenge not found.");
}

// Đáp án: tên file (không bao gồm đường dẫn, có thể bỏ phần mở rộng nếu cần)
$correct_answer = pathinfo($challenge['file_path'], PATHINFO_FILENAME);

if (isset($_POST['submit_answer'])) {
    $submitted_answer = trim($_POST['answer']);
    if ($submitted_answer == $correct_answer) {
         // Nếu đúng, đọc và hiển thị nội dung file txt chứa bài thơ, văn
         $file_content = file_get_contents($challenge['file_path']);
         echo "Correct! Challenge content: <br><pre>" . htmlspecialchars($file_content) . "</pre>";
    } else {
         echo "Incorrect answer.";
    }
}
?>
<html>
  <body>
    <h3>Challenge</h3>
    <p><?php echo $challenge['challenge_hint']; ?></p>
    <form method="post">
      Your Answer: <input type="text" name="answer"><br>
      <input type="submit" name="submit_answer" value="Submit Answer">
    </form>
  </body>
</html>
