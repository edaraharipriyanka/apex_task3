```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/database.php';
/** @var mysqli $conn */

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_id = (int)$_POST['role_id'];

    $profileImage = "";

    // Upload Image
    if (
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['error'] == 0
    )
    {
        $fileName =
            time() . "_" .
            basename($_FILES['profile_image']['name']);

        $targetPath =
            "../assets/uploads/" . $fileName;

        if (
            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'],
                $targetPath
            )
        )
        {
            $profileImage = $fileName;
        }
    }

    // Validation
    if (empty($name) || empty($email) || empty($password))
    {
        $message = "All fields are required!";
        $messageType = "danger";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $message = "Invalid email format!";
        $messageType = "danger";
    }
    elseif (strlen($password) < 6)
    {
        $message = "Password must be at least 6 characters!";
        $messageType = "danger";
    }
    else
    {
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "s",
            $email
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0)
        {
            $message = "Email already exists!";
            $messageType = "warning";
        }
        else
        {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (
                    name,
                    email,
                    password,
                    role_id,
                    profile_image
                )
                VALUES
                (
                    ?, ?, ?, ?, ?
                )"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sssis",
                $name,
                $email,
                $hashedPassword,
                $role_id,
                $profileImage
            );

            if (mysqli_stmt_execute($stmt))
            {
                $message = "Registration Successful!";
                $messageType = "success";
            }
            else
            {
                $message = "Registration Failed!";
                $messageType = "danger";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

      <link rel="stylesheet" href="../assets/css/style.css">
      <script src="../assets/js/script.js"></script>
<style>


body{
    background:#f4f6f9;
}

.register-card{
    max-width:550px;
    margin:auto;
    margin-top:50px;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow register-card">

<div class="card-body p-4">

<h2 class="text-center mb-4">
    User Registration
</h2>

<?php if(!empty($message)) { ?>

<div class="alert alert-<?php echo $messageType; ?>">
    <?php echo $message; ?>
</div>

<?php } ?>

<form method="POST"
      enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Name
</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

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

<div class="mb-3">

<label class="form-label">
Role
</label>

<select
name="role_id"
class="form-select"
required>

<option value="1">
Admin
</option>

<option value="2" selected>
User
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">
Profile Image
</label>

<input
type="file"
name="profile_image"
class="form-control">

</div>

<button
type="submit"
class="btn btn-success w-100">

Register

</button>

</form>

<hr>

<p class="text-center mb-0">

Already have an account?

<a href="login.php">
Login Here
</a>

</p>

</div>

</div>

</div>

<script>

function togglePassword()
{
    let password =
        document.getElementById("password");

    let icon =
        document.querySelector(".bi");

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
```
