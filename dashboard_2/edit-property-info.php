<?php

session_start();


require_once "../db.php";



$page = "properties";



if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();
}



$userId = $_SESSION['user_id'];



$propertyId = $_GET['id'] ?? 0;



if (!$propertyId) {

    header("Location: my-properties.php");

    exit();
}



/* Fetch Property */


$propertyQuery = "

SELECT *

FROM housing

WHERE id = $propertyId

AND user_id = $userId

";


$propertyResult = mysqli_query($connect, $propertyQuery);



$property = mysqli_fetch_assoc($propertyResult);



if (!$property) {

    header("Location: my-properties.php");

    exit();
}




/* Handle Form Submit */


if ($_SERVER["REQUEST_METHOD"] == "POST") {



    $availableBeds = $_POST['availableBeds'] ?? "";

    $deposit = $_POST['deposit'] ?? "";

    $depositAmount = $_POST['depositAmount'] ?? "";



    $updateQuery = "

    UPDATE housing

    SET

    available_slots = '$availableBeds',

    security_deposit_required = '" . ($deposit == "required" ? 1 : 0) . "',

    security_deposit_amount = '$depositAmount'

    WHERE id = $propertyId

    AND user_id = $userId

    ";



    mysqli_query($connect, $updateQuery);



    header("Location: edit-student-requirements.php?id=$propertyId");


    exit();
}
?>

<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta name="viewport"

        content="width=device-width, initial-scale=1.0">


    <title>

        Edit Property Information

    </title>



    <link rel="stylesheet"

        href="../css/malaz.css">



</head>



<body>



    <div class="container">



        <div class="form-box">



            <form

                action=""

                method="POST">





                <div class="progress-container">



                    <div class="progress-text">



                        <span>

                            Step 2 of 3

                        </span>



                        <span>

                            Edit Property Information

                        </span>



                    </div>




                    <div class="progress-bar">



                        <div class="progress progress-step2"></div>



                    </div>



                </div>





                <hr class="divider">





                <h1>

                    Edit Property

                </h1>




                <p>

                    Update the remaining information about your property.

                </p>





                <div class="section-title">


                    <h2>

                        Property Information

                    </h2>


                    <p>

                        Update additional details about your property.

                    </p>


                </div>





                <div class="input-group">



                    <label for="availableBeds">

                        Available Beds

                    </label>




                    <select

                        id="availableBeds"

                        name="availableBeds"

                        required>




                        <option value=""

                            disabled>

                            Select Number of Available Beds

                        </option>




                        <?php


                        for ($i = 1; $i <= 20; $i++) {



                        ?>



                            <option

                                value="<?php echo $i; ?>"

                                <?php


                                echo (($property['available_slots'] ?? '') == $i)

                                    ? "selected"

                                    : "";


                                ?>>



                                <?php echo $i; ?>


                            </option>




                        <?php


                        }


                        ?>



                    </select>



                </div>
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


                                echo (($property['security_deposit_required'] ?? 0) == 1)

                                    ? "checked"

                                    : "";


                                ?>>


                            Required



                        </label>





                        <label>



                            <input

                                type="radio"

                                name="deposit"

                                value="not-required"



                                <?php


                                echo (($property['security_deposit_required'] ?? 0) == 0)

                                    ? "checked"

                                    : "";


                                ?>>


                            Not Required



                        </label>



                    </div>



                </div>







                <div class="input-group">


                    <label for="depositAmount">


                        Deposit Amount (EGP)


                    </label>




                    <input

                        type="number"

                        id="depositAmount"

                        name="depositAmount"

                        min="0"

                        value="<?php echo htmlspecialchars($property['security_deposit_amount'] ?? ''); ?>">



                </div>






                <div class="button-group">



                    <a

                        href="edit-property-details.php?id=<?php echo $propertyId; ?>"

                        class="back-btn">


                        <span>

                            ←

                        </span>


                        Back



                    </a>





                    <button

                        type="submit"

                        class="next-btn">



                        Next

                        <span>

                            →

                        </span>



                    </button>




                </div>





            </form>



        </div>



    </div>



</body>



</html>