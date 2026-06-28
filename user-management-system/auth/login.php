<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, password, role_id
         FROM users
         WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result))
    {
        if (password_verify($password, $user['password']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role_id'] = $user['role_id'];

            if ($user['role_id'] == 1)
            {
                header("Location: ../admin/admin_dashboard.php");
            }
            else
            {
                header("Location: ../profile/user_dashboard.php");
            }

            exit();
        }
        else
        {
            $message = "Invalid Password!";
        }
    }
    else
    {
        $message = "Email Not Found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    background:#f4f6f9;
}

.login-card{
    max-width:450px;
    margin:auto;
    margin-top:80px;
}

</style>
<script src="../assets/js/script.js"></script>

</head>

<body>

<div class="container">

<div class="card shadow login-card">

<div class="card-body p-4">

<h2 class="text-center mb-4">
    Login
</h2>

<?php if(!empty($message)) { ?>

<div class="alert alert-danger">
    <?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">
Email
</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">
Password
</label>

<div class="input-group">

<input
type="password"
id="password"
name="password"
class="form-control"
required>

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword()">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<button
type="submit"
class="btn btn-primary w-100">

Login

</button>

</form>

<hr>

<p class="text-center mb-0">

Don't have an account?

<a href="register.php">
Register Here
</a>

</p>

</div>

</div>

</div>

<script>
function togglePassword()
{
    let password = document.getElementById("password");
    let icon = document.querySelector(".bi");

    if(password.type === "password")
    {
        password.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
    else
    {
        password.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>

</body>
</html>