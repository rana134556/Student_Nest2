<?php

/*==================================================
=              GET CONVERSATIONS                   =
==================================================*/

function getConversations(PDO $conn, $tenantId)
{

    $conversations = [];

    $sql = "SELECT

                c.id,
                c.owner_id,
                u.name AS owner_name,
                h.title AS property_title,

                (
                    SELECT message
                    FROM messages
                    WHERE conversation_id = c.id
                    ORDER BY id DESC
                    LIMIT 1
                ) AS last_message,

                (
                    SELECT created_at
                    FROM messages
                    WHERE conversation_id = c.id
                    ORDER BY id DESC
                    LIMIT 1
                ) AS time

            FROM conversations c

            INNER JOIN users u
                ON u.id = c.owner_id

            INNER JOIN housing h
                ON h.id = c.property_id

            WHERE c.tenant_id = ?

            ORDER BY c.id DESC";


    $stmt = $conn->prepare($sql);

    $stmt->execute([$tenantId]);


    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $conversations[] = $row;
    }

    return $conversations;

}



/*==================================================
=                 GET MESSAGES                     =
==================================================*/

function getMessages(PDO $conn, $conversationId)
{

    $messages = [];

    $sql = "SELECT
                sender,
                message,
                created_at AS time

            FROM messages

            WHERE conversation_id = ?

            ORDER BY id ASC";


    $stmt = $conn->prepare($sql);

    $stmt->execute([$conversationId]);


    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $messages[] = $row;
    }

    return $messages;

}



/*==================================================
=               SEND MESSAGE                       =
==================================================*/

function sendMessage(
    PDO $conn,
    $conversationId,
    $senderId,
    $sender,
    $message
)
{

    $sql = "INSERT INTO messages
            (conversation_id, sender_id, sender, message)
            VALUES (?,?,?,?)";


    $stmt = $conn->prepare($sql);


    return $stmt->execute([
        $conversationId,
        $senderId,
        $sender,
        $message
    ]);

}
function createConversation(PDO $conn, $propertyId, $tenantId)
{
    $stmt = $conn->prepare("
        SELECT user_id AS owner_id
        FROM housing
        WHERE id = ?
    ");

    $stmt->execute([$propertyId]);

    $ownerId = $stmt->fetchColumn();

    if (!$ownerId) {
        return false;
    }

    $stmt = $conn->prepare("
        SELECT id
        FROM conversations
        WHERE property_id = ?
        AND tenant_id = ?
        AND owner_id = ?
    ");

    $stmt->execute([
        $propertyId,
        $tenantId,
        $ownerId
    ]);

    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($conversation) {
        return $conversation['id'];
    }

    $stmt = $conn->prepare("
        INSERT INTO conversations
        (
            property_id,
            tenant_id,
            owner_id
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $propertyId,
        $tenantId,
        $ownerId
    ]);

    return $conn->lastInsertId();
}
?>