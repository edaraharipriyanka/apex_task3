```php
<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */

if (!isset($_SESSION['user_id']))
{
    header("Location: ../auth/login.php");
    exit();
}

$message = "";
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT password FROM users WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $userId
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!password_verify($currentPassword, $user['password']))
    {
        $message = "Current password is incorrect!";
    }
    elseif ($newPassword != $confirmPassword)
    {
        $message = "New passwords do not match!";
    }
    elseif (strlen($newPassword) < 6)
    {
        $message = "Password must be at least 6 characters!";
    }
    else
    {
        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $update = mysqli_prepare(
            $conn,
            "UPDATE users
             SET password = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "si",
            $hashedPassword,
            $userId
        );

        if (mysqli_stmt_execute($update))
        {
            $message = "Password changed successfully!";
        }
        else
        {
            $message = "Failed to update password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
          <link rel="stylesheet" href="../assets/css/style.css">
          <script src="../assets/js/script.js"></script>
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

<div class="card shadow mx-auto"
     style="max-width:600px;">

<div class="card-body">

<h2 class="mb-4 text-center">
    Change Password
</h2>

<?php if(!empty($message)) { ?>

<div class="alert alert-info">
    <?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<!-- Current Password -->

<div class="mb-3">

<label class="form-label">
Current Password
</label>

<div class="input-group">

<input
type="password"
id="currentPassword"
name="current_password"
class="form-control"
required>

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword('currentPassword', this)">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<!-- New Password -->

<div class="mb-3">

<label class="form-label">
New Password
</label>

<div class="input-group">

<input
type="password"
id="newPassword"
name="new_password"
class="form-control"
required>

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword('newPassword', this)">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<!-- Confirm Password -->

<div class="mb-3">

<label class="form-label">
Confirm Password
</label>

<div class="input-group">

<input
type="password"
id="confirmPassword"
name="confirm_password"
class="form-control"
required>

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword('confirmPassword', this)">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<button
type="submit"
class="btn btn-success">
Update Password
</button>

<a href="user_dashboard.php"
   class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
function togglePassword(inputId, button)
{
    let input = document.getElementById(inputId);
    let icon = button.querySelector("i");

    if (input.type === "password")
    {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
    else
    {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>

</body>
</html>
```
