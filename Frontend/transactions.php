<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

$username = $_SESSION['username'];
$user_id = $conn->query("SELECT id FROM users WHERE username='$username'")->fetch_assoc()['id'];

// Fetch transactions and account names
$sql = "
  SELECT t.title, t.amount, t.category, t.date, a.name AS account_name
  FROM transactions t
  JOIN accounts a ON t.account_id = a.id
  WHERE t.user_id = $user_id
  ORDER BY t.date DESC, t.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Transactions</title>
  <style>
    body { font-family: Arial, sans-serif;padding: 20px; }
    h2 { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
    th { background: #1e4421; color: white; }
    tr:nth-child(even)
    .amount-positive { color: green; }
    .amount-negative { color: red; }
  </style>
</head>
<body>

<h2>Your Transactions</h2>

<table>
  <thead>
    <tr>
      <th>Title</th>
      <th>Amount</th>
      <th>Account</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td class="<?= $row['amount'] < 0 ? 'amount-negative' : 'amount-positive' ?>">
        KSh <?= number_format($row['amount'], 2) ?>
      </td>
      <td><?= htmlspecialchars($row['account_name']) ?></td>
      <td><?= htmlspecialchars($row['date']) ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
