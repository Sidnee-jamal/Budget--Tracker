<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isAdmin()) header("Location: login.php");



// Filter by type (income/expense/all)
$typeFilter = isset($_GET['filter']) && in_array($_GET['filter'], ['income', 'expense']) 
    ? $_GET['filter'] 
    : null;

// Get ALL transactions with user info
$query = "SELECT t.*, u.username FROM transactions t 
          JOIN users u ON t.user_id = u.id" . 
          ($typeFilter ? " WHERE type = ?" : "") . 
          " ORDER BY date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($typeFilter ? [$typeFilter] : []);
$transactions = $stmt->fetchAll();

// Calculate totals
$totals = $pdo->query("SELECT 
                      COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END), 0) as income,
                      COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END), 0) as expense
                      FROM transactions")->fetch();

$pageTitle = "Admin - All Transactions";
require 'includes/adminheader.php';
?>

<div class="container">
    <h1>Transaction Manager</h1>

    <!-- Filter Toggle -->
    <div class="filter-tabs">
        <a href="?filter=" class="<?= !$typeFilter ? 'active' : '' ?>">All</a>
        <a href="?filter=income" class="<?= $typeFilter === 'income' ? 'active' : '' ?>">Income</a>
        <a href="?filter=expense" class="<?= $typeFilter === 'expense' ? 'active' : '' ?>">Expenses</a>
    </div>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-card">
            <span>Total Income</span>
            <h3 class="amount-positive">+$<?= number_format($totals['income'], 2) ?></h3>
        </div>
        <div class="stat-card">
            <span>Total Expenses</span>
            <h3 class="amount-negative">-$<?= number_format($totals['expense'], 2) ?></h3>
        </div>
        <div class="stat-card">
            <span>Net Balance</span>
            <h3 class="<?= ($totals['income'] - $totals['expense']) >= 0 ? 'amount-positive' : 'amount-negative' ?>">
                $<?= number_format($totals['income'] - $totals['expense'], 2) ?>
            </h3>
        </div>
    </div>

    <!-- Add Transaction Form -->

          
      

    <!-- Transactions Table -->
    <div class="transaction-table">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['username']) ?></td>
                    <td><?= date('M j, Y', strtotime($t['date'])) ?></td>
                    <td><?= htmlspecialchars($t['description']) ?></td>
                    <td>
                        <span class="type-badge <?= $t['type'] === 'income' ? 'income' : 'expense' ?>">
                            <?= ucfirst($t['type']) ?>
                        </span>
                    </td>
                    <td class="<?= $t['type'] === 'income' ? 'amount-positive' : 'amount-negative' ?>">
                        <?= $t['type'] === 'income' ? '+' : '-' ?>$<?= number_format($t['amount'], 2) ?>
                    </td>
                    <td class="actions">
                        <button class="btn-edit" 
                                data-id="<?= $t['id'] ?>"
                                data-user-id="<?= $t['user_id'] ?>"
                                data-type="<?= $t['type'] ?>"
                                data-amount="<?= $t['amount'] ?>"
                                data-description="<?= htmlspecialchars($t['description']) ?>"
                                data-date="<?= $t['date'] ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" data-id="<?= $t['id'] ?>">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Transaction</h2>
        <form method="POST" id="editForm" class="user-form">
            <input type="hidden" id="editId" name="id">
            <div class="form-row">
                <div class="form-group">
                    <label>User</label>
                    <select id="editUserId" name="user_id" required>
                        <?php
                        $users = $pdo->query("SELECT id, username FROM users WHERE role = 'user' ")->fetchAll();
                         foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select id="editType" name="type" required>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" id="editAmount" name="amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" id="editDate" name="date" required>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" id="editDescription" name="description">
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Include JavaScript -->
<script src="assets/js/admin_transactions.js"></script>

<?php require 'includes/footer.php'; ?>