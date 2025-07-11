<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Account</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h2>Add New Account</h2>
  <form action="process_account.php" method="POST">
    <label for="name">Account Name:</label>
    <input type="text" name="name" required><br><br>

    <label for="balance">Initial Balance:</label>
    <input type="number" name="balance" step="0.01" required><br><br>

    <button type="submit">Create Account</button>
  </form>
</body>
</html>
