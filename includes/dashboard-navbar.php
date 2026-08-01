<nav class="tenant-navbar">

    <div class="tenant-nav-container">

        <a href="../home.php" class="tenant-logo">
            <i class="fa-solid fa-house"></i>
            <span class="logo-dark">Student</span>
            <span class="logo-color">Nest</span>
        </a>

        <!-- Hamburger -->
        <button class="menu-toggle">
            <i class="fa-solid fa-bars"></i>
        </button>

        <ul class="tenant-links">

            <li>
                <a href="../home.php">
                    <i class="fa-solid fa-house"></i>
                    Home
                </a>
            </li>

            <li>
                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="favorites.php">
                    <i class="fa-solid fa-heart"></i>
                    Favorites
                </a>
            </li>

            <li>
                <a href="bookings.php">
                    <i class="fa-solid fa-calendar"></i>
                    Bookings
                </a>
            </li>

            <li>
                <a href="messages.php">
                    <i class="fa-solid fa-message"></i>
                    Messages
                </a>
            </li>

            <li>
                <a href="notifications.php">
                    <i class="fa-solid fa-bell"></i>
                    Notifications
                </a>
            </li>

            <li>
                <a href="profile.php">
                    <i class="fa-solid fa-user"></i>
                    Profile
                </a>
            </li>

            <li>
                <a href="logout.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </li>

        </ul>

    </div>

</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.querySelector(".menu-toggle");
    const menu = document.querySelector(".tenant-links");

    btn.addEventListener("click", function () {
        menu.classList.toggle("active");
    });

});
</script>
