<?php
require '../Backend/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('adminlogin.html');
    exit;
}

if (!isset($_SESSION['user_type_id']) || $_SESSION['user_type_id'] != 2) {
  
    header('adminlogin.html');
  
}


$stmt = $pdo->query('SELECT * FROM logs ORDER BY timestamp DESC');
?>
<!DOCTYPE html>
<html>
<head>
  <title>System Logs</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="main-content">
  <div class="card">
    <h2>System Logs</h2>
    <table class="tableclass">
      <tr><th>Action</th><th>User</th><th>Timestamp</th></tr>
      <?php while ($row = $stmt->fetch()): ?>
        <tr>
          <td><?= htmlspecialchars($row['action']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['timestamp']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
    <button><a href="adminpanel.php" >Back to Admin Panel</a></button>
  </div>
</div>
</body>
</html>