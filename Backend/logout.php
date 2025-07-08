<?php
session_start();

// Optionally log the logout action if username is set
if (isset($_SESSION['username'])) {
    require_once 'db.php';
    $log_stmt = $pdo->prepare('INSERT INTO logs (action, username) VALUES (?, ?)');
    $log_stmt->execute(['User logged out', $_SESSION['username']]);
}

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

 $log_stmt = $pdo->prepare('INSERT INTO logs (action, username) VALUES (?, ?)');
 $log_stmt->execute(['User logged out', $username]);
// Redirect to login page
header('Location: ../Frontend/login.html');
exit;