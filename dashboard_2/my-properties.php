<?php

session_start();


require_once "../db.php";


$page = "properties";



if (!isset($_SESSION['user_id'])) {

    header("Location: ../tenant_login.php");

    exit();
}



$userId = $_SESSION['user_id'];



/* Fetch Properties */


$propertiesQuery = "

SELECT

housing.*,

categories.name AS category_name

FROM housing

LEFT JOIN categories

ON housing.category_id = categories.id

WHERE housing.user_id = $userId

ORDER BY housing.id DESC

";



$propertiesResult = mysqli_query($connect, $propertiesQuery);



$properties = [];



while ($row = mysqli_fetch_assoc($propertiesResult)) {


    $properties[] = $row;
}

?>

<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>

        My Properties

    </title>


    <link rel="stylesheet"
        href="../css/dashboard.css">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>


<body>


    <?php include "navbar.php"; ?>



    <div class="dashboard-container">



        <div class="section-header">


            <h2>

                My Properties

            </h2>



            <a href="../form/property-details.php">


                <i class="fa-solid fa-plus"></i>


                Add New Property


            </a>



        </div>




        <div class="properties-grid">


            <?php


            if (!empty($properties)) {


                foreach ($properties as $property) {


            ?>



                    <div class="property-card">



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

                                <?php

                                echo htmlspecialchars($property['category_name']);

                                ?>

                            </h3>




                            <p>

                                <i class="fa-solid fa-location-dot"></i>


                                <?php

                                echo htmlspecialchars($property['governorate']);

                                ?>

                                -

                                <?php

                                echo htmlspecialchars($property['city']);

                                ?>


                            </p>




                            <p>

                                <i class="fa-solid fa-money-bill-wave"></i>


                                <?php

                                echo htmlspecialchars($property['price']);

                                ?>


                                EGP / Month


                            </p>




                            <span class="property-status available">

                                Available

                            </span>



                        </div>




                        <div class="property-actions">



                            <a

                                href="view-property.php?id=<?php echo $property['id']; ?>"

                                class="view-btn">


                                <i class="fa-solid fa-eye"></i>


                                View


                            </a>





                            <a

                                href="edit-property-details.php?id=<?php echo $property['id']; ?>"

                                class="edit-btn">


                                <i class="fa-solid fa-pen"></i>


                                Edit


                            </a>





                            <a

                                href="../form/delete-property.php?id=<?php echo $property['id']; ?>"

                                class="delete-btn"

                                onclick="return confirm('Are you sure you want to delete this property?');">


                                <i class="fa-solid fa-trash"></i>


                                Delete


                            </a>




                        </div>



                    </div>



                <?php


                }
            } else {


                ?>



                <div class="empty-property">


                    <i class="fa-solid fa-house-circle-xmark"
                        style="font-size:70px;
color:#e05f97;
margin-bottom:20px;"></i>



                    <h2>

                        No Properties Yet

                    </h2>




                    <br>



                    <p>

                        You haven't added any property yet.

                    </p>



                    <br>




                    <a

                        href="../form/property-details.php"

                        class="edit-btn">


                        <i class="fa-solid fa-plus"></i>


                        Add Property


                    </a>



                </div>



            <?php


            }


            ?>


        </div>


    </div>



</body>


</html>