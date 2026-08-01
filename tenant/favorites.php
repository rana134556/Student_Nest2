<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/favorite.php";

/*=========================
Database
=========================*/

$favorites = getFavorites(
    $conn,
    $_SESSION['user_id']
);

?>

<section class="favorites-page">

<div class="container">

<div class="section-title">

<h2>

Favorite Properties

</h2>

<p>

All properties you have saved.

</p>

</div>

<div class="properties-grid">

<?php if(!empty($favorites)): ?>

<?php foreach($favorites as $property): ?>

<div class="property-card">

<img
src="../<?= htmlspecialchars($property['image']) ?>"
alt="<?= htmlspecialchars($property['title']) ?>">

<div class="property-content">

<h3>

<?= htmlspecialchars($property['title']) ?>

</h3>

<p>

<i class="fa-solid fa-location-dot"></i>

<?= htmlspecialchars($property['location']) ?>

</p>

<h4>

<?= number_format($property['price']) ?>

EGP

</h4>

<div class="card-buttons">

<a
href="property_details.php?id=<?= $property['property_id'] ?>"
class="details-btn">

<i class="fa-solid fa-eye"></i>

View Details

</a>

<a
href="remove_favorite.php?id=<?= $property['property_id'] ?>"
class="remove-btn"
onclick="return confirm('Are you sure you want to remove this property from favorites?')">

<i class="fa-solid fa-trash"></i>

Remove

</a>

</div>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="no-results">

<h3>No favorite properties found.</h3>

</div>

<?php endif; ?>

</div>

</div>

</section>

