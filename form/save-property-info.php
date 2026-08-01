<?php

session_start();

require_once "../db.php";


// Check Request Method

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: property-info.php");

    exit();
}


// Property Information

$availableBeds = $_POST['availableBeds'] ?? "";

$deposit = $_POST['deposit'] ?? "";

$depositAmount = $_POST['depositAmount'] ?? "";


// Property ID

$propertyId = $_SESSION['property_id'] ?? 0;


if ($propertyId == 0) {

    header("Location: property-details.php");

    exit();
}


// Deposit Value

$depositRequired = ($deposit == "required") ? 1 : 0;

// Update Property Data

$query = mysqli_query(

    $connect,

    "UPDATE housing SET

        available_slots = '$availableBeds',

        security_deposit_required = '$depositRequired',

        security_deposit_amount = '$depositAmount'

    WHERE id = '$propertyId'"

);



if (!$query) {

    die("Update Error: " . mysqli_error($connect));
}




// Go To Next Page

header("Location: student-requirements.php");

exit();