<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

$username = $_SESSION['username'];
$user_result = $conn->query("SELECT id FROM users WHERE username='$username'");
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];

// Fetch existing budgets
$budgets = $conn->query("SELECT category, amount, month, year FROM budgets WHERE user_id = $user_id ORDER BY year DESC, FIELD(month, 'January','February','March','April','May','June','July','August','September','October','November','December')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Set Budget - BudgetWise</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

  <form class="registration-form" id="set-budget-form" action="save_budget.php" method="post">
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
      </select>
    </div>

    <button type="submit">Set Budget</button>

    <div class="login-link">
      <a href="Dashboard.php">Back to Dashboard</a>
    </div>
  </form>

  <hr>

  <section class="budget-list">
    <h2>Your Budgets</h2>
    <?php if ($budgets->num_rows > 0): ?>
      <ul>
        <?php while ($row = $budgets->fetch_assoc()): ?>
          <li>
            <strong><?php echo htmlspecialchars($row['category']); ?></strong> — 
            Ksh <?php echo number_format($row['amount'], 2); ?> 
            for <?php echo htmlspecialchars($row['month']) . ' ' . htmlspecialchars($row['year']); ?>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No budgets set yet.</p>
    <?php endif; ?>
  </section>

  <script>
    // Auto-populate year dropdown
    const yearSelect = document.getElementById('year');
    const currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= currentYear - 5; i--) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = i;
      yearSelect.appendChild(option);
    }
  </script>

</body>
</html>
