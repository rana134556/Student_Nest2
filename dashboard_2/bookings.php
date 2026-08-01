<?php

session_start();


require_once "../db.php";


$page = "bookings";



/* Check User */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();
}



$userId = $_SESSION['user_id'];





/* Fetch Bookings */


$query = mysqli_prepare(

    $connect,

    "SELECT

        bookings.id,
        bookings.property_id,
       bookings.tenant_id,
        bookings.booking_date,

        bookings.status,


        housing.title,

        housing.governorate,

        housing.city,

        housing.price,


        users.name AS student_name


    FROM bookings


    INNER JOIN housing

    ON bookings.property_id = housing.id


    INNER JOIN users

    ON bookings.tenant_id = users.id


    WHERE housing.user_id = ?"

);



mysqli_stmt_bind_param(

    $query,

    "i",

    $userId

);



mysqli_stmt_execute($query);



$result = mysqli_stmt_get_result($query);



$bookings = [];



while ($row = mysqli_fetch_assoc($result)) {


    $bookings[] = [


        "id" => $row['id'],
        "propertyId"=>$row['property_id'],
        "tenantId" => $row['tenant_id'],
        "propertyName" => $row['title'],


        "location" =>

        $row['governorate'] . " - " . $row['city'],


        "monthlyRent" => $row['price'],


        "studentName" => $row['student_name'],


        "requestDate" =>

        date(

            "Y-m-d",

            strtotime($row['booking_date'])

        ),


        "status" => $row['status']


    ];
}
?>

<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>

        Bookings

    </title>


    <link rel="stylesheet"
        href="../css/dashboard.css">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>



<body>



    <?php include "navbar.php"; ?>



    <div class="dashboard-container">



        <div class="page-header">


            <h1>

                My Bookings

            </h1>



            <p>

                Manage student booking requests for your properties.

            </p>


        </div>





        <!-- Filters -->


        <div class="booking-filters">


            <button class="active">

                All

            </button>



            <button>

                Pending

            </button>



            <button>

                Accepted

            </button>



            <button>

                Rejected

            </button>



        </div>





        <div class="bookings-container">



            <?php


            if (empty($bookings)) {


            ?>


                <div class="empty-state">


                    <i class="fa-solid fa-calendar-xmark"></i>



                    <h2>

                        No Bookings Yet

                    </h2>



                    <p>

                        You don't have any booking requests at the moment.

                    </p>



                </div>



                <?php


            } else {


                foreach ($bookings as $booking) {


                ?>



                    <div class="booking-card <?php echo strtolower($booking['status']); ?>">



                        <!-- Property Information -->


                        <div class="booking-property">


                            <div class="booking-image">


                                <?php


                                $imageQuery = mysqli_prepare(

                                    $connect,

                                    "SELECT image

     FROM property_images

     WHERE property_id =

     (

        SELECT property_id

        FROM bookings

        WHERE id = ?

     )

     AND is_main = 1

     LIMIT 1"

                                );



                                mysqli_stmt_bind_param(

                                    $imageQuery,

                                    "i",

                                    $booking['id']

                                );



                                mysqli_stmt_execute($imageQuery);



                                $imageResult = mysqli_stmt_get_result($imageQuery);



                                $imageData = mysqli_fetch_assoc($imageResult);



                                ?>


                                <img

                                    src="../<?php echo htmlspecialchars($imageData['image'] ?? 'default.jpg'); ?>"

                                    alt="Property Image">



                            </div>





                            <div class="booking-property-info">


                                <h2>

                                    <?php

                                    echo htmlspecialchars(

                                        $booking['propertyName']

                                    );

                                    ?>

                                </h2>




                                <p>

                                    <i class="fa-solid fa-location-dot"></i>


                                    <?php

                                    echo htmlspecialchars(

                                        $booking['location']

                                    );

                                    ?>

                                </p>




                                <p>

                                    <i class="fa-solid fa-money-bill"></i>


                                    <?php

                                    echo htmlspecialchars(

                                        $booking['monthlyRent']

                                    );

                                    ?>


                                    EGP

                                </p>



                            </div>



                        </div>

                        <!-- Student Information -->

                        <div class="booking-student">


                            <h3>

                                Student Information

                            </h3>



                            <div class="booking-details">


                                <p>

                                    <strong>Name:</strong>

                                    <?php

                                    echo htmlspecialchars(

                                        $booking['studentName']

                                    );

                                    ?>

                                </p>



                                <p>

                                    <strong>Request Date:</strong>

                                    <?php

                                    echo htmlspecialchars(

                                        $booking['requestDate']

                                    );

                                    ?>

                                </p>


                            </div>


                        </div>





                        <!-- Booking Status -->


                        <div class="booking-footer">


                            <span class="booking-status 
<?php echo strtolower($booking['status']); ?>">


                                <?php

                                echo htmlspecialchars(

                                    $booking['status']

                                );

                                ?>


                            </span>





                            <?php


                            if ($booking['status'] == "Pending") {


                            ?>


                                <div class="booking-actions">



                                    <a

                                        href="booking-action.php?id=<?php echo $booking['id']; ?>&action=accept"

                                        class="accept-btn">


                                        <i class="fa-solid fa-check"></i>


                                        Accept


                                    </a>





                                    <a

                                        href="booking-action.php?id=<?php echo $booking['id']; ?>&action=reject"

                                        class="reject-btn">


                                        <i class="fa-solid fa-xmark"></i>


                                        Reject


                                    </a>



                                </div>



                            <?php


                            }



                            if ($booking['status'] == "Approved") {


                            ?>


                                <div class="booking-actions">



                                    <a
                                 
                                         href="messages.php?property=<?php echo $booking['propertyId']; ?>&tenant=<?php echo $booking['tenantId']; ?>"

                                        class="message-btn">


                                        <i class="fa-solid fa-message"></i>


                                        Message Student


                                    </a>



                                </div>



                            <?php


                            }


                            ?>



                        </div>



                    </div>



            <?php


                }
            }


            ?>



        </div>



    </div>



</body>


</html>