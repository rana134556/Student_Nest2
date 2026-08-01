<?php

/*==================================================
=              GET ALL PROPERTIES                  =
==================================================*/

function getProperties(PDO $conn)
{

    $properties = [];

    $sql = "SELECT

                h.id,
                h.title,
                h.price,
                h.city AS location,
                h.description,
                h.available_slots,
                h.address,
                h.gender,

                u.name AS owner,
                u.phone,

               
 (
    SELECT image
    FROM property_images
    WHERE property_id = h.id
    AND is_main = 1
    LIMIT 1
) AS image
            FROM housing h

            INNER JOIN users u
                ON u.id = h.user_id

            ORDER BY h.id DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute();


    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $properties[] = $row;
    }

    return $properties;

}



/*==================================================
=              GET PROPERTY BY ID                  =
==================================================*/

function getPropertyById(PDO $conn, $id)
{
    $sql = "SELECT

                h.*,

                c.name AS category,

                CONCAT(h.governorate, ', ', h.city) AS location,

                u.name AS owner,
                u.phone,
                u.image AS owner_image,

                
    (
    SELECT image
    FROM property_images
    WHERE property_id = h.id
    AND is_main = 1
    LIMIT 1
) AS image

            FROM housing h

            INNER JOIN users u
                ON u.id = h.user_id

            INNER JOIN categories c
                ON c.id = h.category_id

            WHERE h.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getPropertyImages(PDO $conn, $propertyId)
{
    $sql = "SELECT image, is_main
            FROM property_images
            WHERE property_id = ?
            ORDER BY is_main DESC, id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$propertyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPropertyFacilities(PDO $conn, $propertyId)
{
    $sql = "SELECT f.name

            FROM property_facilities pf

            INNER JOIN facilities f
            ON f.id = pf.facility_id

            WHERE pf.property_id = ?

            ORDER BY f.name";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$propertyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPropertyReviews(PDO $conn, $propertyId)
{
    $sql = "SELECT

                r.rating,
                r.comment,
                r.review_date,

                u.name,
                u.image

            FROM reviews r

            INNER JOIN users u
            ON u.id = r.tenant_id

            WHERE r.property_id = ?

            ORDER BY r.review_date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$propertyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/*==================================================
=            GET PROPERTIES BY CATEGORY            =
==================================================*/

function getPropertiesByCategory(PDO $conn, $categoryId)
{
    $properties = [];

    $sql = "SELECT 
                h.id,
                h.title,
                h.price,
                h.city AS location,
                h.description,
                h.available_slots,
                h.address,
                h.gender,
                u.name AS owner,
                u.phone,
                (
    SELECT image
    FROM property_images
    WHERE property_id = h.id
    AND is_main = 1
    LIMIT 1
) AS image
            FROM housing h
            INNER JOIN users u
                ON u.id = h.user_id
            WHERE h.category_id = ?
            ORDER BY h.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$categoryId]);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $properties[] = $row;
    }

    return $properties;
}
?>