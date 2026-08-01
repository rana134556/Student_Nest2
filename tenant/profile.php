<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/profile.php";


/*==========================
Database
==========================*/

$user = getProfile(
    $conn,
    $_SESSION['user_id']
);

?>

<section class="profile-page">

<div class="container">

<div class="profile-card">

<div class="profile-image">
    <?php if(!empty($user['image'])): ?>
        <img src="../<?= htmlspecialchars($user['image']); ?>" alt="Profile">
    <?php else: ?>
        <img src="../asset/imges/2.jfif" alt="Default Profile">
    <?php endif; ?>
</div>
<div class="profile-info">

<h2>

<?= htmlspecialchars($user['name']); ?>

</h2>

<p>

📧

<?= htmlspecialchars($user['email']); ?>

</p>

<p>

📞

<?= htmlspecialchars($user['phone']); ?>

</p>

<a
href="edit_profile.php"
class="main-btn">

Edit Profile

</a>

</div>

</div>

</div>

</section>

