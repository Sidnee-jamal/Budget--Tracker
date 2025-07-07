<?php

require '../Backend/db.php';

if (!isset($_GET['updateuser'])) {
    die('No user specified.');
}

$username = $_GET['updateuser'];

// Fetch current user data
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Optionally, add validation here

    $update_stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
    if ($update_stmt->execute([$new_username, $new_email, $user['id']])) {
        header('Location: adminpanel.php');
        exit;
    } else {
        $error = "Update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="main-content">
    <div class="card">
        <h2>Update User</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="submit-btn">Update</button>
            <button><a href="adminpanel.php"  >Cancel</a></button>
        </form>
    </div>
</div>
</body>
</html>