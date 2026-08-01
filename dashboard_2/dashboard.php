<?php

     session_start();


     require_once "../db.php";


     $page = "dashboard";



     if (!isset($_SESSION['user_id'])) {

          header("Location: ../login.php");

          exit();
     }



     $userId = $_SESSION['user_id'];



     /* Fetch Owner Data */


     $userQuery = "

     SELECT *

     FROM users

     WHERE id = $userId

     ";


     $userResult = mysqli_query($connect, $userQuery);


     $owner = mysqli_fetch_assoc($userResult);



     $ownerName = $owner['name'] ?? "Owner";



     /* Fetch Recent Property */


     $propertyQuery = "

     SELECT 

     housing.*,

     categories.name AS category_name

     FROM housing

     LEFT JOIN categories

     ON housing.category_id = categories.id

     WHERE housing.user_id = $userId

     ORDER BY housing.id DESC

     LIMIT 1

     ";


     $propertyResult = mysqli_query($connect, $propertyQuery);


     $property = mysqli_fetch_assoc($propertyResult);



     /* Property Count */


     $propertyCountQuery = "

     SELECT COUNT(*) AS total

     FROM housing

     WHERE user_id = $userId

     ";


     $propertyCountResult = mysqli_query($connect, $propertyCountQuery);


     $propertyCount = mysqli_fetch_assoc($propertyCountResult)['total'];



     /* Booking Count */


     $bookingCountQuery = "

     SELECT COUNT(*) AS total

     FROM bookings

     JOIN housing

     ON bookings.property_id = housing.id

     WHERE housing.user_id = $userId

     ";


     $bookingCountResult = mysqli_query($connect, $bookingCountQuery);


     $bookingCount = mysqli_fetch_assoc($bookingCountResult)['total'];



     /* Messages Count */


     $messageCountQuery = "

     SELECT COUNT(*) AS total

     FROM conversations

     WHERE owner_id = $userId

     ";


     $messageCountResult = mysqli_query($connect, $messageCountQuery);


     $messageCount = mysqli_fetch_assoc($messageCountResult)['total'];



?>

<!DOCTYPE html>

<html lang="en">


<head>

     <meta charset="UTF-8">


     <meta name="viewport" content="width=device-width, initial-scale=1.0">


     <title>Dashboard</title>


     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


     <link rel="stylesheet" href="../css/dashboard.css">


</head>


<body>



     <?php include "navbar.php"; ?>



     <div class="dashboard-container">



          <div class="welcome-section">



               <div class="welcome-text">


                    <h1>

                         Welcome, <?php echo htmlspecialchars($ownerName); ?>

                    </h1>


                    <p>

                         Manage your properties, bookings and messages from here.

                    </p>


               </div>



               <div class="welcome-button">


                    <a href="../form/property-details.php" class="add-property-btn">

                         + Add New Property

                    </a>


               </div>



          </div>





          <div class="stats-container">



               <div class="stat-card">


                    <div class="stat-icon">

                         🏠

                    </div>


                    <div class="stat-info">


                         <h3>

                              Properties

                         </h3>


                         <p>

                              <?php echo $propertyCount; ?>

                         </p>


                    </div>


               </div>





               <div class="stat-card">


                    <div class="stat-icon">

                         📅

                    </div>


                    <div class="stat-info">


                         <h3>

                              Bookings

                         </h3>


                         <p>

                              <?php echo $bookingCount; ?>

                         </p>


                    </div>


               </div>





               <div class="stat-card">


                    <div class="stat-icon">

                         💬

                    </div>


                    <div class="stat-info">


                         <h3>

                              Messages

                         </h3>


                         <p>

                              <?php echo $messageCount; ?>

                         </p>


                    </div>


               </div>



          </div>

          <div class="recent-property-section">



               <div class="section-header">



                    <h2>

                         Recent Property

                    </h2>



                    <a href="my-properties.php">

                         View All

                    </a>



               </div>





               <div class="property-card">



                    <?php if ($property) { ?>



                         <div class="property-image">



                              <?php


                              $imageQuery = "

SELECT image

FROM property_images

WHERE property_id = {$property['id']}

AND is_main = 1

LIMIT 1

";



                              $imageResult = mysqli_query($connect, $imageQuery);


                              $image = mysqli_fetch_assoc($imageResult);



                              ?>



                              <img

                                   src="../<?php echo htmlspecialchars($image['image'] ?? 'default.jpg'); ?>"

                                   alt="Property Image">



                         </div>





                         <div class="property-details">



                              <h3>

                                   <?php echo htmlspecialchars($property['category_name']); ?>

                              </h3>




                              <p>

                                   📍

                                   <?php echo htmlspecialchars($property['city']); ?>

                              </p>




                              <p>

                                   💰

                                   <?php echo htmlspecialchars($property['price']); ?>

                                   EGP

                              </p>




                         </div>





                         <div class="property-actions">



                              <a href="view-property.php?id=<?php echo $property['id']; ?>" class="view-btn">

                                   View

                              </a>



                              <a href="edit-property-details.php?id=<?php echo $property['id']; ?>" class="edit-btn">

                                   Edit

                              </a>



                              <a href="../form/delete-property.php?id=<?php echo $property['id']; ?>" class="delete-btn">

                                   Delete

                              </a>



                         </div>




                    <?php } else { ?>



                         <div class="empty-property">


                              <p>

                                   No properties added yet.

                              </p>


                         </div>



                    <?php } ?>



               </div>



          </div>

     </div>



</body>


</html>
