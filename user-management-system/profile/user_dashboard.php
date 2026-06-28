```php
<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2)
{
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT users.*, roles.role_name
     FROM users
     JOIN roles ON users.role_id = roles.id
     WHERE users.id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/style.css">
      <script src="../assets/js/script.js"></script>

<style>

body{
    background:#f4f6f9;
}

.profile-card{
    max-width:500px;
    margin:auto;
    margin-top:60px;
    border:none;
    border-radius:20px;
}

.profile-image{
    width:150px;
    height:150px;
    object-fit:cover;
    border-radius:50%;
    border:5px solid #0d6efd;
}

.card{
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

</style>

</head>

<body>
    <?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="card profile-card">

        <div class="card-body text-center">

            <?php if(!empty($user['profile_image'])) { ?>

                <img
                    src="../assets/uploads/<?php echo $user['profile_image']; ?>"
                    class="profile-image mb-3">

            <?php } else { ?>

                <img
                    src="https://via.placeholder.com/150"
                    class="profile-image mb-3">

            <?php } ?>

            <h2>
                <?php echo htmlspecialchars($user['name']); ?>
            </h2>

            <p class="text-muted">
                <?php echo htmlspecialchars($user['email']); ?>
            </p>

            <span class="badge bg-primary fs-6">
                <?php echo htmlspecialchars($user['role_name']); ?>
            </span>

            <hr>

            <div class="row text-start">

                <div class="col-4">
                    <strong>User ID:</strong>
                </div>

                <div class="col-8">
                    <?php echo $user['id']; ?>
                </div>

                <div class="col-4 mt-2">
                    <strong>Name:</strong>
                </div>

                <div class="col-8 mt-2">
                    <?php echo htmlspecialchars($user['name']); ?>
                </div>

                <div class="col-4 mt-2">
                    <strong>Email:</strong>
                </div>

                <div class="col-8 mt-2">
                    <?php echo htmlspecialchars($user['email']); ?>
                </div>

                <div class="col-4 mt-2">
                    <strong>Role:</strong>
                </div>

                <div class="col-8 mt-2">
                    <?php echo htmlspecialchars($user['role_name']); ?>
                </div>

            </div>

            <div class="mt-4">
                <a href="edit_profile.php"
   class="btn btn-primary">
   Edit Profile
</a>
<a href="change_password.php"
   class="btn btn-warning">
   Change Password
</a>

                <a href="../auth/logout.php"
                   class="btn btn-danger">
                    Logout
                </a>

            </div>

        </div>

    </div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
```
