<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>
<body>

<h2>Welcome <?php echo $_SESSION['user_name']; ?></h2>

<p>Login Successful!</p>

<a href="logout.php">Logout</a>

</body>
</html>