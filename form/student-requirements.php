<?php

session_start();

require_once "../db.php";


// Save Student Requirements

if (

    $_SERVER["REQUEST_METHOD"] == "POST"

    &&

    isset($_POST['confirm'])

) {


    $fieldOfStudy = $_POST['fieldOfStudy'] ?? "";

    $studentGender = $_POST['studentGender'] ?? "";

    $studentMinAge = $_POST['studentMinAge'] ?? "";

    $studentMaxAge = $_POST['studentMaxAge'] ?? "";

    $studentSmoking = $_POST['studentSmoking'] ?? "";

    $studentRequirements = $_POST['studentRequirements'] ?? "";



    $propertyId = $_SESSION['property_id'] ?? 0;



    if ($propertyId == 0) {

        header("Location: property-details.php");

        exit();
    }



    $smokingAllowed = ($studentSmoking == "allowed") ? 1 : 0;

    // Update Student Requirements


    $query = mysqli_query(

        $connect,

        "UPDATE housing SET


            required_field_of_study = '$fieldOfStudy',


            gender = '$studentGender',


            min_age = '$studentMinAge',


            max_age = '$studentMaxAge',


            smoking_allowed = '$smokingAllowed',


            additional_requirements = '$studentRequirements'


        WHERE id = '$propertyId'"

    );



    if (!$query) {

        die("Update Error: " . mysqli_error($connect));
    }



    $_SESSION['completed'] = true;


    header("Location: success.php");

    exit();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Student Requirements</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

</head>

<body>

    <div class="container">

        <div class="form-box">

            <form method="POST">


                <!-- Progress Bar -->

                <div class="progress-container">

                    <div class="progress-text">

                        <span>Step 3 of 3</span>

                        <span>Student Requirements</span>

                    </div>

                    <div class="progress-bar">

                        <div class="progress progress-step3"></div>

                    </div>

                </div>


                <hr class="divider">
                <h1>

                    Student Requirements

                </h1>


                <p>

                    Specify your preferred student tenants.

                </p>


                <div class="section-title">

                    <h2>

                        Student Information

                    </h2>


                    <p>

                        Set the requirements for students who can rent this property.

                    </p>

                </div>



                <div class="input-group">

                    <label for="fieldOfStudy">

                        Required Field of Study

                    </label>


                    <input

                        type="text"

                        id="fieldOfStudy"

                        name="fieldOfStudy"

                        placeholder="Example: Engineering, Medicine, Any Student">


                </div>




                <div class="input-group">

                    <label for="studentGender">

                        Preferred Gender

                    </label>


                    <select

                        id="studentGender"

                        name="studentGender">


                        <option value="">

                            No Preference

                        </option>


                        <option value="Male">

                            Male

                        </option>


                        <option value="Female">

                            Female

                        </option>


                        <option value="Both">

                            Both

                        </option>


                    </select>


                </div>




                <div class="row">


                    <div class="input-group">


                        <label for="studentMinAge">

                            Minimum Age

                        </label>


                        <input

                            type="number"

                            id="studentMinAge"

                            name="studentMinAge"

                            min="16"

                            max="100">


                    </div>



                    <div class="input-group">


                        <label for="studentMaxAge">

                            Maximum Age

                        </label>


                        <input

                            type="number"

                            id="studentMaxAge"

                            name="studentMaxAge"

                            min="16"

                            max="100">


                    </div>


                </div>




                <div class="input-group">


                    <label>

                        Smoking

                    </label>


                    <div class="radio-group">


                        <label>

                            <input

                                type="radio"

                                name="studentSmoking"

                                value="allowed">


                            Allowed

                        </label>



                        <label>

                            <input

                                type="radio"

                                name="studentSmoking"

                                value="not-allowed">


                            Not Allowed

                        </label>



                    </div>


                </div>




                <div class="input-group">


                    <label for="studentRequirements">

                        Additional Requirements

                    </label>


                    <textarea

                        id="studentRequirements"

                        name="studentRequirements"

                        rows="4"

                        placeholder="Example: Quiet students only, no overnight guests."></textarea>


                </div>
                <div class="button-group">


                    <a href="property-info.php" class="back-btn">


                        <span>←</span>


                        Back


                    </a>



                    <button

                        type="submit"

                        name="confirm"

                        class="next-btn">


                        Confirm Data


                        <span>✓</span>


                    </button>



                </div>



            </form>


        </div>


    </div>


</body>


</html>