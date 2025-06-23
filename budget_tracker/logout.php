<?php 
require 'includes/config.php';
require 'includes/auth_functions.php';

logoutUser();
header("Location: login.php");
exit;
?>