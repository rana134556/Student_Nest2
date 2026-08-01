<?php session_start();
$ishome = true;
include("includes/header.php");
include("db.php");

$query = "SELECT housing.*, property_images.image FROM housing
INNER JOIN property_images ON housing.id = property_images.property_id
WHERE property_images.is_main = 1
ORDER BY housing.id DESC LIMIT 3";

$result=mysqli_query($connect,$query);

?>?>


    <!-- start header -->
    <header>
<?php 
include 'constants.php';
include 'navbar.php'; ?>
        <div class="home">
            <div >
                <img src="Images/photo_2026-07-16_00-58-32.jpg" alt="no photo">
            </div>
            <div class="title">
                <p class="p">StudentNest, A stay worthy of you.</p>
                <h1>Discover the Charm of <br><span>Student Living</span></h1>
                <p>We Curate the best studentaclodations across Egypt,<br>conseing dering with the student expente.</p>
                <div>
                    <!-- <button><a href="">Explore Housing</a></button> -->
                   <?php
    if (isset($_SESSION['user_id'])) {
        echo '<button class="nav_button"><a href="tenant/logout.php" class="nav-btn">Sign Out</a></button>';
    } else {
        echo '<button class="nav_button"><a href="SignIn.php" class="nav-btn">Sign In</a></button>';
    }
    ?>
                    <!-- <button class="signIn"><a href="SignIn.php">Sign In</a> </button> -->

                </div>
            </div>
        </div>
    </header>
    <!-- the end header -->

    <!-- start section1 -->
     <section class="sec1">
        <div>
            <i class="fa-solid fa-user-check user-check"></i>
            <h2>Verified Accommodations</h2>
            <p>Our expert team guarantees the quality and reliability of every accommodation unit offered.</p>
        </div>
        <div>
            <i class="fa-solid fa-lock"></i>
            <h2>Secure Booking</h2>
            <p>An integrated payment and booking system that guarantees your rights and the rights of the property owner with ease.</p>
        </div>
        <div>
            <i class="fa-solid fa-location-dot"></i>
            <h2>Prime Locations</h2>
            <p>From the heart of Cairo to the tranquility of Aswan,we offer you accommodation in the most beautiful location in Egypt.</p>
        </div>
     </section>
     <!-- end section1 -->

     <!-- start section2 -->
      <section class="sec2">
        <h2>"Selected Accommodations for you" </h2>
        <div class="ViewAll">
          <a href="gallery.php"><i class="fa-solid fa-arrow-down fa-rotate-270"></i>View all</a>
        </div>
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

      </section>
      <!-- end section2 -->

      <!-- start section3 -->
       <section class="sec3">
        <h2>WHAT OUR STUDENTS SAY.</h2>
        <div class="says">
            <div class="say">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <p>"Living in student housing is a great,social experience."</p>
                <img src="Images/Ellipse 5.png" alt="no photo"><p class="p">Khaled Mohamed,Giza</p>
            </div>
            <div class="say">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <p> "It is the perfect placeto focus on my studies."</p>
                <img src="Images/Ellipse 1131 (1).jpg" alt="no photo"><p class="p">Sara Ali, Alexandria</p>
            </div>
            <div class="say">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <p>"I Love the vibrant community and meeting new friends here." </p>
                <img src="Images/Ellipse 1131.jpg" alt="no photo"><p class="p">Mohamed Gamal, Cairo</p>
            </div>
        </div>

       </section>
       <!-- end section3 -->

       <!-- start footer -->

       <?php include("includes/footer.php");?>
    
        <!-- end footer -->
