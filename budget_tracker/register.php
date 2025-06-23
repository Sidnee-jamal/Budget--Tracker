<?php 
require 'includes/config.php';
require 'includes/auth_functions.php';

// Admin registration secret key (change this!)
define('ADMIN_REGISTER_KEY', 'admin123'); 

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $secret_key = trim($_POST['secret_key'] ?? '');

    if ($password !== $confirm_password) {
        $error = "Passwords don't match";
    } else {
        // ENUM-compatible role assignment
        $role = ($secret_key === ADMIN_REGISTER_KEY) ? 'admin' : 'user';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) 
                                 VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $username,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role // ENUM value will be validated by MySQL
            ]);
            
            header("Location: login.php?registered=1");
            exit;
            
        } catch (PDOException $e) {
            // Handle ENUM constraint violations
            if (strpos($e->getMessage(), 'role') !== false) {
                $error = "Invalid role assignment";
            } else if ($stmt->errorInfo()[1] == 1062) { // Duplicate entry
                $error = "Username or email already exists";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!-- Rest of your HTML form remains exactly the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Budget Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Create Account</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <!-- Admin registration field (hidden by default) -->
            <div class="form-group" id="adminKeyGroup" style="display:none;">
                <label for="secret_key">Admin Registration Key</label>
                <input type="password" id="secret_key" name="secret_key">
                <small class="hint">Only for authorized administrators</small>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
<p id="adminToggle" style="cursor:pointer;color:#3498db;margin-top:10px;">
    <?php if (isset($_POST['secret_key']) || (isset($_GET['admin']) && $_GET['GET']['admin'] == 'true')): ?>
        ← Register as normal user
    <?php else: ?>
        Need admin access? →
    <?php endif; ?>
</p>

<script>
document.getElementById('adminToggle').addEventListener('click', function() {
    const keyGroup = document.getElementById('adminKeyGroup');
    const isAdminMode = keyGroup.style.display === 'block';
    
    keyGroup.style.display = isAdminMode ? 'none' : 'block';
    this.innerHTML = isAdminMode ? 'Need admin access? →' : '← Register as normal user';
    
    // Update URL without reload
    const url = new URL(window.location.href);
    url.searchParams.set('admin', isAdminMode ? 'false' : 'true');
    window.history.pushState({}, '', url);
});
</script>
</body>
</html>