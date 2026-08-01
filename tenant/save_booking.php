<?php

require "../config/auth.php";
require "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: dashboard.php");
    exit;
}

$propertyId = (int)$_POST['property_id'];
$tenantId   = $_SESSION['user_id'];
$message    = trim($_POST['message']);

/*=========================
Check Duplicate Booking
=========================*/

$check = $conn->prepare("
SELECT id
FROM bookings
WHERE property_id = ?
AND tenant_id = ?
");

$check->execute([$propertyId, $tenantId]);

if($check->fetch()){

    $_SESSION['message'] = "You already booked this property.";

    header("Location: property_details.php?id=".$propertyId);
    exit;
}

/*=========================
Save Booking
=========================*/

$sql = "
INSERT INTO bookings
(
property_id,
tenant_id,
booking_date,
message,
status
)
VALUES
(
?,
?,
NOW(),
?,
'Pending'
)
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $propertyId,
    $tenantId,
    $message
]);

$_SESSION['message'] = "Booking request sent successfully.";

header("Location: bookings.php");
exit;