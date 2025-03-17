<?php
$host = 'localhost';
$user = 'dqh';
$pass = '123456789';
$dbname = 'challenge5a';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
