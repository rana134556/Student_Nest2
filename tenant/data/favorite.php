<?php

/*==================================================
=                 GET FAVORITES                    =
==================================================*/

function getFavorites(PDO $conn, $tenantId)
{

    $favorites = [];


    $sql = "SELECT

                f.id,
                f.property_id,

                h.title,
                h.price,
                h.city AS location,

                (
    SELECT image
    FROM property_images
    WHERE property_id = h.id
    AND is_main = 1
    LIMIT 1
) AS image

            FROM favorites f

            INNER JOIN housing h
                ON h.id = f.property_id

            WHERE f.tenant_id = ?

            ORDER BY f.id DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $tenantId
    ]);


    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $favorites[] = $row;
    }


    return $favorites;

}
/*==================================================
=                 ADD FAVORITE                     =
==================================================*/

function addFavorite(PDO $conn, $tenantId, $propertyId)
{
    // منع التكرار
    $check = $conn->prepare("
        SELECT id
        FROM favorites
        WHERE tenant_id = ? AND property_id = ?
    ");

    $check->execute([
        $tenantId,
        $propertyId
    ]);

    if ($check->fetch()) {
        return false;
    }

    $sql = "INSERT INTO favorites
            (tenant_id, property_id)
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        $tenantId,
        $propertyId
    ]);
}


/*==================================================
=               REMOVE FAVORITE                    =
==================================================*/

function removeFavorite(PDO $conn, $tenantId, $propertyId)
{
    $sql = "DELETE FROM favorites
            WHERE tenant_id = ?
            AND property_id = ?";

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        $tenantId,
        $propertyId
    ]);
}
?>