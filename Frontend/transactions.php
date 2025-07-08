<?php
require '../Backend/db.php'; // Adjust the path as necessary
session_start();
if (!isset($_SESSION['user_id'])) {
    header('login.html');
    exit;
}

if (!isset($_SESSION['user_type_id']) || $_SESSION['user_type_id'] == 2) {
  
    header('login.html');
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1.0, initial-scale=1.0">
    <title>All Transactions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?>
</body>
</html>