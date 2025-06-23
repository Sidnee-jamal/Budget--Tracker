<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Add new expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, type, description, date) 
                          VALUES (?, ?, 'expense', ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['amount'],
        $_POST['description'],
        $_POST['date']
    ]);
    header("Location: expenses.php");
    exit;
}

// Get all expenses
$expenses = $pdo->prepare("SELECT * FROM transactions 
                          WHERE user_id = ? AND type = 'expense' 
                          ORDER BY date DESC");
$expenses->execute([$_SESSION['user_id']]);
$expenseTransactions = $expenses->fetchAll();

// Calculate totals
$totalIncome = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM transactions 
                             WHERE user_id = ? AND type = 'income'");
$totalIncome->execute([$_SESSION['user_id']]);
$totalIncome = $totalIncome->fetchColumn();

$totalExpenses = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM transactions 
                               WHERE user_id = ? AND type = 'expense'");
$totalExpenses->execute([$_SESSION['user_id']]);
$totalExpenses = $totalExpenses->fetchColumn();

$remaining = $totalIncome - $totalExpenses;

$pageTitle = "Expenses";
require 'includes/header.php';
?>

<div class="container">
    <h1>Expenses</h1>
    <div class="summary-card">
        <h3>Total Income: <span class="amount-positive">$<?= number_format($totalIncome, 2) ?></span></h3>
        <h3>Total Expenses: <span class="amount-negative">$<?= number_format($totalExpenses, 2) ?></span></h3>
        <h3>Remaining: <span class="<?= $remaining < 0 ? 'amount-negative' : 'amount-positive' ?>">
            $<?= number_format($remaining, 2) ?>
        </span></h3>
    </div>

    <form method="POST" class="transaction-form">
        <h3>Add New Expense</h3>
        <div class="form-group">
            <input type="number" name="amount" step="0.01" placeholder="Amount" required>
        </div>
        <div class="form-group">
            <input type="text" name="description" placeholder="What was it for?">
        </div>
        <div class="form-group">
            <input type="date" name="date" required value="<?= date('Y-m-d') ?>">
        </div>
        <button type="submit" name="add_expense" class="btn">Add Expense</button>
    </form>

    <div class="transaction-list">
        <h3>Expense History</h3>
        <?php if (empty($expenseTransactions)): ?>
            <p>No expenses recorded yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Actions</th> <!-- New column for edit/delete -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expenseTransactions as $t): ?>
                    <tr>
                        <td><?= date('M j, Y', strtotime($t['date'])) ?></td>
                        <td><?= htmlspecialchars($t['description']) ?></td>
                        <td class="amount-negative">-$<?= number_format($t['amount'], 2) ?></td>
                        <td class="actions">
                            <!-- Edit Button -->
                            <button class="btn-edit" 
                                    data-id="<?= $t['id'] ?>"
                                    data-amount="<?= $t['amount'] ?>"
                                    data-description="<?= htmlspecialchars($t['description']) ?>"
                                    data-date="<?= $t['date'] ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- Delete Button -->
                            <button class="btn-delete" data-id="<?= $t['id'] ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Modal (same as income page) -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Expense</h2>
        <form id="editForm">
            <input type="hidden" id="editId" name="id">
            <div class="form-group">
                <label for="editAmount">Amount</label>
                <input type="number" id="editAmount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="editDescription">Description</label>
                <input type="text" id="editDescription" name="description">
            </div>
            <div class="form-group">
                <label for="editDate">Date</label>
                <input type="date" id="editDate" name="date" required>
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Include the JavaScript file (same as income page) -->
<script src="assets/js/transactions.js"></script>

<?php require 'includes/footer.php'; ?>