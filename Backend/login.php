<?php

session_start();
require 'db.php';

// Get POST data from a standard HTML form
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    // Redirect back with error (or handle as you wish)
    header('Location: ../Frontend/login.html?error=empty');
    exit;
}

// Prepare and execute query
$stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?  AND user_type_id = 2');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Log the login action
    $log_stmt = $pdo->prepare('INSERT INTO logs (action, username) VALUES (?, ?)');
    $log_stmt->execute(['User logged in', $username]);

    // Redirect to dashboard or admin panel
    header('Location: ../Frontend/Dashboard.php');
    exit;
} else {
    // Redirect back with error (or handle as you wish)
    header('Location: ../Frontend/login.html?error=invalid');
    exit;
}
?>