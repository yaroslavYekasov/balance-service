<?php
include '../config/database.php';

$user_id = $_POST['user_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$user_id || !$amount) {
    die(json_encode(["error" => "User ID and amount are required"]));
}

$conn->begin_transaction();

try {
    $check_balance = $conn->prepare("SELECT balance FROM balances WHERE user_id = ?");
    $check_balance->bind_param("i", $user_id);
    $check_balance->execute();
    $result = $check_balance->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("User not found");
    }

    $balance = $result->fetch_assoc()['balance'];
    if ($balance < $amount) {
        throw new Exception("Insufficient funds");
    }

    $query = $conn->prepare("UPDATE balances SET balance = balance - ? WHERE user_id = ?");
    $query->bind_param("ii", $amount, $user_id);
    $query->execute();

    $log = $conn->prepare("INSERT INTO transactions (type, amount, from_user) VALUES ('subtract', ?, ?)");
    $log->bind_param("ii", $amount, $user_id);
    $log->execute();

    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
