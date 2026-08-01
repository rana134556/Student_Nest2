<?php

function getBookings(PDO $conn, $userId)
{

    $bookings = [];

    $sql = "SELECT

                b.id,
                b.booking_date,
                b.status,

                h.id AS property_id,
                h.title,
                CONCAT(h.governorate, ', ', h.city) AS location,
                h.price

            FROM bookings b

            INNER JOIN housing h
            ON h.id = b.property_id

            WHERE b.tenant_id = ?

            ORDER BY b.booking_date DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute([$userId]);


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $bookings[] = $row;

    }

    return $bookings;

}

?>