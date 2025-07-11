<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$user_id = $conn->query("SELECT id FROM users WHERE username='$username'")->fetch_assoc()['id'];

$title = $_POST['title'];
$amount = floatval($_POST['amount']);
$type = $_POST['type'];
$account_id = intval($_POST['account_id']);
$date = $_POST['date'];

if ($type === 'expense') {
    $amount = -abs($amount); // Make sure it's negative
}

$conn->begin_transaction();

try {
    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, account_id, title, amount, category, date) VALUES (?, ?, ?, ?, ?, ?)");
    $category = ucfirst($type);
    $stmt->bind_param("iisdss", $user_id, $account_id, $title, $amount, $category, $date);
    $stmt->execute();

    // Update the linked account balance
    $conn->query("UPDATE accounts SET balance = balance + $amount WHERE id = $account_id");

    $conn->commit();
    header("Location: Dashboard.php");
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
