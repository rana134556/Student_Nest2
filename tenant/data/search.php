<?php

require_once "property.php";

/*==================================================
=              SEARCH PROPERTIES                   =
==================================================*/

function searchProperties(PDO $conn, $keyword = "")
{

    $properties = [];

    $sql = "SELECT

                h.id,
                h.title,
                h.price,
                h.city AS location,
                h.description,

                u.name AS owner,
                u.phone,

                (
                    SELECT image
                    FROM property_images
                    WHERE property_id = h.id
                    LIMIT 1
                ) AS image

            FROM housing h

            INNER JOIN users u
                ON u.id = h.user_id

            WHERE

                h.title LIKE ?
                OR h.city LIKE ?
                OR h.governorate LIKE ?

            ORDER BY h.id DESC";


    $search = "%".$keyword."%";


    $stmt = $conn->prepare($sql);


    $stmt->execute([
        $search,
        $search,
        $search
    ]);


    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $properties[] = $row;
    }


    return $properties;

}

?>