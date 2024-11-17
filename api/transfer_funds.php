<?php
include '../config/database.php';

$from_user = $_POST['from_user'] ?? null;
$to_user = $_POST['to_user'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$from_user || !$to_user || !$amount) {
    die(json_encode(["error" => "All fields are required"]));
}

$conn->begin_transaction();

try {
    $check_balance = $conn->prepare("SELECT balance FROM balances WHERE user_id = ?");
    $check_balance->bind_param("i", $from_user);
    $check_balance->execute();
    $result = $check_balance->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Sender not found");
    }

    $balance = $result->fetch_assoc()['balance'];
    if ($balance < $amount) {
        throw new Exception("Insufficient funds");
    }

    $deduct = $conn->prepare("UPDATE balances SET balance = balance - ? WHERE user_id = ?");
    $deduct->bind_param("ii", $amount, $from_user);
    $deduct->execute();

    $add = $conn->prepare("UPDATE balances SET balance = balance + ? WHERE user_id = ?");
    $add->bind_param("ii", $amount, $to_user);
    $add->execute();

    $log = $conn->prepare("INSERT INTO transactions (type, amount, from_user, to_user) VALUES ('transfer', ?, ?, ?)");
    $log->bind_param("iii", $amount, $from_user, $to_user);
    $log->execute();

    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
