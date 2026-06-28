<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="#">
    User Management System
</a>

<div>

<?php if(isset($_SESSION['user_id'])) { ?>

<a href="../auth/logout.php"
   class="btn btn-danger">
   Logout
</a>

<?php } ?>

</div>

</div>

</nav>