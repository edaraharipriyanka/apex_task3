<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1)
{
    header("Location: ../auth/login.php");
    exit();
}

// Statistics
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

$totalAdmins = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id=1")
)['total'];

$totalNormalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id=2")
)['total'];
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/style.css">

<script
src="../assets/js/script.js">
</script>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Admin Dashboard
        </h2>

        <a
        href="../auth/logout.php"
        class="btn btn-danger">

        Logout

        </a>

    </div>

    <h4 class="mb-4">

    Welcome,
    <?php echo $_SESSION['user_name']; ?>

    </h4>

    <div class="row g-4">

    <!-- Total Users -->

    <div class="col-md-4">

    <a
    href="user_list.php?type=all"
    class="text-decoration-none text-dark">

        <div class="card shadow text-center h-100">

        <div class="card-body">

        <h5 class="text-primary">
        Total Users
        </h5>

        <h1>
        <?php echo $totalUsers; ?>
        </h1>

            <p class="text-muted">

            Click to View All Users

            </p>

    </div>

</div>

</a>

</div>

<!-- Total Admins -->

<div class="col-md-4">

   <a
    href="user_list.php?type=admin"
    class="text-decoration-none text-dark">

    <div class="card shadow text-center h-100">

        <div class="card-body">

            <h5 class="text-success">
             Total Admins
            </h5>

        <h1>
            <?php echo $totalAdmins; ?>
        </h1>

        <p class="text-muted">

          Click to View Admin List

        </p>

        </div>

    </div>

    </a>

</div>

<!-- Total Users Role -->

<div class="col-md-4">

<a
href="user_list.php?type=user"
class="text-decoration-none text-dark">

<div class="card shadow text-center h-100">

<div class="card-body">

<h5 class="text-warning">
Normal Users
</h5>

<h1>
<?php echo $totalNormalUsers; ?>
</h1>

<p class="text-muted">

Click to View Users

</p>

</div>

</div>

</a>

</div>

</div>

<div class="mt-5">

<a
href="manage_users.php"
class="btn btn-primary">

Manage Users

</a>

</div>

</div>

<?php include '../includes/footer.php'; ?>

</body>

</html>