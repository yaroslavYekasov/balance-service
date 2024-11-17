<?php
$host = 'localhost';
$db = 'balance_service';
$user = 'root';
$password = '54836';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
