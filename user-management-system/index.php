<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js"></script>

<style>

.hero{
    min-height:80vh;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="#">
    User Management System
</a>

<div>

<a href="auth/login.php"
   class="btn btn-outline-light me-2">
   Login
</a>

<a href="auth/register.php"
   class="btn btn-primary">
   Register
</a>

</div>

</div>

</nav>

<section class="hero">

<div class="container">

<h1 class="display-4 fw-bold">
    User Management System
</h1>

<p class="lead mt-3">
    Secure User Authentication, Role-Based Access,
    Profile Management and Admin Dashboard
</p>

<div class="mt-4">

<a href="auth/login.php"
   class="btn btn-primary btn-lg me-2">
   Login
</a>

<a href="auth/register.php"
   class="btn btn-success btn-lg">
   Register
</a>

</div>

</div>

</section>

<footer class="bg-dark text-white text-center p-3">

© 2026 User Management System |
Developed by Hari Priyanka

</footer>

</body>
</html>