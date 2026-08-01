<?php

session_start();


require_once "../db.php";



$page = "profile";



/* Current User */

$currentUserId = $_SESSION['user_id'] ?? null;


/*
Temporary value until login system is connected.
Replace with session user_id later.
*/

// if (!$currentUserId) {

//         $currentUserId = DEFAULT_LANDLORD_ID;
// }





/* Fetch User Data */


$userQuery = mysqli_prepare(

        $connect,

        "SELECT

        name,

        email,

        phone,

        image,

        role


    FROM users


    WHERE id = ?"

);



mysqli_stmt_bind_param(

        $userQuery,

        "i",

        $currentUserId

);



mysqli_stmt_execute($userQuery);



$userResult = mysqli_stmt_get_result($userQuery);



$userData = mysqli_fetch_assoc($userResult);





/* Fetch Statistics */


$propertiesQuery = mysqli_prepare(

        $connect,

        "SELECT COUNT(*) AS total

     FROM housing

     WHERE user_id = ?"

);



mysqli_stmt_bind_param(

        $propertiesQuery,

        "i",

        $currentUserId

);



mysqli_stmt_execute($propertiesQuery);



$propertiesResult = mysqli_stmt_get_result($propertiesQuery);



$propertiesCount = mysqli_fetch_assoc($propertiesResult)['total'];





$bookingsQuery = mysqli_prepare(

        $connect,

        "SELECT COUNT(*) AS total

     FROM bookings

     INNER JOIN housing

     ON bookings.property_id = housing.id

     WHERE housing.user_id = ?"

);



mysqli_stmt_bind_param(

        $bookingsQuery,

        "i",

        $currentUserId

);



mysqli_stmt_execute($bookingsQuery);



$bookingsResult = mysqli_stmt_get_result($bookingsQuery);



$bookingsCount = mysqli_fetch_assoc($bookingsResult)['total'];





$user = [

        "name" => $userData['name'] ?? "",

        "email" => $userData['email'] ?? "",

        "phone" => $userData['phone'] ?? "",

        "role" => $userData['role'] ?? "landlord",

        "image" => $userData['image'] ?? "",

        "location" => "Egypt",

        "properties" => $propertiesCount,

        "bookings" => $bookingsCount

];
?>

<!DOCTYPE html>

<html lang="en">


<head>


        <meta charset="UTF-8">


        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <title>

                Profile

        </title>



        <link rel="stylesheet" href="../css/dashboard.css">



        <link rel="stylesheet"

                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

   
</head>



<body>



        <?php include "navbar.php"; ?>



        <div class="dashboard-container">



                <div class="profile-page">



                        <div class="profile-header">



                                <div class="profile-image">


                                        <?php

                                        if (!empty($user['image'])) {

                                        ?>


                                                <img

                                                        src="../<?php echo htmlspecialchars($user['image']); ?>"

                                                        alt="Profile Image">


                                        <?php

                                        } else {

                                        ?>


                                                <img

                                                        src="../Images/default-user.png"

                                                        alt="Default User">


                                        <?php

                                        }

                                        ?>


                                </div>





                                <div class="profile-info">


                                        <h1>

                                                <?php

                                                echo htmlspecialchars($user['name']);

                                                ?>

                                        </h1>



                                        <p class="profile-role">

                                                <i class="fa-solid fa-user"></i>

                                                <?php

                                                echo htmlspecialchars($user['role']);

                                                ?>

                                        </p>


                                        <p class="profile-location">

                                                <i class="fa-solid fa-location-dot"></i>

                                                <?php

                                                echo htmlspecialchars($user['location']);

                                                ?>

                                        </p>



                                </div>





                                <a href="edit-profile.php" class="edit-profile-btn">


                                        <i class="fa-solid fa-pen"></i>

                                        Edit Profile


                                </a>



                        </div>





                        <div class="profile-content">



                                <div class="profile-card">


                                        <h2>

                                                Account Information

                                        </h2>



                                        <div class="profile-item">


                                                <span>

                                                        Email

                                                </span>


                                                <strong>

                                                        <?php

                                                        echo htmlspecialchars($user['email']);

                                                        ?>

                                                </strong>


                                        </div>





                                        <div class="profile-item">


                                                <span>

                                                        Phone Number

                                                </span>


                                                <strong>

                                                        <?php

                                                        echo htmlspecialchars($user['phone']);

                                                        ?>

                                                </strong>


                                        </div>





                                        <div class="profile-item">


                                                <span>

                                                        Location

                                                </span>


                                                <strong>

                                                        <?php

                                                        echo htmlspecialchars($user['location']);

                                                        ?>

                                                </strong>


                                        </div>



                                </div>





                                <div class="profile-card">


                                        <h2>

                                                Statistics

                                        </h2>



                                        <div class="statistics-box">



                                                <div class="stat-item">


                                                        <i class="fa-solid fa-house"></i>


                                                        <h3>

                                                                <?php

                                                                echo $user['properties'];

                                                                ?>

                                                        </h3>


                                                        <p>

                                                                Properties

                                                        </p>


                                                </div>





                                                <div class="stat-item">


                                                        <i class="fa-solid fa-calendar-check"></i>


                                                        <h3>

                                                                <?php

                                                                echo $user['bookings'];

                                                                ?>

                                                        </h3>


                                                        <p>

                                                                Bookings

                                                        </p>


                                                </div>



                                        </div>



                                </div>



                        </div>



                </div>



        </div>



</body>


</html>