<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isAdmin()) header("Location: login.php");

$pageTitle = "Admin Dashboard";
require 'includes/adminheader.php';
?>

<div class="container">
    <h1>Admin Dashboard</h1>
    
    <div class="summary-cards">
        <div class="card balance">
            <h3>Total Users</h3>
            <div class="amount">
            <p><?= $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?></p>
            </div>
        </div>
        <div class="card balance">
            <h3>Admins</h3>
            <div class="amount">
            <p><?= $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn() ?></p>
            </div>
        </div>
    </div>
    
    
</div>




<?php require 'includes/footer.php'; ?>