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

$userId = $_SESSION['user_id'];
$message = "";

// Get User Data
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Update Profile
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    $profileImage = $user['profile_image'];

    // Upload New Image
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

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET name = ?,
             email = ?,
             profile_image = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $name,
        $email,
        $profileImage,
        $userId
    );

    if (mysqli_stmt_execute($stmt))
    {
        $message = "Profile Updated Successfully!";

        $_SESSION['user_name'] = $name;

        // Reload User Data
        $stmt = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $userId
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    }
    else
    {
        $message = "Update Failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/style.css">
      <script src="../assets/js/script.js"></script>

</head>

<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

<div class="container py-5">

<div class="card shadow mx-auto"
     style="max-width:600px;">

<div class="card-body">

<h2 class="text-center mb-4">
    Edit Profile
</h2>

<?php if(!empty($message)) { ?>

<div class="alert alert-success">
    <?php echo $message; ?>
</div>

<?php } ?>

<div class="text-center mb-4">

<?php if(!empty($user['profile_image'])) { ?>

<img
src="../assets/uploads/<?php echo $user['profile_image']; ?>"
width="150"
height="150"
style="border-radius:50%; object-fit:cover;">

<?php } ?>

</div>

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
value="<?php echo htmlspecialchars($user['name']); ?>"
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
value="<?php echo htmlspecialchars($user['email']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
    Change Profile Image
</label>

<input
type="file"
name="profile_image"
class="form-control">

</div>

<button
type="submit"
class="btn btn-success">

Update Profile

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

</body>
</html>
```
