<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isAdmin()) header("Location: login.php");

if (isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    echo "User deleted";
}
?>