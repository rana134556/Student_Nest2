<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/property.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$images = getPropertyImages($conn, $id);
$facilities = getPropertyFacilities($conn, $id);
$reviews = getPropertyReviews($conn, $id);


/*==========================
Database
==========================*/

$property = getPropertyById(
    $conn,
    $id
);



if(!$property){

    echo "<h2>Property Not Found.</h2>";

    require "../includes/footer.php";

    exit;

}

?>

<section class="property-details">

<div class="container">

 <div class="property-gallery">
    <div class="main-image">
        <img
            id="mainImage"
            src="../<?= htmlspecialchars($images[0]['image']) ?>"
            alt="Property">
    </div>

    <div class="gallery-images">
        <?php foreach($images as $img): ?>
            <img
                src="../<?= htmlspecialchars($img['image']) ?>"
                onclick="changeImage(this)"
                alt="">
        <?php endforeach; ?>
    </div>
</div>
    <!-- Content -->
    <div class="property-content">

        <h2><?= htmlspecialchars($property['title']) ?></h2>

        <p class="location">
            <i class="fa-solid fa-location-dot"></i>
            <?= htmlspecialchars($property['location']) ?>
        </p>

        <div class="price">
            <?= number_format($property['price']) ?> EGP / Month
        </div>

        <div class="property-info">

            <div>
                <i class="fa-solid fa-house"></i>
                <?= htmlspecialchars($property['category']) ?>
            </div>

            <div>
                <i class="fa-solid fa-user"></i>
                <?= htmlspecialchars($property['gender']) ?>
            </div>

        </div>

        <div class="description">

            <h3>Description</h3>

            <p>

                <?= nl2br(htmlspecialchars($property['description'])) ?>

            </p>

        </div>

        <div class="facilities">

            <h3>Facilities</h3>

            <div class="facilities-list">

                <?php foreach($facilities as $facility): ?>

                    <span>

                        <i class="fa-solid fa-check"></i>

                        <?= htmlspecialchars($facility['name']) ?>

                    </span>

                <?php endforeach; ?>

            </div>

        </div>

        <div class="owner-card">

            <img src="../asset/images/avatar.png" alt="Owner">

            <div>

                <h4><?= htmlspecialchars($property['owner']) ?></h4>

                <small><?= htmlspecialchars($property['phone']) ?></small>

            </div>

        </div>

        <div class="booking-box">

    <h3>Interested?</h3>

    <p style="margin-bottom:20px;">
        Contact the owner or book this property directly.
    </p>

    <div class="card-buttons">

        <a href="start_chat.php?property=<?= $property['id'] ?>" class="details-btn">
            Contact
        </a>

        <a href="add_favorite.php?id=<?= $property['id'] ?>" class="second-btn">
            Favorite
        </a>

        <a href="book_property.php?id=<?= $property['id'] ?>" class="main-btn">
            Book Now
        </a>

    </div>

</div>

    </div>

</div>

</section>

<script>
function changeImage(img){

    document.getElementById("mainImage").src = img.src;

}
</script>
