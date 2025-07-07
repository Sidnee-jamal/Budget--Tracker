<?php
require_once 'db.php';

if (isset($_GET['deleteuser'])) {
    $username = $_GET['deleteuser'];

    // Use prepared statements to prevent SQL injection
    $stmt = $pdo->prepare('DELETE FROM users WHERE username = ?');
    if ($stmt->execute([$username])) {
        header('Location: /Frontend/adminpanel.php');
        $log_stmt = $pdo->prepare('INSERT INTO logs (action, username) VALUES (?, ?)');
        $log_stmt->execute(['Deleted user', $username]);
        exit;
    } else {
        echo "Failed to delete user.";
    }
} else {
    echo "No user specified.";
}
?>