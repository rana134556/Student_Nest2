<?php

require "../config/auth.php";
require "../config/database.php";

require "data/message.php";

if(!isset($_GET['property']))
{
    header("Location: dashboard.php");
    exit;
}

$propertyId = (int)$_GET['property'];

$conversationId = createConversation(
    $conn,
    $propertyId,
    $_SESSION['user_id']
);

header("Location: messages.php?chat=".$conversationId);
exit;