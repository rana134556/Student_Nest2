<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/notification.php";


/*==============================
Mark As Read
==============================*/

if(isset($_GET['read']))
{
    $notificationId = (int)$_GET['read'];

    markNotificationAsRead($conn, $notificationId);

    $link = $_GET['link'] ?? "notifications.php";

    header("Location: ".$link);

    exit;
}


/*==============================
Notifications
==============================*/

$notifications = getNotifications(
    $conn,
    $_SESSION['user_id']
);

?>

<section class="notifications-page">

<div class="container">

<div class="section-title">

<h2>Notifications</h2>

<p>Latest updates related to your account.</p>

</div>

<div class="notifications-list">

<?php if(!empty($notifications)): ?>

<?php foreach($notifications as $notification): ?>

<a

href="notifications.php?read=<?= $notification['id'] ?>&link=<?= urlencode($notification['link']) ?>"

class="notification-card <?= $notification['is_read'] ? 'read' : 'unread'; ?>">

<div class="notification-icon">

<?php

switch($notification['type']){

    case "booking":
        echo "📅";
        break;

    case "message":
        echo "💬";
        break;

    case "property":
        echo "🏠";
        break;

    default:
        echo "🔔";
}

?>

</div>

<div class="notification-content">

<h4>

<?= htmlspecialchars($notification['title']) ?>

</h4>

<small>

<?= date("d M Y - h:i A", strtotime($notification['date'])) ?>

</small>

</div>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="no-results">

<h3>No Notifications Yet</h3>

<p>You don't have any notifications.</p>

</div>

<?php endif; ?>

</div>

</div>

</section>

