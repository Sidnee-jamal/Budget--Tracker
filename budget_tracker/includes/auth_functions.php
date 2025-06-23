<?php
function registerUser($username, $email, $password) {
    global $pdo;
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    if (strlen($password) < 8) {
        return "Password must be at least 8 characters";
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        return "Username or email already exists";
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $passwordHash]);

    return true;
}

function loginUser($username, $password) {
    global $pdo;
    
    // Find user (only select necessary columns)
    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        return "Invalid username or password";
    }

    // Verify password
    if (password_verify($password, $user['password_hash'])) {
        // Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];  // Store the role
        
        // Return both success and role
        return [
            'success' => true,
            'role' => $user['role']
        ];
    } else {
        return "Invalid username or password";
    }
}


function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// // Use in admin pages:
// if (!isAdmin()) {
//     header("HTTP/1.1 403 Forbidden");
//     die("Admin access required");
// }

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logoutUser() {
    session_unset();
    session_destroy();
}
?>