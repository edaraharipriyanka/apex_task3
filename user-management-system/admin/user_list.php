<?php
session_start();
include '../config/database.php';
/** @var mysqli $conn */

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1)
{
    header("Location: ../auth/login.php");
    exit();
}

$type = "all";

if(isset($_GET['type']))
{
    $type = $_GET['type'];
}

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$query = "
SELECT
users.id,
users.name,
users.email,
users.profile_image,
roles.role_name
FROM users
JOIN roles
ON users.role_id = roles.id
WHERE 1
";

if($type=="admin")
{
    $query .= " AND users.role_id=1 ";
}

elseif($type=="user")
{
    $query .= " AND users.role_id=2 ";
}

if(!empty($search))
{
    $search = mysqli_real_escape_string(
        $conn,
        $search
    );

    $query .= "
    AND
    (
    users.name LIKE '%$search%'
    OR
    users.email LIKE '%$search%'
    )
    ";
}

$query .= "
ORDER BY users.id DESC
";

$result = mysqli_query(
    $conn,
    $query
);

$title = "All Users";

if($type=="admin")
{
    $title = "Admin Users";
}

elseif($type=="user")
{
    $title = "Normal Users";
}
?>

<!DOCTYPE html>

<html>

<head>

<title><?php echo $title; ?></title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/style.css">

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<?php echo $title; ?>

</h2>

<a
href="admin_dashboard.php"
class="btn btn-secondary">

Dashboard

</a>

</div>

<form
method="GET"
class="mb-4">

<input
type="hidden"
name="type"
value="<?php echo $type; ?>">

<div class="input-group">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Name or Email"
value="<?php echo htmlspecialchars($search); ?>">

<button
class="btn btn-primary">

Search

</button>

<a
href="user_list.php?type=<?php echo $type; ?>"
class="btn btn-dark">

Reset

</a>

</div>

</form>

<table
class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Image</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>

<td>

<?php if(!empty($row['profile_image'])) { ?>

<img
src="../assets/uploads/<?php echo $row['profile_image']; ?>"
width="60"
height="60"
class="rounded-circle border"
style="object-fit:cover;">

<?php } else { ?>

<img
src="https://via.placeholder.com/60"
class="rounded-circle">

<?php } ?>

</td>

<td>

<?php echo htmlspecialchars($row['name']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['role_name']); ?>

</td>

<td>

<a
href="edit_user.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete_user.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this user?')">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php

if(mysqli_num_rows($result)==0)
{
?>

<tr>

<td
colspan="6"
class="text-center text-danger">

No Users Found

</td>

</tr>

<?php
}
?>

</tbody>

</table>

<div class="mt-4">

<a
href="manage_users.php"
class="btn btn-primary">

Manage Users

</a>

<a
href="admin_dashboard.php"
class="btn btn-secondary">

Back to Dashboard

</a>

</div>

</div>

<?php include '../includes/footer.php'; ?>

</body>

</html>