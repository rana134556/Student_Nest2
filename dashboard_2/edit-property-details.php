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

SELECT

housing.*,

categories.name AS category_name

FROM housing

LEFT JOIN categories

ON housing.category_id = categories.id

WHERE housing.id = $propertyId

AND housing.user_id = $userId

";

$propertyResult = mysqli_query($connect, $propertyQuery);

$property = mysqli_fetch_assoc($propertyResult);

if (!$property) {

    header("Location: my-properties.php");
    exit();
}


/* Fetch Existing Images */

$existingImages = [];

$imageQuery = mysqli_prepare(
    $connect,
    "SELECT image, is_main
     FROM property_images
     WHERE property_id = ?
     ORDER BY is_main DESC, id ASC"
);

mysqli_stmt_bind_param(
    $imageQuery,
    "i",
    $propertyId
);

mysqli_stmt_execute($imageQuery);

$imageResult = mysqli_stmt_get_result($imageQuery);

while ($image = mysqli_fetch_assoc($imageResult)) {

    $existingImages[] = $image;
}


/* Handle Form Submit */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $propertyType = $_POST['propertyType'] ?? "";

    $monthlyRent = $_POST['monthlyRent'] ?? "";

    $governorate = $_POST['governorate'] ?? "";

    $city = $_POST['city'] ?? "";

    $address = $_POST['address'] ?? "";

    $googleMap = $_POST['googleMap'] ?? "";

    $description = $_POST['description'] ?? "";


    /* Get Category ID */

    $categoryQuery = "

    SELECT id

    FROM categories

    WHERE name = '$propertyType'

    LIMIT 1

    ";

    $categoryResult = mysqli_query($connect, $categoryQuery);

    $category = mysqli_fetch_assoc($categoryResult);

    $categoryId = $category['id'] ?? $property['category_id'];


    /* Update Housing */

    $updateQuery = "

    UPDATE housing

    SET

    category_id = '$categoryId',

    price = '$monthlyRent',

    governorate = '$governorate',

    city = '$city',

    address = '$address',

    location_link = '$googleMap',

    description = '$description'

    WHERE id = $propertyId

    AND user_id = $userId

    ";

    mysqli_query($connect, $updateQuery);


    /* Update Images */

    if (!empty($_FILES['propertyImages']['name'][0])) {


        /* Get Old Images */

        $oldImagesQuery = mysqli_prepare(
            $connect,
            "SELECT image
             FROM property_images
             WHERE property_id = ?"
        );

        mysqli_stmt_bind_param(
            $oldImagesQuery,
            "i",
            $propertyId
        );

        mysqli_stmt_execute($oldImagesQuery);

        $oldImagesResult = mysqli_stmt_get_result($oldImagesQuery);

        $oldImages = [];

        while ($row = mysqli_fetch_assoc($oldImagesResult)) {

            $oldImages[] = $row['image'];
        }


        /* Delete Old Images From Database */

        $deleteOldImages = mysqli_prepare(
            $connect,
            "DELETE FROM property_images
             WHERE property_id = ?"
        );

        mysqli_stmt_bind_param(
            $deleteOldImages,
            "i",
            $propertyId
        );

        mysqli_stmt_execute($deleteOldImages);


        /* Delete Old Image Files */

        foreach ($oldImages as $oldImage) {

            $oldImagePath = "../" . basename($oldImage);

            if (file_exists($oldImagePath)) {

                unlink($oldImagePath);
            }
        }


        
/* Upload New Images */

$firstImage = true;

foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmpName) {

    if (empty($tmpName)) {
        continue;
    }

    $imageName = uniqid() . "_" . basename(
        $_FILES['propertyImages']['name'][$key]
    );

    $uploadPath = "../" . $imageName;

    if (move_uploaded_file($tmpName, $uploadPath)) {

        
        $isMain = $firstImage ? 1 : 0;

        $imageInsert = mysqli_prepare(
            $connect,
            "INSERT INTO property_images
            (property_id, image, is_main)
            VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $imageInsert,
            "isi",
            $propertyId,
            $imageName,
            $isMain
        );

        mysqli_stmt_execute($imageInsert);

        
        $firstImage = false;
    }
}
    }


    /* Success */

    header("Location: edit-property-info.php?id=$propertyId");

    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Property Details</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

    <style>

        .existing-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .existing-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #eee;
            background: #fff;
        }

        .existing-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .main-image-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e05f97;
            color: #fff;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 768px) {

            .existing-images {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 480px) {

            .existing-images {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <div class="container">

        <div class="form-box">

            <form
                action=""
                method="POST"
                enctype="multipart/form-data">


                <!-- Progress -->

                <div class="progress-container">

                    <div class="progress-text">

                        <span>
                            Step 1 of 3
                        </span>

                        <span>
                            Edit Property Details
                        </span>

                    </div>

                    <div class="progress-bar">

                        <div class="progress progress-step1"></div>

                    </div>

                </div>


                <hr class="divider">


                <h1>
                    Edit Property
                </h1>

                <p>
                    Update your property information.
                </p>


                <!-- Property Information -->

                <div class="section-title">

                    <h2>
                        Property Information
                    </h2>

                    <p>
                        Update the basic information about your property.
                    </p>

                </div>


                <!-- Property Type -->

                <div class="input-group">

                    <label for="propertyType">
                        Property Type
                    </label>

                    <select
                        id="propertyType"
                        name="propertyType"
                        required>

                        <option value="" disabled>
                            Select Property Type
                        </option>

                        <option
                            value="Single Room"
                            <?php
                            echo ($property['category_name'] == "Single Room")
                                ? "selected"
                                : "";
                            ?>>
                            Single Room
                        </option>

                        <option
                            value="Double Room"
                            <?php
                            echo ($property['category_name'] == "Double Room")
                                ? "selected"
                                : "";
                            ?>>
                            Double Room
                        </option>

                        <option
                            value="Empty Apartment"
                            <?php
                            echo ($property['category_name'] == "Empty Apartment")
                                ? "selected"
                                : "";
                            ?>>
                            Empty Apartment
                        </option>

                    </select>

                </div>


                <!-- Monthly Rent -->

                <div class="input-group">

                    <label for="monthlyRent">
                        Monthly Rent (EGP)
                    </label>

                    <input
                        type="number"
                        id="monthlyRent"
                        name="monthlyRent"
                        min="500"
                        value="<?php echo htmlspecialchars($property['price']); ?>"
                        required>

                </div>


                <!-- Location -->

                <div class="section-title">

                    <h2>
                        Property Location
                    </h2>

                    <p>
                        Update your property location.
                    </p>

                </div>


                <div class="row">

                    <!-- Governorate -->

                    <div class="input-group">

                        <label for="governorate">
                            Governorate
                        </label>

                        <select
                            id="governorate"
                            name="governorate"
                            required>

                            <option value="" disabled>
                                Select Governorate
                            </option>

                            <option value="cairo"
                                <?php if ($property['governorate'] == "cairo") echo "selected"; ?>>
                                Cairo
                            </option>

                            <option value="giza"
                                <?php if ($property['governorate'] == "giza") echo "selected"; ?>>
                                Giza
                            </option>

                            <option value="alexandria"
                                <?php if ($property['governorate'] == "alexandria") echo "selected"; ?>>
                                Alexandria
                            </option>

                            <option value="sharkia"
                                <?php if ($property['governorate'] == "sharkia") echo "selected"; ?>>
                                Sharkia
                            </option>

                        </select>

                    </div>


                    <!-- City -->

                    <div class="input-group">

                        <label for="city">
                            City
                        </label>

                        <input
                            type="text"
                            id="city"
                            name="city"
                            value="<?php echo htmlspecialchars($property['city']); ?>"
                            placeholder="Enter City"
                            required>

                    </div>

                </div>


                <!-- Address -->

                <div class="input-group">

                    <label for="address">
                        Full Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        required><?php echo htmlspecialchars($property['address']); ?></textarea>

                </div>


                <!-- Google Maps -->

                <div class="input-group">

                    <label for="googleMap">
                        Google Maps Link
                    </label>

                    <input
                        type="url"
                        id="googleMap"
                        name="googleMap"
                        value="<?php echo htmlspecialchars($property['location_link']); ?>"
                        placeholder="Paste Google Maps Link"
                        required>

                </div>


                <!-- Description -->

                <div class="input-group">

                    <label for="description">
                        Property Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Write a description about the property..."
                        required><?php echo htmlspecialchars($property['description'] ?? ''); ?></textarea>

                    <small class="note">
                        Describe the property, rooms, location, nearby services, and other important details.
                    </small>

                </div>


                <!-- Property Features -->

                <div class="section-title">

                    <h2>
                        Property Features
                    </h2>

                    <p>
                        Update available features.
                    </p>

                </div>


                <div class="input-group">

                    <label>
                        Property Features
                    </label>

                    <div class="checkbox-group">

                        <?php

                        $facilitiesQuery = "

                        SELECT *

                        FROM facilities

                        ";

                        $facilitiesResult = mysqli_query(
                            $connect,
                            $facilitiesQuery
                        );

                        while ($facility = mysqli_fetch_assoc($facilitiesResult)) {

                            $checked = "";

                            $propertyFacilityQuery = "

                            SELECT *

                            FROM property_facilities

                            WHERE property_id = $propertyId

                            AND facility_id = {$facility['id']}

                            ";

                            $propertyFacilityResult = mysqli_query(
                                $connect,
                                $propertyFacilityQuery
                            );

                            if (mysqli_num_rows($propertyFacilityResult) > 0) {

                                $checked = "checked";
                            }

                        ?>

                            <label>

                                <input
                                    type="checkbox"
                                    name="features[]"
                                    value="<?php echo $facility['id']; ?>"
                                    <?php echo $checked; ?>>

                                <?php echo htmlspecialchars($facility['name']); ?>

                            </label>

                        <?php

                        }

                        ?>

                    </div>

                </div>


                <!-- Property Images -->

                <div class="section-title">

                    <h2>
                        Property Images
                    </h2>

                    <p>
                        Update your property images.
                    </p>

                </div>


                <div class="input-group">

                    <label>
                        Current Images
                    </label>


                    <?php if (!empty($existingImages)): ?>

                        <div class="existing-images">

                            <?php foreach ($existingImages as $image): ?>

                                <div class="existing-image">

                                    <img
                                        src="../<?php echo htmlspecialchars($image['image']); ?>"
                                        alt="Property Image">

                                    <?php if ($image['is_main'] == 1): ?>

                                        <span class="main-image-label">
                                            Main Image
                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <p style="color:#777; margin-top:10px;">
                            No images uploaded yet.
                        </p>

                    <?php endif; ?>


                    <label for="propertyImages">
                        Upload New Images
                    </label>

                    <input
                        type="file"
                        id="propertyImages"
                        name="propertyImages[]"
                        accept="image/*"
                        multiple>


                    <small class="note">

                        If you select new images, they will replace the current images.

                        If you do not select new images, the current images will remain unchanged.

                    </small>

                </div>


                <!-- Buttons -->

                <div class="button-group">

                    <a
                        href="my-properties.php"
                        class="back-btn">

                        <span>
                            ←
                        </span>

                        Cancel

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