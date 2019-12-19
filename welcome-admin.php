<?php

session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our Question Paper Generator!</h1>
    </div>
    <p>
    	<h3>What would you like to do?</h3>
    </p>
    <p>
        <a href="add-topic.php" class="btn btn-info btn-lg">Add a New Subject</a><br><br>
        <a href="subject-list.php" class="btn btn-success btn-lg">View Available Subject List</a><br><br>
        <a href="reset-password-admin.php" class="btn btn-warning btn-lg">Reset Your Password</a><br><br>
        <a href="logout.php" class="btn btn-danger btn-lg">Sign Out of Your Account</a>
    </p>
    <footer><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <p>Copyright</p>
        A Project by 17BCE001 & 17BCE028
      </footer>
</body>
</html>