<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isLoggedIn()) header("Location: login.php");

// Get totals
$totalIncome = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM transactions 
                             WHERE user_id = ? AND type = 'income'");
$totalIncome->execute([$_SESSION['user_id']]);
$totalIncome = $totalIncome->fetchColumn();

$totalExpenses = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM transactions 
                               WHERE user_id = ? AND type = 'expense'");
$totalExpenses->execute([$_SESSION['user_id']]);
$totalExpenses = $totalExpenses->fetchColumn();

$balance = $totalIncome - $totalExpenses;

$pageTitle = "Dashboard";
require 'includes/header.php';
?>

<div class="container">
    <h1>Dashboard</h1>
    
    <div class="summary-cards">
        <div class="card income">
            <h3>Total Income</h3>
            <div class="amount">$<?= number_format($totalIncome, 2) ?></div>
        </div>
        
        <div class="card expenses">
            <h3>Total Expenses</h3>
            <div class="amount">$<?= number_format($totalExpenses, 2) ?></div>
        </div>
        
        <div class="card balance <?= $balance < 0 ? 'negative' : '' ?>">
            <h3>Current Balance</h3>
            <div class="amount">$<?= number_format($balance, 2) ?></div>
        </div>
    </div>

    <div class="recent-activity">
        <h2>Recent Transactions</h2>
        <?php
        $recent = $pdo->prepare("SELECT * FROM transactions 
                                WHERE user_id = ? 
                                ORDER BY date DESC LIMIT 5");
        $recent->execute([$_SESSION['user_id']]);
        $recentTransactions = $recent->fetchAll();
        
        if (empty($recentTransactions)): ?>
            <p>No transactions yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTransactions as $t): ?>
                    <tr>
                        <td><?= date('M j', strtotime($t['date'])) ?></td>
                        <td><?= htmlspecialchars($t['description']) ?></td>
                        <td><?= ucfirst($t['type']) ?></td>
                        <td class="<?= $t['type'] === 'income' ? 'amount-positive' : 'amount-negative' ?>">
                            <?= $t['type'] === 'income' ? '+' : '-' ?>$<?= number_format($t['amount'], 2) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>