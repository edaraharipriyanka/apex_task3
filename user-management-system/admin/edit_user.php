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
$message = "";

// Update User
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role_id = (int)$_POST['role_id'];
    $newPassword = trim($_POST['password']);

    // Existing Image
    $profileImage = $_POST['old_image'];

    // Upload New Image
    if (
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['error'] == 0
    )
    {
        $fileName =
            time() . "_" .
            basename($_FILES['profile_image']['name']);

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            "../assets/uploads/" . $fileName
        );

        $profileImage = $fileName;
    }

    // Update With Password
    if(!empty($newPassword))
    {
        $hashedPassword =
            password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users
            SET
                name=?,
                email=?,
                role_id=?,
                password=?,
                profile_image=?
            WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssissi",
            $name,
            $email,
            $role_id,
            $hashedPassword,
            $profileImage,
            $id
        );
    }
    else
    {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users
            SET
                name=?,
                email=?,
                role_id=?,
                profile_image=?
            WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssisi",
            $name,
            $email,
            $role_id,
            $profileImage,
            $id
        );
    }

    if(mysqli_stmt_execute($stmt))
    {
        $message = "User Updated Successfully!";
    }
    else
    {
        $message = "Update Failed!";
    }
}

// Fetch User
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if(!$user)
{
    die("User Not Found");
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/style.css">

<script src="../assets/js/script.js"></script>

</head>

<body>

<div class="container py-5">

<div class="card shadow mx-auto"
style="max-width:700px;">

<div class="card-body">

<h2 class="text-center mb-4">
Edit User
</h2>

<?php if($message){ ?>

<div class="alert alert-success">
<?php echo $message; ?>
</div>

<?php } ?>

<form
method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?php echo $user['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo $user['email']; ?>"
required>

</div>

<div class="mb-3">

<label>Current Profile Image</label>

<br> 

<?php
if(!empty($user['profile_image']))
{
?>

<img
src="../assets/uploads/<?php echo $user['profile_image']; ?>"
width="120"
height="120"
class="rounded border mb-3">

<?php
}
else
{
?>

<p class="text-muted">
No Profile Image
</p>

<?php
}
?>

<input
type="file"
name="profile_image"
class="form-control">

<input
type="hidden"
name="old_image"
value="<?php echo $user['profile_image']; ?>">

</div>

<div class="mb-3">

<label>Role</label>

<select
name="role_id"
class="form-select">

<option
value="1"
<?php if($user['role_id']==1) echo "selected"; ?>>

Admin

</option>

<option
value="2"
<?php if($user['role_id']==2) echo "selected"; ?>>

User

</option>

</select>

</div>

<div class="mb-3">

<label>New Password</label>

<div class="input-group">

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Leave blank to keep current password">

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword()">

👁

</button>

</div>

<small class="text-muted">

Leave this field blank if you don't want to change the password.

</small>

</div>

<div class="d-grid gap-2">

<button
type="submit"
class="btn btn-success">

Update User

</button>

<a
href="manage_users.php"
class="btn btn-secondary">

Back

</a>

</div>

</form>

</div>

</div>

</div>

<script>

function togglePassword()
{
    var password =
        document.getElementById("password");

    if(password.type==="password")
    {
        password.type="text";
    }
    else
    {
        password.type="password";
    }
}

</script>

</body>

</html>