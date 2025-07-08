<?php
require '../Backend/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Get current user data
$stmt = $pdo->prepare("SELECT id, username, email, profile_picture FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_picture'];
        
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif ($file['size'] > 2097152) { // 2MB max
            $error = "File too large. Maximum 2MB allowed.";
        } else {
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $uploadPath = '../uploads/profile_pictures/' . $filename;
            
            // Create directory if it doesn't exist
            if (!file_exists('../uploads/profile_pictures')) {
                mkdir('../uploads/profile_pictures', 0755, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Delete old picture if exists
                if (!empty($user['profile_picture'])) {
                    @unlink('../uploads/profile_pictures/' . $user['profile_picture']);
                }
                
                // Update database with new filename
                $updateStmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $updateStmt->execute([$filename, $_SESSION['user_id']]);
                $user['profile_picture'] = $filename;
                $success = "Profile picture updated successfully!";
            } else {
                $error = "Error uploading file.";
            }
        }
    }
    
    // Update username and email if changed
    if (!empty($_POST['username']) && !empty($_POST['email'])) {
        $updateStmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $updateStmt->execute([
            htmlspecialchars($_POST['username']),
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            $_SESSION['user_id']
        ]);
        $user['username'] = $_POST['username'];
        $user['email'] = $_POST['email'];
        $success = isset($success) ? $success . " Profile updated!" : "Profile updated!";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="text"],
    input[type="email"],
    input[type="file"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .btn {
      padding: 10px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #45a049;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
    .success {
      color: green;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  
  <div class="profile-container">
    <h1>Edit Profile</h1>
    
    <?php if (isset($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="profile_picture">Profile Picture:</label>
        <?php if (!empty($user['profile_picture'])): ?>
          <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
               alt="Profile Picture" class="profile-pic">
        <?php else: ?>
          <img src="images/default-profile.jpg" alt="Profile Picture" class="profile-pic">
        <?php endif; ?>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
      </div>
      
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" 
               value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" 
               value="<?php echo htmlspecialchars($user['email']); ?>" required>
      </div>
      
      <button type="submit" class="btn">Save Changes</button>
      <button type="button" class="btn" onclick="window.location.href='profile.php'">Cancel</button>
    </form>
  </div>
</body>
</html>