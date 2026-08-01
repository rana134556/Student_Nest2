<?php

session_start();


// Add Another Property

if (isset($_GET['new'])) {


    unset($_SESSION['property_id']);

    unset($_SESSION['completed']);


    header("Location: property-details.php");

    exit();
}



// Dashboard

if (isset($_GET['dashboard'])) {


    header("Location: ../dashboard_2/dashboard.php");

    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Property Submitted</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

</head>

<body>

    <div class="container">

        <div class="form-box success-box">
            <div class="success-icon">

                ✅

            </div>



            <h1>

                Property Submitted Successfully

            </h1>



            <p>

                Your property has been added successfully.

                You can now manage your property, edit its information, view bookings and messages from your dashboard.

            </p>



            <!-- Navigation Buttons -->


            <div class="button-group">


                <a

                    href="success.php?new=1"

                    class="back-btn">


                    Add Another Property


                </a>




                <a

                    href="success.php?dashboard=1"

                    class="next-btn">


                    Go to Dashboard


                    <span>→</span>


                </a>



            </div>



        </div>


    </div>


</body>


</html>