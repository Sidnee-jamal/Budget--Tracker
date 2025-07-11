<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

$username = $_SESSION['username'];
$user_id = $conn->query("SELECT id FROM users WHERE username='$username'")->fetch_assoc()['id'];
$accounts = $conn->query("SELECT id, name FROM accounts WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Transaction</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Add Transaction</h2>

<form action="save_transaction.php" method="post" class="transaction-form">
  <div class="form-group">
    <input type="text" name="title" placeholder="Transaction name" required>
  </div>

  <div class="form-group">
    <input type="number" name="amount" placeholder="Amount" required>
  </div>

  <div class="form-group">
    <select name="type" required>
      <option value="">Select Type</option>
      <option value="expense">Expense</option>
      <option value="income">Income</option>
    </select>
  </div>

  <div class="form-group">
    <select name="account_id" required>
      <option value="">Select Account</option>
      <?php while ($acc = $accounts->fetch_assoc()) { ?>
        <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <input type="date" name="date" required>
  </div>

  <button type="submit" class="submit-btn">Add Transaction</button>
</form>

</body>
</html>
