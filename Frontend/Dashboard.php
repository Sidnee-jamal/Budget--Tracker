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
<!-- The rest of your HTML code follows here -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BudgetWise Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  
</head>
<body>
  

 <?php include 'header.php'; ?>

  <div class="card">
    <h2>Total Balance</h2>
    <p>Ksh 0.00</p>
  </div>

  <div class="container">

    <a href="budgets.html">
      <div class="card">
      <h3>Budgets</h3>
      <p>0 budgets</p>
    </div>
    </a>
 

    <div class="card">
      <h3>Loans</h3>
      <p>Ksh 0.00</p>
    </div>

    <a href="goals.html">
      <div class="card">
      <h3>Goals</h3>
      <p>0 active, 0 completed</p>
    </div>
    </a>
    

  </div>
<a href="addtransactions.html">
   <div class="add-transaction">
    <button><span class="icon">➕</span>Add Transaction</button>
  </div>
</a>
 

<script>
  // Fetch accounts from localStorage
  const accounts = JSON.parse(localStorage.getItem('accounts')) || [];

  // Sum up all account balances
  const total = accounts.reduce((sum, acc) => sum + Number(acc.amount || 0), 0);

  // Insert into the dashboard card
  document.querySelector(".card p").textContent = `Ksh ${total.toFixed(2)}`;
</script>

</body>
</html>
