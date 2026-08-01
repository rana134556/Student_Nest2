<?php


session_start();

require_once "../db.php";

/* =========================
   Current Landlord
========================= */

$currentUserId = $_SESSION['user_id'] ?? 0;
$selectedConversationId = (int)($_GET['chat'] ?? 0);
if (!$currentUserId) {
    die("Landlord is not logged in.");
}

/* =========================
   Get Conversation
========================= */

$conversationId = (int)($_GET['chat'] ?? $_POST['conversation_id'] ?? 0);

/* If page opened from property */
if (   $conversationId === 0 &&
    isset($_GET['property']) &&
    isset($_GET['tenant'])) {

    $propertyId = (int)$_GET['property'];

    $query = mysqli_prepare(
        $connect,
        "SELECT id
FROM conversations
WHERE property_id = ?
AND tenant_id = ?
AND owner_id = ?
LIMIT 1"
    );

    $tenantId = (int)$_GET['tenant'];

mysqli_stmt_bind_param(
    $query,
    "iii",
    $propertyId,
    $tenantId,
    $currentUserId
);

    mysqli_stmt_execute($query);

    $result = mysqli_stmt_get_result($query);

    $row = mysqli_fetch_assoc($result);

    $conversationId = (int)($row['id'] ?? 0);
   $selectedConversationId = $conversationId;
}

/* =========================
   Send Message
========================= */

if (isset($_POST['send'])) {

    $message = trim($_POST['message'] ?? '');

    if (!empty($message) && $conversationId > 0) {

        $senderId = $currentUserId;
        $sender = "owner";

        $query = mysqli_prepare(
            $connect,
            "INSERT INTO messages
            (conversation_id, sender_id, sender, message)
            VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $query,
            "iiss",
            $conversationId,
            $senderId,
            $sender,
            $message
        );

        if (!mysqli_stmt_execute($query)) {
            die("Message Error: " . mysqli_error($connect));
        }

       header("Location: messages.php?chat=" . $conversationId);
exit();
    }
}

$page = "messages";






/* Fetch Conversations */


$conversationsQuery = mysqli_prepare(

    $connect,

    "SELECT

        conversations.id,

        users.name AS student_name,

        users.image AS student_image,

        housing.title AS property_name


    FROM conversations


    INNER JOIN users

    ON conversations.tenant_id = users.id


    INNER JOIN housing

    ON conversations.property_id = housing.id


    WHERE conversations.owner_id = ?"

);



mysqli_stmt_bind_param(

   $conversationsQuery,

    "i",

    $currentUserId

);



mysqli_stmt_execute($conversationsQuery);



$conversationsResult = mysqli_stmt_get_result($conversationsQuery);



$conversations = [];



while ($row = mysqli_fetch_assoc($conversationsResult)) {


    $conversations[] = [


        "id" => $row['id'],


        "studentName" => $row['student_name'],


        "studentImage" => $row['student_image'],


        "propertyName" => $row['property_name']


    ];
}

/* Fetch Last Message For Each Conversation */
$selectedStudentName = "";
$selectedPropertyName = "";

foreach ($conversations as &$conversation) {


    $conversationDbId=(int)$conversation['id'];

if ($conversationDbId === $selectedConversationId) {
    $selectedStudentName = $conversation['studentName'];
    $selectedPropertyName = $conversation['propertyName'];
}

    $messageQuery = mysqli_prepare(

        $connect,

        "SELECT

            message,

            created_at


        FROM messages


        WHERE conversation_id = ?


        ORDER BY created_at DESC


        LIMIT 1"

    );



    mysqli_stmt_bind_param(

        $messageQuery,

        "i",

        $conversationDbId

    );



    mysqli_stmt_execute($messageQuery);



    $messageResult = mysqli_stmt_get_result($messageQuery);



    $lastMessage = mysqli_fetch_assoc($messageResult);



    $conversation['lastMessage'] =

        $lastMessage['message'] ?? "";



    $conversation['time'] =

        $lastMessage['created_at'] ?? "";
}


unset($conversation);


?>



<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta name="viewport"

        content="width=device-width, initial-scale=1.0">



    <title>

        Messages

    </title>



    <link rel="stylesheet"

        href="../css/dashboard.css">



    <link rel="stylesheet"

        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>



<body>



    <?php include "navbar.php"; ?>



    <div class="dashboard-container">



        <div class="messages-page">



            <!-- Left Side -->


            <div class="conversations-panel">



                <h2>

                    Messages

                </h2>




                <div class="conversation-list">



                  <?php foreach ($conversations as $conversation) { ?>

    <a
        href="messages.php?chat=<?php echo $conversation['id']; ?>"
        class="conversation-item"
        style="text-decoration: none; color: inherit;"
    >


                            <div class="conversation-image">


                                <img

                                    src="../<?php echo htmlspecialchars($conversation['studentImage'] ?? 'default.jpg'); ?>"

                                    alt="Student Image">


                            </div>




                            <div class="conversation-info">



                                <h3>

                                    <?php

                                    echo htmlspecialchars(

                                        $conversation['studentName']

                                    );

                                    ?>

                                </h3>




                                <p class="property-name">


                                    <?php

                                    echo htmlspecialchars(

                                        $conversation['propertyName']

                                    );

                                    ?>


                                </p>




                                <p class="last-message">


                                    <?php

                                    echo htmlspecialchars(

                                        $conversation['lastMessage']

                                    );

                                    ?>


                                </p>



                            </div>




                            <span class="message-time">


                                <?php

                                echo htmlspecialchars(

                                    $conversation['time']

                                );

                                ?>


                            </span>



                       

</a>

                    <?php } ?>



                </div>



            </div>
            <!-- Right Side -->


           <div class="chat-panel">

    <div class="chat-header">
        <div>
            <h2>
    <?php echo htmlspecialchars($selectedStudentName); ?>
</h2>

<p>
    <?php echo htmlspecialchars($selectedPropertyName); ?>
</p>
        </div>

        <a href="#" class="call-btn">
            <i class="fa-solid fa-phone"></i>
        </a>
    </div>

    <div class="messages-container">

        <?php
        $chatMessages = [];

        $query = mysqli_prepare(
            $connect,
            "SELECT sender, message, created_at AS time
             FROM messages
             WHERE conversation_id = ?
             ORDER BY created_at ASC"
        );

        mysqli_stmt_bind_param(
            $query,
            "i",
            $selectedConversationId
        );

        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        while ($row = mysqli_fetch_assoc($result)) {
            $chatMessages[] = $row;
        }

        foreach ($chatMessages as $message) {
        ?>

            <div class="message <?php echo ($message['sender'] == 'owner') ? 'sent' : 'received'; ?>">

                <p>
                    <?php echo htmlspecialchars($message['message']); ?>
                </p>

                <span>
                    <?php echo htmlspecialchars($message['time']); ?>
                </span>

            </div>

        <?php } ?>

    </div>

    <form method="POST">

        <div class="send-message">

            <input
                type="text"
                name="message"
                placeholder="Type your message..."
            >

            <input
                type="hidden"
                name="conversation_id"
                value="<?php echo $selectedConversationId; ?>"
            >

            <button type="submit" name="send">
                <i class="fa-solid fa-paper-plane"></i>
                Send
            </button>

        </div>

    </form>

</div>


        </div>




    </div>



</body>


</html>