<?php
include '../config/database.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User ID is required"]);
    exit;
}

$query = $conn->prepare("SELECT balance FROM balances WHERE user_id = ?");
$query->bind_param("i", $user_id);

if (!$query->execute()) {
    echo json_encode(["error" => "Database query failed"]);
    exit;
}

$result = $query->get_result();

if ($result->num_rows > 0) {
    $balance = $result->fetch_assoc();
    echo json_encode(["balance" => $balance['balance']]);
} else {
    echo json_encode(["error" => "User not found"]);
}

$conn->close();
?>
