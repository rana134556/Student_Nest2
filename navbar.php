<?php include_once __DIR__ . "/constants.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="css/navbar.css"> -->
</head>
<body>
    

<nav class="nav_bar">

    <a href="<?=BASE_URL?>home.php" class="tenant-logo">
        <i class="fa-solid fa-house"></i>
        <span class="logo-dark">Student</span>
        <span class="logo-color">Nest</span>
    </a>

    
    <button class="menu-toggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <ul class="nav-links">

        <li><a href="<?=BASE_URL?>home.php">Home</a></li>
        <li><a href="<?=BASE_URL?>gallery.php">Gallery</a></li>
        <li><a href="<?=BASE_URL?>public/about.php">About Us</a></li>
        <li><a href="<?=BASE_URL?>contact_us.php">Contact Us</a></li>

        <?php
        if(isset($_SESSION['user_id'])){
            if($_SESSION['role']=="tenant"){ ?>
                <li><a href="<?=BASE_URL?>tenant/dashboard.php">Find Housing</a></li>
        <?php }else{ ?>
                <li><a href="<?=BASE_URL?>dashboard_2/dashboard.php">My Properties</a></li>
        <?php }} ?>

    </ul>

</nav>

<script>
const menu = document.querySelector(".menu-toggle");
const links = document.querySelector(".nav-links");

menu.addEventListener("click", function () {
    links.classList.toggle("active");
});
</script>
</body>
</html>
