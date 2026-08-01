<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../db.php";


/* Get Property ID */

$propertyId = $_GET['id'] ?? $_SESSION['property_id'] ?? null;


if (!$propertyId) {

    header("Location: my-properties.php");
    exit();
}


/* Get Property Data */

$propertyQuery = mysqli_prepare(
    $connect,
    "SELECT * FROM housing WHERE id = ?"
);


mysqli_stmt_bind_param(
    $propertyQuery,
    "i",
    $propertyId
);


mysqli_stmt_execute($propertyQuery);


$propertyResult = mysqli_stmt_get_result($propertyQuery);


$property = mysqli_fetch_assoc($propertyResult);



if (!$property) {

    header("Location: my-properties.php");
    exit();
}


/* Get Property Images */

$images = [];


$imageQuery = mysqli_prepare(
    $connect,
    "SELECT image FROM property_images WHERE property_id = ?"
);


mysqli_stmt_bind_param(
    $imageQuery,
    "i",
    $propertyId
);


mysqli_stmt_execute($imageQuery);


$imageResult = mysqli_stmt_get_result($imageQuery);


while ($image = mysqli_fetch_assoc($imageResult)) {

    $images[] = $image['image'];
}


/* Get Property Facilities */

$facilities = [];


$facilityQuery = mysqli_prepare(
    $connect,
    "SELECT facility_id FROM property_facilities WHERE property_id = ?"
);


mysqli_stmt_bind_param(
    $facilityQuery,
    "i",
    $propertyId
);


mysqli_stmt_execute($facilityQuery);


$facilityResult = mysqli_stmt_get_result($facilityQuery);


while ($facility = mysqli_fetch_assoc($facilityResult)) {

    $facilities[] = $facility['facility_id'];
}
/* Handle Update */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $propertyType = $_POST['propertyType'] ?? "";

    $monthlyRent = $_POST['monthlyRent'] ?? "";

    $governorate = $_POST['governorate'] ?? "";

    $city = $_POST['city'] ?? "";

    $address = $_POST['address'] ?? "";

    $googleMap = $_POST['googleMap'] ?? "";
    $description = $_POST['description'] ?? "";

    $availableSlots = $_POST['availableBeds'] ?? "";

    $depositRequired = ($_POST['deposit'] ?? "") == "required" ? 1 : 0;

    $depositAmount = $_POST['depositAmount'] ?? null;


    $gender = $_POST['studentGender'] ?? "Both";

    $fieldOfStudy = $_POST['fieldOfStudy'] ?? "";

    $minAge = $_POST['studentMinAge'] ?? null;

    $maxAge = $_POST['studentMaxAge'] ?? null;

    $smokingAllowed = ($_POST['studentSmoking'] ?? "") == "allowed" ? 1 : 0;

    $requirements = $_POST['studentRequirements'] ?? "";



    /* Update Housing */


    $updateHousing = mysqli_prepare(
        $connect,
        "UPDATE housing SET
        category_id = ?,
        price = ?,
        governorate = ?,
        city = ?,
        address = ?,
        location_link = ?,
        description = ?,
        available_slots = ?,
        gender = ?,
        security_deposit_required = ?,
        security_deposit_amount = ?,
        required_field_of_study = ?,
        min_age = ?,
        max_age = ?,
        smoking_allowed = ?,
        additional_requirements = ?
        WHERE id = ?"
    );



    $category = match ($propertyType) {

        "single-room" => 1,

        "double-room" => 2,

        "empty-apartment" => 3,

        default => 1
    };



    mysqli_stmt_bind_param(
        $updateHousing,
        "idssssissssiiisi",
        $category,
        $monthlyRent,
        $governorate,
        $city,
        $address,
        $googleMap,
        $description,
        $availableSlots,
        $gender,
        $depositRequired,
        $depositAmount,
        $fieldOfStudy,
        $minAge,
        $maxAge,
        $smokingAllowed,
        $requirements,
        $propertyId
    );


    mysqli_stmt_execute($updateHousing);



    /* Update Facilities */


    mysqli_query(
        $connect,
        "DELETE FROM property_facilities WHERE property_id = $propertyId"
    );



    if (!empty($_POST['features'])) {


        foreach ($_POST['features'] as $facilityId) {


            $insertFacility = mysqli_prepare(
                $connect,
                "INSERT INTO property_facilities
                (property_id, facility_id)
                VALUES (?, ?)"
            );


            mysqli_stmt_bind_param(
                $insertFacility,
                "ii",
                $propertyId,
                $facilityId
            );


            mysqli_stmt_execute($insertFacility);
        }
    }
    /* Update Images */


    if (!empty($_FILES['propertyImages']['name'][0])) {


        foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmpName) {


            $imageName = basename(
                $_FILES['propertyImages']['name'][$key]
            );


            $uploadPath = "../" . $imageName;



            if (move_uploaded_file($tmpName, $uploadPath)) {


                $imageInsert = mysqli_prepare(
                    $connect,
                    "INSERT INTO property_images
                (property_id, image)
                VALUES (?, ?)"
                );


                mysqli_stmt_bind_param(
                    $imageInsert,
                    "is",
                    $propertyId,
                    $imageName
                );


                mysqli_stmt_execute($imageInsert);
            }
        }
    }



    /* Success */

    header(
        "Location: my-properties.php?updated=1"
    );

    exit();
}