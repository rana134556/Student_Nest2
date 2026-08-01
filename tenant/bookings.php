<?php

require "../config/auth.php";
require "../config/database.php";
require "../constants.php";
$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/booking.php";

/*=========================
Database
=========================*/

$bookings = getBookings(
    $conn,
    $_SESSION['user_id']
);

?>

<section class="bookings-page">

    <div class="container">

        <div class="section-title">

            <h2>My Bookings</h2>

            <p>All your booking requests.</p>

        </div>

        <table class="booking-table">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Property</th>

                    <th>Location</th>

                    <th>Price</th>

                    <th>Date</th>

                    <th>Status</th>

                    <th>Details</th>

                </tr>

            </thead>

            <tbody>

            <?php if(!empty($bookings)): ?>

                <?php foreach($bookings as $booking): ?>

                    <tr>

                        <td>

                            <?= $booking['id'] ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($booking['title']) ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($booking['location']) ?>

                        </td>

                        <td>

                            <?= number_format($booking['price']) ?> EGP

                        </td>

                        <td>

                            <?= !empty($booking['booking_date'])
                                ? date("d M Y", strtotime($booking['booking_date']))
                                : "-" ?>

                        </td>

                        <td>

                            <span class="status <?= strtolower($booking['status']) ?>">

                                <?= htmlspecialchars($booking['status']) ?>

                            </span>

                        </td>

                        <td>

                            <a
                                href="property_details.php?id=<?= $booking['property_id'] ?>"
                                class="details-btn">

                                View

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7" style="text-align:center;padding:25px;">

                        No bookings found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>
<?php if(isset($_SESSION['message'])): ?>

<div id="toast" class="toast-message">
    <i class="fa-solid fa-circle-check"></i>
    <span><?= $_SESSION['message']; ?></span>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toast = document.getElementById("toast");

    if (toast) {

        toast.classList.add("show");

        setTimeout(function () {
            toast.classList.remove("show");

            setTimeout(function () {
                toast.remove();
            }, 500);

        }, 3000);

    }

});
</script>

<?php unset($_SESSION['message']); ?>

<?php endif; ?>
</section>

<?php

// require ROOT_PATH . "includes/footer.php";

?>