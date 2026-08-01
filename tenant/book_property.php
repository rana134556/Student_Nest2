<?php

require "../config/auth.php";
require "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$propertyId = (int)$_GET['id'];
$tenantId = $_SESSION['user_id'];

/*==========================
Check if already booked
==========================*/

$sql = "SELECT id
        FROM bookings
        WHERE property_id = ?
        AND tenant_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$propertyId, $tenantId]);

if ($stmt->fetch()) {

    $_SESSION['message'] = "You have already booked this property.";

    header("Location: property_details.php?id=".$propertyId);
    exit;
}

/*==========================
Insert Booking
==========================*/

$sql = "INSERT INTO bookings
(property_id, tenant_id, booking_date, message, status)
VALUES (?, ?, NOW(), ?, 'Pending')";

$stmt = $conn->prepare($sql);

if ($stmt->execute([
    $propertyId,
    $tenantId,
    "",
])) {

    $_SESSION['message'] = "Booking request sent successfully.";

} else {

    $_SESSION['message'] = "Booking failed.";

}

header("Location: bookings.php");
exit;