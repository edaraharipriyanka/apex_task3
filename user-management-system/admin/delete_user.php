```php
<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */


if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1)
{
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']))
{
    header("Location: manage_users.php");
    exit();
}

$id = (int)$_GET['id'];

// Prevent admin from deleting themselves
if ($id == $_SESSION['user_id'])
{
    die("You cannot delete your own account.");
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM users WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt))
{
    header("Location: manage_users.php");
    exit();
}
else
{
    echo "Delete Failed!";
}
?>
```
