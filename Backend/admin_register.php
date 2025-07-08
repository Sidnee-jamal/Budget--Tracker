<?php
require 'db.php'; // Assumes your PDO connection is in db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die('All fields are required.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address.');
    }
    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        die('Username or email already exists.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Get the user_type_id for 'user'
    $stmt = $pdo->prepare('SELECT id FROM user_types WHERE type_name = ?');
    $stmt->execute(['admin']);
    $user_type = $stmt->fetch();
    $user_type_id = $user_type['id']; // Assuming 'admin' is the type you want to assign

    // Insert new user
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, user_type_id) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$username, $email, $hashed_password, $user_type_id])) {
    header('Location: /Frontend/adminlogin.html');
    $log_stmt = $pdo->prepare('INSERT INTO logs (action, username) VALUES (?, ?)');
    $log_stmt->execute(['Registered new admin', $username]);
    exit;
} else {
    echo 'Registration failed. Please try again.';
    }
}

?>