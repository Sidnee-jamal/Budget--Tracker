<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<div class="top-bar">
  <div class="logo">BudgetWise</div>
  <div class="nav-links">
    <?php if (isset($_SESSION['username'])): ?>
      <span class="greeting" style="margin-right:100px;">Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
    <?php endif; ?>
    <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>"><span class="icon">📊</span>Dashboard</a>
    <a href="transactions.php" class="<?= $current_page == 'transactions.php' ? 'active' : '' ?>">Transactions</a>
    <a href="accounts.php" class="<?= $current_page == 'accounts.php' ? 'active' : '' ?>"><span class="icon">💰</span>Accounts</a>
    <a href="budgets.php" class="<?= $current_page == 'budgets.php' ? 'active' : '' ?>"><span class="icon">📅</span>Budgets</a>
    <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>"><span class="icon">👤</span>Profile</a>
    <a href="/Backend/logout.php">Log out</a>
  </div>
</div>