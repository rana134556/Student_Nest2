<?php

session_start();

require_once "../db.php";


// Check Login
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();
}


$userId = $_SESSION['user_id'];


// Get Property ID
$propertyId = intval($_GET['id'] ?? 0);

if (!$propertyId) {

    header("Location:../dashboard_2/my-properties.php");
    exit();
}


// Check Property Belongs To User
$propertyQuery = mysqli_prepare(
    $connect,
    "SELECT id
     FROM housing
     WHERE id = ?
     AND user_id = ?"
);

mysqli_stmt_bind_param(
    $propertyQuery,
    "ii",
    $propertyId,
    $userId
);

mysqli_stmt_execute($propertyQuery);

$propertyResult = mysqli_stmt_get_result($propertyQuery);

$property = mysqli_fetch_assoc($propertyResult);


if (!$property) {

    header("Location:../dashboard_2/my-properties.php");
    exit();
}


// Handle Delete
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* --------------------------------
       Get Images Before Delete
    -------------------------------- */

    $imageQuery = mysqli_prepare(
        $connect,
        "SELECT image
         FROM property_images
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $imageQuery,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($imageQuery);

    $imageResult = mysqli_stmt_get_result($imageQuery);

    $images = [];


    while ($row = mysqli_fetch_assoc($imageResult)) {

        $images[] = $row['image'];
    }



    /* --------------------------------
       Delete Messages
    -------------------------------- */

    $deleteMessages = mysqli_prepare(
        $connect,
        "DELETE FROM messages
         WHERE conversation_id IN (
             SELECT id
             FROM conversations
             WHERE property_id = ?
         )"
    );

    mysqli_stmt_bind_param(
        $deleteMessages,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteMessages);



    /* --------------------------------
       Delete Conversations
    -------------------------------- */

    $deleteConversations = mysqli_prepare(
        $connect,
        "DELETE FROM conversations
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteConversations,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteConversations);



    /* --------------------------------
       Delete Bookings
    -------------------------------- */

    $deleteBookings = mysqli_prepare(
        $connect,
        "DELETE FROM bookings
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteBookings,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteBookings);



    /* --------------------------------
       Delete Reviews
    -------------------------------- */

    $deleteReviews = mysqli_prepare(
        $connect,
        "DELETE FROM reviews
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteReviews,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteReviews);



    /* --------------------------------
       Delete Property Facilities
    -------------------------------- */

    $deleteFacilities = mysqli_prepare(
        $connect,
        "DELETE FROM property_facilities
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteFacilities,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteFacilities);



    /* --------------------------------
       Delete Property Images From DB
    -------------------------------- */

    $deleteImages = mysqli_prepare(
        $connect,
        "DELETE FROM property_images
         WHERE property_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteImages,
        "i",
        $propertyId
    );

    mysqli_stmt_execute($deleteImages);



    /* --------------------------------
       Delete Image Files
    -------------------------------- */

    foreach ($images as $image) {

        $image = basename($image);

        $imagePath = "../images/" . $image;

        if (file_exists($imagePath)) {

            unlink($imagePath);
        }
    }



    /* --------------------------------
       Delete Property
    -------------------------------- */

    $deleteProperty = mysqli_prepare(
        $connect,
        "DELETE FROM housing
         WHERE id = ?
         AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $deleteProperty,
        "ii",
        $propertyId,
        $userId
    );


    if (!mysqli_stmt_execute($deleteProperty)) {

        die(
            "Delete Error: " .
            mysqli_stmt_error($deleteProperty)
        );
    }


    /* --------------------------------
       Success
    -------------------------------- */

    header("Location:../dashboard_2/my-properties.php?deleted=1");
    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Delete Property</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

    <style>

        .delete-box {
            text-align: center;
            padding: 40px;
        }

        .delete-box h1 {
            margin-bottom: 15px;
        }

        .delete-box p {
            margin-bottom: 30px;
            color: #666;
        }

        .delete-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .cancel-btn,
        .delete-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }

        .cancel-btn {
            background: #ddd;
            color: #333;
        }

        .delete-btn {
            background: #c94c4c;
            color: white;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="form-box delete-box">

            <h1>Delete Property</h1>

            <p>
                Are you sure you want to delete this property?
                <br>
                This action cannot be undone.
            </p>


            <div class="delete-buttons">

                <a
                    href="../dashboard_2/my-properties.php"
                    class="cancel-btn">

                    Cancel

                </a>


                <form
                    method="POST"
                    action="delete-property.php?id=<?php echo $propertyId; ?>">

                    <button
                        type="submit"
                        class="delete-btn">

                        Yes, Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>