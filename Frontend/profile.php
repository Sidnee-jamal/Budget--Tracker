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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="styles.css">

</head>
<body>
 <?php include 'header.php'; ?>
  <div class="profile-container">
    <!-- Profile image loaded from backend -->
    <img src="" alt="Profile Picture" class="profile-pic" id="profileImage">

    <div class="info-label">Name:</div>
    <div class="info-value" id="username"></div>

    <div class="info-label">Email:</div>
    <div class="info-value" id="email"></div>

    

    <button class="edit-btn" onclick="location.href='editprofile.html'">Edit Profile</button>
  </div>



</body>
</html>
