
<?php

session_start();
$isgallery = true;
include("includes/header.php");
include("db.php");
$category = isset($_GET['category']) ? $_GET['category'] : "all";
$query = "SELECT housing.*, property_images.image FROM housing
INNER JOIN property_images ON housing.id = property_images.property_id
WHERE property_images.is_main = 1";

if($category != "all"){

    $query .= " AND housing.category_id = (SELECT id FROM categories WHERE name='$category')";
}
$result=mysqli_query($connect,$query);

?>

  <?php 
  include 'constants.php';
  include 'navbar.php'; ?>

<div class="address">
<h2 class="gallery">Gallery</h2>
<p>Explore photos of student housing,rooms,and,facilities.</p>

</div>

<br>
<br>
<form action="" method="GET">
<div class="show">
<button type="submit" name="category" value="all">ALL</button>
<button type="submit" name="category" value="Single Room"> Single Room</button>
<button type="submit" name="category" value="Double Room">Double Room</button>
<button type="submit" name="category" value="Empty Apartment">Unfurnished Apartment</button>

</div>
</form>
  <div class="sec2">
<div class="container">
    <div class="row">
<?php 
   while($values = mysqli_fetch_assoc($result)){?>
 <div class="col-12 col-md-4 mb-4 gallery">
    <div class="card" >
  <img src="<?php echo $values['image'];?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><?php echo $values['governorate'] ?> - <?php echo $values['city'] ?> </h5>
    <h4><?php  echo $values['price']?> EGP/Month</h4>
    <hr>
    <p class="card-text"><?php echo $values['description'] ?></p>
   
 </div>
</div>
</div> 

<?php }?>
  </div> 
</div>
</div>

<?php include("includes/footer.php");?>