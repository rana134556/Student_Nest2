<?php

session_start();


require_once "../db.php";



/* Check User */

if (!isset($_SESSION['user_id'])) {


    header("Location: ../login.php");

    exit();
}



$userId = $_SESSION['user_id'];





/* Check Request */


if (!isset($_GET['id']) || !isset($_GET['action'])) {


    header("Location: bookings.php");

    exit();
}



$bookingId = $_GET['id'];

$action = $_GET['action'];





/* Validate Action */


if (

    $action != "accept"

    &&

    $action != "reject"

) {


    header("Location: bookings.php");

    exit();
}





/* Update Booking Status */


$status = ($action == "accept")

    ? "Approved"

    : "Rejected";




$query = mysqli_prepare(

    $connect,

    "UPDATE bookings

     INNER JOIN housing

     ON bookings.property_id = housing.id

     SET bookings.status = ?

     WHERE bookings.id = ?

     AND housing.user_id = ?"

);



mysqli_stmt_bind_param(

    $query,

    "sii",

    $status,

    $bookingId,

    $userId

);



mysqli_stmt_execute($query);



$getBooking = mysqli_prepare(
    $connect,
    "SELECT property_id, tenant_id
     FROM bookings
     WHERE id = ?"
);

mysqli_stmt_bind_param($getBooking, "i", $bookingId);
mysqli_stmt_execute($getBooking);

$result = mysqli_stmt_get_result($getBooking);
$booking = mysqli_fetch_assoc($result);



if ($status == "Approved") {

    $propertyId = (int)$booking['property_id'];
    $tenantId = (int)$booking['tenant_id'];
    $ownerId = (int)$userId;

    // Check if conversation already exists
    $checkConversation = mysqli_prepare(
        $connect,
        "SELECT id
         FROM conversations
         WHERE property_id = ?
         AND tenant_id = ?
         AND owner_id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $checkConversation,
        "iii",
        $propertyId,
        $tenantId,
        $ownerId
    );

    mysqli_stmt_execute($checkConversation);

    $conversationResult = mysqli_stmt_get_result($checkConversation);

    if (mysqli_num_rows($conversationResult) === 0) {

        $insertConversation = mysqli_prepare(
            $connect,
            "INSERT INTO conversations
            (property_id, tenant_id, owner_id)
            VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $insertConversation,
            "iii",
            $propertyId,
            $tenantId,
            $ownerId
        );

        mysqli_stmt_execute($insertConversation);
    }
}
header("Location: bookings.php");

exit();