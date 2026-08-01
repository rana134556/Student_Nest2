<?php

session_start();

require_once "../db.php";


// Get Property Data

$property = [];


if (isset($_GET['id'])) {


    $propertyId = intval($_GET['id']);


    $query = mysqli_query(

        $connect,

        "SELECT * 
         FROM housing 
         WHERE id = $propertyId"

    );


    if (mysqli_num_rows($query) > 0) {


        $property = mysqli_fetch_assoc($query);
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Property Information</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

</head>

<body>

    <div class="container">

        <div class="form-box">

            <form
                action="save-property-info.php"
                method="POST">


                <!-- Progress Bar -->

                <div class="progress-container">

                    <div class="progress-text">

                        <span>Step 2 of 3</span>

                        <span>Property Information</span>

                    </div>


                    <div class="progress-bar">

                        <div class="progress progress-step2"></div>

                    </div>

                </div>


                <hr class="divider">


                <h1>Add New Property</h1>


                <p>

                    Complete the remaining information about your property.

                </p>


                <div class="section-title">

                    <h2>Property Information</h2>

                    <p>

                        Provide additional information about your property before continuing.

                    </p>

                </div>
                <!-- Available Beds -->

                <div class="input-group">

                    <label for="availableBeds">

                        Available Beds

                    </label>


                    <select

                        id="availableBeds"

                        name="availableBeds"

                        required>


                        <option

                            value=""

                            disabled

                            <?php echo empty($property['available_slots']) ? "selected" : ""; ?>>

                            Select Number of Available Beds

                        </option>



                        <?php


                        for ($i = 1; $i <= 20; $i++) {


                        ?>


                            <option

                                value="<?php echo $i; ?>"

                                <?php

                                if (

                                    ($property['available_slots'] ?? '') == $i

                                ) {

                                    echo "selected";
                                }

                                ?>>

                                <?php echo $i; ?>

                            </option>


                        <?php


                        }


                        ?>


                    </select>


                </div>




                <!-- Security Deposit -->


                <div class="input-group">


                    <label>

                        Security Deposit

                    </label>



                    <div class="radio-group">



                        <label>


                            <input

                                type="radio"

                                name="deposit"

                                value="required"

                                <?php

                                if (

                                    ($property['security_deposit_required'] ?? '') == 1

                                ) {

                                    echo "checked";
                                }

                                ?>

                                required>


                            Required


                        </label>




                        <label>


                            <input

                                type="radio"

                                name="deposit"

                                value="not-required"

                                <?php

                                if (

                                    ($property['security_deposit_required'] ?? '') == 0

                                ) {

                                    echo "checked";
                                }

                                ?>>


                            Not Required


                        </label>



                    </div>


                </div>





                <!-- Deposit Amount -->


                <div class="input-group">


                    <label for="depositAmount">


                        Deposit Amount (EGP)


                    </label>



                    <input

                        type="number"

                        id="depositAmount"

                        name="depositAmount"

                        placeholder="Enter Deposit Amount"

                        min="0"

                        value="<?php echo htmlspecialchars($property['security_deposit_amount'] ?? ''); ?>">



                </div>
                <!-- Navigation Buttons -->

                <div class="button-group">


                    <a href="property-details.php" class="back-btn">


                        <span>←</span>


                        Back


                    </a>



                    <button

                        type="submit"

                        class="next-btn">


                        Next <span>→</span>


                    </button>


                </div>



            </form>


        </div>


    </div>


</body>


</html>