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

// Search
$search = "";

if (isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

if (!empty($search))
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT users.id,
                users.name,
                users.email,
                users.profile_image,
                roles.role_name
         FROM users
         JOIN roles
         ON users.role_id = roles.id
         WHERE users.name LIKE ?
            OR users.email LIKE ?
         ORDER BY users.id DESC"
    );

    $searchTerm = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $searchTerm,
        $searchTerm
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
}
else
{
    $query = "
    SELECT users.id,
           users.name,
           users.email,
           users.profile_image,
           roles.role_name
    FROM users
    JOIN roles
    ON users.role_id = roles.id
    ORDER BY users.id DESC
    ";

    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Users</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js"></script>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>User Management</h2>

        <div>
            <a href="admin_dashboard.php"
               class="btn btn-primary">
               Dashboard
            </a>

            <a href="../auth/logout.php"
               class="btn btn-danger">
               Logout
            </a>
        </div>

    </div>

    <!-- Search -->

    <form method="GET" class="mb-4">

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by Name or Email"
                value="<?php echo htmlspecialchars($search); ?>">

            <button
                type="submit"
                class="btn btn-primary">
                Search
            </button>

            <a href="manage_users.php"
               class="btn btn-secondary">
               Reset
            </a>

        </div>

    </form>

    <!-- Table -->

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th width="180">Actions</th>
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
                            style="
                                border-radius:50%;
                                object-fit:cover;
                                border:2px solid #ddd;
                            ">

                    <?php } else { ?>

                        No Image

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

                    <a href="edit_user.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-warning btn-sm">
                       Edit
                    </a>

                    <a href="delete_user.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this user?')">
                       Delete
                    </a>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>
<?php include '../includes/footer.php'; ?>

</body>
</html>
```
