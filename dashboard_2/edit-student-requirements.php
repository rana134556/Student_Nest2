<?php

session_start();

require_once "../db.php";


/* Check User */

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



/* Fetch Student Requirements */


$studentQuery = mysqli_prepare(
    $connect,
    "SELECT 
        required_field_of_study,
        gender,
        min_age,
        max_age,
        smoking_allowed,
        additional_requirements

    FROM housing

    WHERE id = ?

    AND user_id = ?"
);



mysqli_stmt_bind_param(
    $studentQuery,
    "ii",
    $propertyId,
    $userId
);



mysqli_stmt_execute($studentQuery);



$studentResult = mysqli_stmt_get_result($studentQuery);



$studentData = mysqli_fetch_assoc($studentResult);



if (!$studentData) {

    header("Location: my-properties.php");
    exit();
}



$student = [

    "fieldOfStudy" => $studentData['required_field_of_study'] ?? "",

    "studentGender" => $studentData['gender'] ?? "Both",

    "studentMinAge" => $studentData['min_age'] ?? "",

    "studentMaxAge" => $studentData['max_age'] ?? "",

    "studentSmoking" => ($studentData['smoking_allowed'] ?? 0)
        ? "allowed"
        : "not-allowed",

    "studentRequirements" =>
    $studentData['additional_requirements'] ?? ""

];



/* Update Data */


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $fieldOfStudy = $_POST['fieldOfStudy'] ?? "";

    $studentGender = $_POST['studentGender'] ?? "Both";

    $minAge = $_POST['studentMinAge'] ?? null;

    $maxAge = $_POST['studentMaxAge'] ?? null;

    $smoking = $_POST['studentSmoking'] ?? "not-allowed";

    $requirements = $_POST['studentRequirements'] ?? "";



    $smokingAllowed = ($smoking == "allowed")
        ? 1
        : 0;



    $updateQuery = mysqli_prepare(
        $connect,
        "UPDATE housing SET

        required_field_of_study = ?,

        gender = ?,

        min_age = ?,

        max_age = ?,

        smoking_allowed = ?,

        additional_requirements = ?

        WHERE id = ?

        AND user_id = ?"
    );



    mysqli_stmt_bind_param(
        $updateQuery,
        "ssiiisii",
        $fieldOfStudy,
        $studentGender,
        $minAge,
        $maxAge,
        $smokingAllowed,
        $requirements,
        $propertyId,
        $userId
    );



    mysqli_stmt_execute($updateQuery);



    header(
        "Location:my-properties.php?id=$propertyId"
    );

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
        Edit Student Requirements
    </title>


    <link rel="stylesheet"
        href="../css/malaz.css">


</head>



<body>



    <div class="container">


        <div class="form-box">



            <form action="" method="POST">



                <!-- Progress -->

                <div class="progress-container">


                    <div class="progress-text">


                        <span>

                            Step 3 of 3

                        </span>



                        <span>

                            Edit Student Requirements

                        </span>


                    </div>



                    <div class="progress-bar">


                        <div class="progress progress-step3"></div>


                    </div>



                </div>




                <hr class="divider">



                <h1>

                    Edit Property

                </h1>



                <p>

                    Update your preferred student requirements.

                </p>





                <!-- Student Requirements -->

                <div class="section-title">


                    <h2>

                        Student Requirements

                    </h2>


                    <p>

                        Update the requirements for students who can rent this property.

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

                        placeholder="Example: Engineering, Medicine, Any Student"

                        value="<?php echo htmlspecialchars($student['fieldOfStudy']); ?>">



                </div>





                <div class="input-group">


                    <label for="studentGender">

                        Preferred Gender

                    </label>



                    <select

                        id="studentGender"

                        name="studentGender">



                        <option

                            value="Both"

                            <?php

                            echo ($student['studentGender'] == "Both")
                                ? "selected"
                                : "";

                            ?>>

                            No Preference

                        </option>




                        <option

                            value="Male"

                            <?php

                            echo ($student['studentGender'] == "Male")
                                ? "selected"
                                : "";

                            ?>>

                            Male

                        </option>




                        <option

                            value="Female"

                            <?php

                            echo ($student['studentGender'] == "Female")
                                ? "selected"
                                : "";

                            ?>>

                            Female

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

                            max="100"

                            value="<?php echo htmlspecialchars($student['studentMinAge']); ?>">



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

                            max="100"

                            value="<?php echo htmlspecialchars($student['studentMaxAge']); ?>">



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

                                value="allowed"

                                <?php

                                echo ($student['studentSmoking'] == "allowed")
                                    ? "checked"
                                    : "";

                                ?>>

                            Allowed


                        </label>





                        <label>


                            <input

                                type="radio"

                                name="studentSmoking"

                                value="not-allowed"

                                <?php

                                echo ($student['studentSmoking'] == "not-allowed")
                                    ? "checked"
                                    : "";

                                ?>>

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

                        placeholder="Example: Quiet students only, no overnight guests."><?php echo htmlspecialchars($student['studentRequirements']); ?></textarea>



                </div>





                <!-- Buttons -->

                <div class="button-group">



                    <a

                        href="edit-property-info.php?id=<?php echo $propertyId; ?>"

                        class="back-btn">


                        <span>

                            ←

                        </span>


                        Back


                    </a>





                    <button

                        type="submit"

                        class="next-btn">


                        Update Property

                        <span>

                            ✓

                        </span>


                    </button>



                </div>





            </form>



        </div>



    </div>



</body>


</html>