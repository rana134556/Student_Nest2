<?php

session_start();


require_once "../db.php";



$page = "properties";



if (!isset($_SESSION['user_id'])) {

    header("Location: ../tenant_login.php");

    exit();
}



$userId = $_SESSION['user_id'];



$propertyId = $_GET['id'] ?? 0;



if (!$propertyId) {

    header("Location: my-properties.php");

    exit();
}



/* Fetch Property */


$propertyQuery = "

SELECT

housing.*,

categories.name AS category_name

FROM housing

LEFT JOIN categories

ON housing.category_id = categories.id

WHERE housing.id = $propertyId

AND housing.user_id = $userId

";


$propertyResult = mysqli_query($connect, $propertyQuery);



$property = mysqli_fetch_assoc($propertyResult);



if (!$property) {

    header("Location: my-properties.php");

    exit();
}



/* Fetch Images */


$imageQuery = "

SELECT image,is_main

FROM property_images

WHERE property_id = $propertyId

ORDER BY is_main DESC

";


$imageResult = mysqli_query($connect, $imageQuery);



$images = [];


while ($row = mysqli_fetch_assoc($imageResult)) {


    $images[] = $row;
}



/* Fetch Student Requirements */


$student = [

    "fieldOfStudy" => $property['required_field_of_study'],

    "studentGender" => $property['gender'],

    "studentMinAge" => $property['min_age'],

    "studentMaxAge" => $property['max_age'],

    "studentSmoking" => $property['smoking_allowed'] ? "allowed" : "not-allowed",

    "studentRequirements" => $property['additional_requirements']

];
?>

<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>

        View Property

    </title>



    <link rel="stylesheet"
        href="../css/dashboard.css">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>



<body>



    <?php include "navbar.php"; ?>



    <div class="dashboard-container">



        <a href="my-properties.php" class="back-link">


            <i class="fa-solid fa-arrow-left"></i>


            Back To My Properties


        </a>




        <div class="details-container">



            <div class="property-header">



                <div>



                    <h1>


                        <?php

                        echo htmlspecialchars($property['category_name']);

                        ?>


                    </h1>




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

                </div>


                <span class="property-status available">


                    Available


                </span>

            </div>

            <div class="details-image">



                <?php


                if (!empty($images[0])) {


                ?>


                    <img

                        src="../<?php echo htmlspecialchars($images[0]['image']); ?>"

                        class="main-property-image" alt="Property Image" >


                <?php


                } else {


                ?>

                    <img

                        src="../Images/no-image.png"

                        alt="No Image">
                <?php

                }

                ?>

            </div>

            <div class="property-gallery">



                <?php

foreach ($images as $image) {

    if ($image['is_main'] == 1) {
        continue;
    }

?>

    <div class="gallery-image">

        <img
            src="../<?= htmlspecialchars($image['image']); ?>"
            alt="Gallery Image">

    </div>

<?php

}

?>



            </div>
            <div class="details-grid">



                <div class="details-card">


                    <h2>

                        Property Details

                    </h2>




                    <div class="details-item">


                        <span>

                            Monthly Rent

                        </span>



                        <strong>


                            <?php echo htmlspecialchars($property['price']); ?>


                            EGP


                        </strong>


                    </div>





                    <div class="details-item">


                        <span>

                            Security Deposit

                        </span>



                        <strong>


                            <?php


                            echo $property['security_deposit_required']

                                ? "Required"

                                : "Not Required";


                            ?>


                        </strong>


                    </div>





                    <div class="details-item">


                        <span>

                            Deposit Amount

                        </span>



                        <strong>


                            <?php


                            echo htmlspecialchars(

                                $property['security_deposit_amount'] ?? "0"

                            );


                            ?>


                            EGP


                        </strong>


                    </div>





                    <div class="details-item">


                        <span>

                            Google Maps

                        </span>



                        <strong>



                            <a

                                href="<?php echo htmlspecialchars($property['location_link']); ?>"

                                target="_blank">


                                Open Location


                            </a>



                        </strong>


                    </div>





                    <div class="details-description">


                        <h3>

                            Description

                        </h3>



                        <p>


                            <?php


                            echo nl2br(

                                htmlspecialchars(

                                    $property['description'] ?? "No Description"

                                )

                            );


                            ?>


                        </p>



                    </div>



                </div>





                <div class="details-card">


                    <h2>

                        Student Requirements

                    </h2>





                    <div class="details-item">


                        <span>

                            Field Of Study

                        </span>



                        <strong>


                            <?php


                            echo htmlspecialchars(

                                $student['fieldOfStudy'] ?? "Any"

                            );


                            ?>


                        </strong>


                    </div>




                    <div class="details-item">


                        <span>

                            Gender

                        </span>



                        <strong>


                            <?php


                            echo htmlspecialchars(

                                $student['studentGender'] ?? "No Preference"

                            );


                            ?>


                        </strong>


                    </div>




                    <div class="details-item">


                        <span>

                            Age

                        </span>



                        <strong>



                            <?php echo $student['studentMinAge'] ?? "-"; ?>


                            -


                            <?php echo $student['studentMaxAge'] ?? "-"; ?>


                        </strong>


                    </div>




                    <div class="details-item">


                        <span>

                            Smoking

                        </span>



                        <strong>


                            <?php


                            echo ucfirst(

                                str_replace(

                                    "-",

                                    " ",

                                    $student['studentSmoking']

                                )

                            );


                            ?>


                        </strong>


                    </div>
                    <div class="details-description">


                        <h3>

                            Additional Requirements

                        </h3>



                        <p>


                            <?php


                            echo nl2br(

                                htmlspecialchars(

                                    $student['studentRequirements'] ?? "No Additional Requirements"

                                )

                            );


                            ?>


                        </p>



                    </div>



                </div>



            </div>





            <div class="property-bottom-actions">



                <a

                     href="edit-property-details.php?id=<?php echo $property['id']; ?>"

                    class="edit-btn-large">


                    <i class="fa-solid fa-pen"></i>


                    Edit Property


                </a>





                <a

                 href="../form/delete-property.php?id=<?php echo $property['id']; ?>"

                    class="delete-btn-large">


                    <i class="fa-solid fa-trash"></i>


                    Delete Property


                </a>



            </div>





        </div>


    </div>


</body>


</html>