<?php

require_once "property.php";

/*==================================================
=                 DATABASE                         =
==================================================*/

function filterProperties(
    PDO $conn,
    $governorate = "",
    $city = "",
    $type = "",
    $maxPrice = ""
)
{

    $properties = [];

    $sql = "SELECT

                h.id,
                h.title,
                h.price,
                h.governorate,
                h.city,
                h.description,
                h.available_slots,
                c.name AS type,

                (
                    SELECT image
                    FROM property_images
                    WHERE property_id = h.id
                    LIMIT 1
                ) AS image

            FROM housing h

            INNER JOIN categories c
            ON c.id = h.category_id

            WHERE 1";


    $params = [];


    if (!empty($governorate)) {

        $sql .= " AND h.governorate LIKE ?";

        $params[] = "%".$governorate."%";
    }


    if (!empty($city)) {

        $sql .= " AND h.city LIKE ?";

        $params[] = "%".$city."%";
    }


   if (!empty($type)) {

    $sql .= " AND c.id = ?";

    $params[] = $type;
}

    if (!empty($maxPrice)) {

        $sql .= " AND h.price <= ?";

        $params[] = $maxPrice;
    }


    $sql .= " ORDER BY h.id DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute($params);


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $row['location'] = $row['governorate'] . ", " . $row['city'];

        $properties[] = $row;

    }


    return $properties;

}

?>