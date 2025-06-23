<?php
require 'includes/config.php';
require 'includes/auth_functions.php';
if (!isAdmin()) header("Location: login.php");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_user'])) {
        // Create new user
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password_hash'], PASSWORD_DEFAULT);
  
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            $success = "User created successfully!";
        } catch (PDOException $e) {
            $error = "Error creating user: " . $e->getMessage();
        }
    } elseif (isset($_POST['update_user'])) {
        // Update existing user
        $id = $_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
   
        
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $id]);
            $success = "User updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating user: " . $e->getMessage();
        }
    } elseif (isset($_GET['delete'])) {
        // Delete user
        $id = $_GET['delete'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $success = "User deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting user: " . $e->getMessage();
        }
    }
}

// Get all users
$users = $pdo->query("SELECT * FROM users WHERE role = 'user'")->fetchAll();

$pageTitle = "User Management";
require 'includes/adminheader.php';
?>

<div class="admin-users-container">
    <h1 class="admin-title">User Management</h1>
    
    <?php if (isset($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Create User Form -->
    <div class="admin-card">
        <h2>Create New User</h2>
        <form method="POST" class="user-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password_hash" required>
                </div>
               
            </div>
            <button type="submit" name="create_user" class="btn">Create User</button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="admin-card">
        <h2>Existing Users</h2>
        <div class="table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="role-badge <?= $user['role']?>">
                                <?= $user['role'] ?>
                            </span>
                        </td>
                        <td class="actions">
                            <button class="btn-edit" 
                                    data-id="<?= $user['id'] ?>"
                                    data-username="<?= htmlspecialchars($user['username']) ?>"
                                    data-email="<?= htmlspecialchars($user['email']) ?>"
                                    data-is_admin="<?= $user['role'] ?>">
                                 <i class="fas fa-edit"></i> Edit
                            </button>
                           
                             <button class="btn-delete" data-id="<?= $user['id'] ?>" >
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
         <span class="close">&times;</span>
        <h2>Edit User</h2>
        <form method="POST" id="editForm" class="user-form">
            <input type="hidden" name="id" id="editId">
            <div class="form-group">
                <label for="editUsername">Username</label>
                <input type="text" name="username" id="editUsername" required>
            </div>
            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" name="email" id="editEmail" required>
            </div>

 
            <div class="form-group">
                
                <button type="submit" name="update_user" class="btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Include JavaScript -->
<script src="assets/js/admin_users.js"></script>

<?php require 'includes/footer.php'; ?>