<?php

require '../Backend/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('adminlogin.html');
    exit;
}

if (!isset($_SESSION['user_type_id']) || $_SESSION['user_type_id'] != 2) {
  
    header('adminlogin.html');
  
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">
  <title>Admin Panel</title>
</head>
<body>

 <div class="main-content">
   <div class="card">
  <h2>Manage Users</h2>

<div class="form-link">
          <a href="adduser.html">Add user</a>
          <a href="adminlogs.php">View Logs</a>
          <a href="/Backend/admin_logout.php">Logout</a>
        </div>

      


  <table class="tableclass" style="width:100%; margin-top:20px;">

    <tr>
       <th >Username</th>
      <th >Email</th>
      <th >Password</th>
      <th>Action</th>

    </tr>


 
    <?php

   $sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

if ($stmt) {
    while ($row = $stmt->fetch()) {
        $username = htmlspecialchars($row['username']);
        $email = htmlspecialchars($row['email']);
        $password = htmlspecialchars($row['password']);
        echo '<tr>
            <td>'.$username.'</td>
            <td>'.$email.'</td>
            <td>'.$password.'</td>
            <td>
                <button class="btn btn-primary"><a href="admupdate.php?updateuser='.$username.'" class="text-light">Update</a></button>
                <button class="btn btn-danger"><a href="/Backend/adm_delete.php?deleteuser='.$username.'" class="text-light">Delete</a></button>
            </td>
        </tr>';
    }
}
?>
</table>
</div>
   </div>


</body>
</html>