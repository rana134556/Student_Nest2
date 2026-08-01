<?php

session_start();

require "../db.php";
require "../includes/header.php";
// require "navbar.php";
// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];


// =====================================
// Get User Data
// =====================================

$userQuery = mysqli_prepare(
    $connect,
    "SELECT name, email, phone, image
     FROM users
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $userQuery,
    "i",
    $userId
);

mysqli_stmt_execute($userQuery);

$userResult = mysqli_stmt_get_result($userQuery);

$user = mysqli_fetch_assoc($userResult);


if (!$user) {
    die("User not found.");
}


// =====================================
// Update Profile
// =====================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Keep old image if no new image is uploaded
    $imageName = $user['image'] ?? '';


    // Upload new image
    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {

        $imgTmpName = $_FILES['image']['tmp_name'];
        $imgOriginalName = $_FILES['image']['name'];

        $imageName =
            time() . "_" .
            uniqid() . "_" .
            basename($imgOriginalName);

        $uploadPath = "../" . $imageName;


        if (!move_uploaded_file($imgTmpName, $uploadPath)) {

            die("Error: Failed to upload image.");
        }
    }


    // Update database
    $updateQuery = mysqli_prepare(
        $connect,
        "UPDATE users
         SET name = ?,
             email = ?,
             phone = ?,
             image = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $updateQuery,
        "ssssi",
        $name,
        $email,
        $phone,
        $imageName,
        $userId
    );


    if (!mysqli_stmt_execute($updateQuery)) {

        die(
            "Update Error: " .
            mysqli_stmt_error($updateQuery)
        );
    }


    // Update session name
    $_SESSION['name'] = $name;


    header("Location: profile.php");
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Profile</title>
     <link rel="stylesheet" href="../css/dashboard.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> -->

    <link
        rel="stylesheet"
        href="../assest/css/tenant.css">
         <link
        rel="stylesheet"
        href="../assest/css/style.css">
        <link rel="stylesheet" href="../css/navbar.css">
        


</head>

<body>

<?php include"navbar.php";?>
<section class="edit-profile">

    <div class="container">

        <div class="profile-form">

            <h2>Edit Profile</h2>


            <form
                method="POST"
                enctype="multipart/form-data">


                <!-- Profile Picture -->

                <div class="form-group">

                    <label>Profile Picture</label>


                    <?php if (!empty($user['image'])): ?>

                        <img
                            src="../images/<?php echo htmlspecialchars(basename($user['image'])); ?>"
                            alt="Profile Picture"
                            style="
                                width: 100px;
                                height: 100px;
                                object-fit: cover;
                                border-radius: 50%;
                                display: block;
                                margin-bottom: 10px;
                            ">

                    <?php endif; ?>


                    <input
                        type="file"
                        name="image"
                        accept="image/*">

                </div>



                <!-- Full Name -->

                <div class="form-group">

                    <label>Full Name</label>


                    <input
                        type="text"
                        name="full_name"
                        value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                        required>

                </div>



                <!-- Email -->

                <div class="form-group">

                    <label>Email</label>


                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                        required>

                </div>



                <!-- Phone -->

                <div class="form-group">

                    <label>Phone Number</label>


                    <input
                        type="text"
                        name="phone"
                        value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                        required>

                </div>



                <button
                    class="main-btn"
                    type="submit">

                    Save Changes

                </button>


            </form>

        </div>

    </div>

</section>


</body>

</html>
