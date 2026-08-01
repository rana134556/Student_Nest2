<?php

/*==================================================
=              DATABASE                            =
==================================================*/

function getCategories(PDO $conn)
{

    $categories = [];

    $sql = "SELECT
                id,
                name,

                CASE
                    WHEN id = 1 THEN 'fa-solid fa-bed'
                    WHEN id = 2 THEN 'fa-solid fa-user-group'
                    WHEN id = 3 THEN 'fa-solid fa-house'
                END AS icon

            FROM categories

            ORDER BY id ASC";


    $stmt = $conn->prepare($sql);

    $stmt->execute();


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $categories[] = $row;

    }

    return $categories;
}


/*==================================================
=             GET CATEGORY BY ID                   =
==================================================*/

function getCategoryById(PDO $conn, $id)
{

    $sql = "SELECT
                id,
                name,

                CASE
                    WHEN id = 1 THEN 'fa-solid fa-bed'
                    WHEN id = 2 THEN 'fa-solid fa-user-group'
                    WHEN id = 3 THEN 'fa-solid fa-house'
                END AS icon

            FROM categories

            WHERE id = ?";


    $stmt = $conn->prepare($sql);

    $stmt->execute([$id]);


    return $stmt->fetch(PDO::FETCH_ASSOC);

}

?>