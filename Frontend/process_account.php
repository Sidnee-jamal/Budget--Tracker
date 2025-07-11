<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$username = $_SESSION['username'];
$userRes = $conn->query("SELECT id FROM users WHERE username = '$username'");
$user_id = $userRes->fetch_assoc()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $balance = floatval($_POST['balance']);

    $sql = "INSERT INTO accounts (user_id, name, balance) VALUES ($user_id, '$name', $balance)";
    if ($conn->query($sql)) {
        header("Location: Dashboard.php"); // or show confirmation
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
