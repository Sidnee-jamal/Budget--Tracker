<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Fetch user ID
$username = $_SESSION['username'];
$userQuery = $conn->query("SELECT id FROM users WHERE username='$username'");
$user_id = $userQuery->fetch_assoc()['id'];

// Fetch total balance
$totalQuery = $conn->query("SELECT SUM(balance) AS total_balance FROM accounts WHERE user_id = $user_id");
$total_balance = $totalQuery->fetch_assoc()['total_balance'] ?? 0;

// Fetch accounts
$accounts = $conn->query("SELECT name, balance FROM accounts WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BudgetWise Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="top-bar">
    <div class="logo">BudgetWise</div>
    <div class="nav-links">
      <span>Hi, <?php echo htmlspecialchars($username); ?>!</span>
      <a href="Dashboard.php" class="active"><span class="icon">📊</span>Dashboard</a>
      <a href="transactions.php">Transactions</a>
      <a href="addaccounts.php"><span class="icon">💰</span>Accounts</a>
      <a href="budgets.php"><span class="icon">📅</span>Budgets</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <h2>Total Balance</h2>
    <p>Ksh <?php echo number_format($total_balance, 2); ?></p>
  </div>

  <div class="container">
    <!-- 👇 Loop through user's accounts and display -->
    <?php while ($acc = $accounts->fetch_assoc()): ?>
      <div class="card">
        <h3><?php echo htmlspecialchars($acc['name']); ?></h3>
        <p>Ksh <?php echo number_format($acc['balance'], 2); ?></p>
      </div>
    <?php endwhile; ?>

    <a href="budgets.php">
      <div class="card">
        <h3>Budgets</h3>
        <p>0 budgets</p>
      </div>
    </a>

    <a href="goals.php">
      <div class="card">
        <h3>Goals</h3>
        <p>0 active, 0 completed</p>
      </div>
    </a>
  </div>

  <a href="addtransactions.php">
    <div class="add-transaction">
      <button><span class="icon">➕</span>Add Transaction</button>
    </div>
  </a>
</body>
</html>
