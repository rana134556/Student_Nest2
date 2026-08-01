<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/property.php";
require "data/search.php";
require "data/filter.php";
require "data/locations.php";
require "data/category.php";
require "data/profile.php";
$user = getProfile($conn, $_SESSION['user_id']);
/*=========================
Database
=========================*/

$categories = getCategories($conn);

$keyword      = trim($_GET['search'] ?? "");
$governorate  = trim($_GET['governorate'] ?? "");
$city         = trim($_GET['city'] ?? "");
$type         = trim($_GET['type'] ?? "");
$price        = trim($_GET['price'] ?? "");
$categoryId   = isset($_GET['category']) ? (int)$_GET['category'] : 0; // استقبال رقم الفئة

if ($categoryId > 0) {

    $properties = filterProperties($conn, "", "", $categoryId, "");
} elseif (
    !empty($governorate) ||
    !empty($city) ||
    !empty($type) ||
    !empty($price)
) {

    $properties = filterProperties(
        $conn,
        $governorate,
        $city,
        $type,
        $price
    );

} else {

    $properties = searchProperties(
        $conn,
        $keyword
    );

}

?>

<section class="dashboard">

<div class="container">

<!-- Welcome -->

<div class="welcome-box">

<div>

<h2>
    Welcome <?= htmlspecialchars($user['name'] ?? "Student"); ?> 👋
</h2>

<p>

Find the perfect accommodation easily.

</p>

</div>

</div>

<!-- Search -->

<div class="search-box">

<form method="GET" class="search-form">

<input
type="text"
name="search"
placeholder="Search by property name..."
value="<?= htmlspecialchars($keyword) ?>">

<select name="governorate">

<option value="">

Choose Governorate

</option>

<?php foreach($governorates as $gov): ?>

<option
value="<?= $gov ?>"
<?= $governorate == $gov ? "selected" : "" ?>>

<?= $gov ?>

</option>

<?php endforeach; ?>

</select>

<input
type="text"
name="city"
placeholder="Enter City"
value="<?= htmlspecialchars($city) ?>">

<input
type="number"
name="price"
placeholder="Maximum Price"
value="<?= htmlspecialchars($price) ?>">

<select name="type">

<option value="">

Accommodation Type

</option>

<?php foreach($categories as $category): ?>

<option
value="<?= $category['id'] ?>"
<?= $type == $category['id'] ? "selected" : "" ?>>

<?= htmlspecialchars($category['name']) ?>

</option>

<?php endforeach; ?>

</select>

<button type="submit">

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>

</form>

</div>

<!-- Categories -->

<section class="categories-section">

<div class="container">

<div class="section-title">

<h2>

Browse Categories

</h2>

<p>

Choose the type of accommodation you need

</p>

</div>

<div class="categories">

<?php foreach($categories as $category): ?>

<a
href="dashboard.php?category=<?= $category['id'] ?>"
class="category-card">

<div class="category-icon">

<i class="<?= htmlspecialchars($category['icon']) ?>"></i>

</div>

<h3>

<?= htmlspecialchars($category['name']) ?>

</h3>

<span>

View Properties

</span>

</a>

<?php endforeach; ?>

</div>

</div>

</section>
<!-- Dynamic Title and Show All Button -->
<div class="section-title" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>
            <?php 
            if ($categoryId > 0) {
                $currentCategoryName = "Properties";
                foreach ($categories as $cat) {
                    if ($cat['id'] == $categoryId) {
                        $currentCategoryName = $cat['name'];
                        break;
                    }
                }
                echo htmlspecialchars($currentCategoryName);
            } else {
                echo "Latest Properties";
            }
            ?>
        </h2>
        <p>
            <?php if ($categoryId > 0): ?>
                Showing accommodations for <?= htmlspecialchars($currentCategoryName) ?>
            <?php else: ?>
                Find the latest available properties
            <?php endif; ?>
        </p>
    </div>

    <?php if ($categoryId > 0): ?>
        <a href="dashboard.php" class="details-btn" style="background: var(--primary); color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 20px;">
            <i class="fa-solid fa-rotate-left"></i> Show All Properties
        </a>
    <?php endif; ?>
</div>
</div>
<div class="properties-grid">

<?php if(!empty($properties)): ?>

<?php foreach($properties as $property): ?>

<div class="property-card">

<?php if(!empty($property['image'])): ?>
    <img src="../<?= htmlspecialchars($property['image']) ?>" alt="<?= htmlspecialchars($property['title']) ?>">

<?php endif; ?>

<div class="property-content">

<h3>

<?= htmlspecialchars($property['title']) ?>

</h3>

<p>

<i class="fa-solid fa-location-dot"></i>

<?php
if(isset($property['location'])){
    echo htmlspecialchars($property['location']);
}else{
    echo htmlspecialchars($property['governorate'].", ".$property['city']);
}
?>

</p>

<h4>

<?= number_format($property['price']) ?>

EGP

</h4>

<div class="card-buttons">

<a
href="property_details.php?id=<?= $property['id'] ?>"
class="details-btn">

Details

</a>

<a
href="add_favorite.php?id=<?= $property['id'] ?>"
class="favorite-btn">

<i class="fa-solid fa-heart"></i>

Favorite

</a>

</div>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="no-results">

<i class="fa-solid fa-house-circle-xmark"></i>

<h3>

No properties found matching your search.

</h3>

</div>

<?php endif; ?>

</div>

</div>

</section>

<?php

// require "../includes/footer.php";

?>