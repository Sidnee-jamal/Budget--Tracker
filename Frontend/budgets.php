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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Set Budget - Budget Tracker</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

 <?php include 'header.php'; ?>

  <form class="registration-form" id="set-budget-form">
    <h2>Set Budget</h2>

    <div class="input-box">
      <label for="category">Category</label>
      <input type="text" id="category" name="category" required />
    </div>

    <div class="input-box">
      <label for="amount">Amount</label>
      <input type="number" id="amount" name="amount" min="0" required />
    </div>

    <div class="input-box">
      <label for="month">Month</label>
      <select id="month" name="month" required>
        <option value="">Select Month</option>
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
      </select>
    </div>

    <div class="input-box">
      <label for="year">Year</label>
      <select id="year" name="year" required>
        <option value="">Select Year</option>
        <!-- Years will be populated by JS -->
      </select>
    </div>

    <button type="submit">Set Budget</button>

  </form>

 
</body>
</html>