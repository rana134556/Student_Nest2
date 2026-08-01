<?php

$currentPage = $page ?? "";

?>

<nav class="dashboard-navbar">


    <div class="navbar-logo">
 <i class="fa-solid fa-house"></i>
        <span class="logo-dark">
            Student
        </span>

        <span class="logo-primary">
            Nest
        </span>

       

    </div>



    <div class="navbar-links">


          <a href="../home.php"
            class="<?php echo ($currentPage == 'home') ? 'active' : ''; ?>">

           Home

        </a>


        <a href="dashboard.php"
            class="<?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">

            Dashboard

        </a>



        <a href="my-properties.php"
            class="<?php echo ($currentPage == 'properties') ? 'active' : ''; ?>">

            My Properties

        </a>



        <a href="bookings.php"
            class="<?php echo ($currentPage == 'bookings') ? 'active' : ''; ?>">

            Bookings

        </a>



        <a href="messages.php"
            class="<?php echo ($currentPage == 'messages') ? 'active' : ''; ?>">

            Messages

        </a>




        <a href="profile.php"
            class="<?php echo ($currentPage == 'profile') ? 'active' : ''; ?>">

            Profile

        </a>


    </div>
    

     <div class="profile-actions">


                            
                                <a href="../logout.php" class="action-btn logout-btn">


                                        <i class="fa-solid fa-right-from-bracket"></i>


                                        Logout


                                </a>



                        </div>


</nav>