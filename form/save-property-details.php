<?php

session_start();

require_once "../db.php";


// Check Request Method

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: property-details.php");

    exit();
}


// Property Data

$propertyType = trim($_POST['propertyType'] ?? "");

$monthlyRent = trim($_POST['monthlyRent'] ?? "");

$governorate = trim($_POST['governorate'] ?? "");

$city = trim($_POST['city'] ?? "");

$address = trim($_POST['address'] ?? "");

$googleMap = trim($_POST['googleMap'] ?? "");
$description = trim($_POST['description'] ?? "");

$features = $_POST['features'] ?? [];


// Get User ID

$userId = $_SESSION['user_id'] ?? 17;


// Insert Property

$propertyQuery = mysqli_query(

    $connect,

    "INSERT INTO housing

    (
        user_id,
        category_id,
        governorate,
        city,
        address,
        location_link,
         description,
        price,
        title
    )

    VALUES

    (
        '$userId',
        '$propertyType',
        '$governorate',
        '$city',
        '$address',
        '$googleMap',
          '$description',
        '$monthlyRent',
        'New Property'
    )"

);


if (!$propertyQuery) {

    die("Property Error: " . mysqli_error($connect));
}


$propertyId = mysqli_insert_id($connect);
$_SESSION['property_id'] = $propertyId;
// Save Property Features

if (!empty($features)) {


    foreach ($features as $featureId) {


        mysqli_query(

            $connect,

            "INSERT INTO property_facilities

            (
                property_id,
                facility_id
            )

            VALUES

            (
                '$propertyId',
                '$featureId'
            )"

        );
    }
}



// Upload Property Images

$uploadFolder = "../";

$images = [];



if (

    isset($_FILES['propertyImages'])

    &&

    !empty($_FILES['propertyImages']['name'][0])

) {


    foreach ($_FILES['propertyImages']['name'] as $key => $image) {


        $tmpName = $_FILES['propertyImages']['tmp_name'][$key];


        $newName = uniqid() . "_" . basename($image);



        if (

            move_uploaded_file(

                $tmpName,

                $uploadFolder . $newName

            )

        ) {


            $isMain = ($key == 0) ? 1 : 0;



            mysqli_query(

                $connect,

                "INSERT INTO property_images

                (
                    property_id,
                    image,
                    is_main
                )

                VALUES

                (
                    '$propertyId',
                    '$newName',
                    '$isMain'
                )"

            );


            $images[] = $newName;
        }
    }
}

// Go To Next Page

header("Location: property-info.php");

exit();