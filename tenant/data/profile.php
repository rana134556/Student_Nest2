<?php

/*==================================================
=                    GET PROFILE                   =
==================================================*/

function getProfile(PDO $conn, $userId)
{
    $sql = "SELECT 
                id,
                name,
                email,
                phone,
                image
            FROM users 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/*==================================================
=                   UPDATE PROFILE                 =
==================================================*/

function updateProfile(
    PDO $conn,
    $userId,
    $name,
    $email,
    $phone,
    $image
)
{
    
    if (!empty($image)) {
        $sql = "UPDATE users 
                SET 
                    name = ?,
                    email = ?,
                    phone = ?,
                    image = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $image, $userId]);
    } else {
       
        $sql = "UPDATE users 
                SET 
                    name = ?,
                    email = ?,
                    phone = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $userId]);
    }
}

?>