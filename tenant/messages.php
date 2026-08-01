<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/message.php";

$currentChat = isset($_GET['chat'])
    ? (int)$_GET['chat']
    : 0;
/*==========================
Conversations
==========================*/

$conversations = getConversations($conn, $_SESSION['user_id']);

if ($currentChat == 0 && !empty($conversations)) {
    $currentChat = $conversations[0]['id'];
}

/*==========================
Send Message
==========================*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = trim($_POST['message']);

    if (!empty($message)) {

        sendMessage(
                $conn,
                $currentChat,
                $_SESSION['user_id'],
                "tenant",
                $message
                    );
        header("Location: messages.php?chat=".$currentChat);
        exit;
    }
}

/*==========================
Messages
==========================*/

$messages = getMessages($conn, $currentChat);

?>

<section class="messages-page">

<div class="container">

<div class="chat-layout">

<div class="chat-sidebar">

<h3>Conversations</h3>

<?php foreach($conversations as $chat): ?>

<a href="?chat=<?= $chat['id'] ?>" class="conversation">

<h4><?= htmlspecialchars($chat['owner_name']) ?></h4>

<small><?= htmlspecialchars($chat['property_title']) ?></small>

<p><?= htmlspecialchars($chat['last_message']) ?></p>

</a>

<?php endforeach; ?>

</div>



<div class="chat-box">

<div class="chat-header">

<h3>Chat</h3>

</div>

<div class="chat-body">

<?php foreach($messages as $message): ?>

<div class="message <?= $message['sender'] ?>">

<?= htmlspecialchars($message['message']) ?>

</div>

<?php endforeach; ?>

</div>

<div class="chat-input">

<form method="POST">

<input
type="text"
name="message"
placeholder="Type your message..."
required>

<button type="submit">

Send

</button>

</form>

</div>

</div>

</div>

</div>

</section>

