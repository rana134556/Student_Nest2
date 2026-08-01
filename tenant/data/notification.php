<?php

/*==================================================
=              GET NOTIFICATIONS                   =
==================================================*/

function getNotifications(PDO $conn, $userId)
{

    $notifications = [];

    $sql = "SELECT

                id,
                type,
                title,
                link,
                created_at AS date,
                is_read

            FROM notifications

            WHERE user_id = ?

            ORDER BY created_at DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute([$userId]);


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $notifications[] = $row;
    }

    return $notifications;
}



/*==================================================
=           MARK NOTIFICATION AS READ              =
==================================================*/

function markNotificationAsRead(PDO $conn, $notificationId)
{

    $sql = "UPDATE notifications
            SET is_read = 1
            WHERE id = ?";


    $stmt = $conn->prepare($sql);


    return $stmt->execute([
        $notificationId
    ]);

}

?>