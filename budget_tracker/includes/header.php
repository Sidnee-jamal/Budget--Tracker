<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Budget Tracker' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="branding">
                <a href="dashboard.php" class="logo">Budget Tracker</a>
                <?php if ($isLoggedIn): ?>
                <span class="welcome-msg">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <?php endif; ?>
            </div>
            
            <?php if ($isLoggedIn): ?>
            <nav class="main-nav">
                <ul>
                    <li <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : '' ?>>
                        <a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a>
                    </li>
                    <li <?= basename($_SERVER['PHP_SELF']) == 'income.php' ? 'class="active"' : '' ?>>
                        <a href="income.php"><i class="icon-income"></i> Income</a>
                    </li>
                    <li <?= basename($_SERVER['PHP_SELF']) == 'expenses.php' ? 'class="active"' : '' ?>>
                        <a href="expenses.php"><i class="icon-expense"></i> Expenses</a>
                    </li>
                    <li class="logout">
                        <a href="logout.php"><i class="icon-logout"></i> Logout</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-content">