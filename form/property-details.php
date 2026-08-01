<?php

session_start();

require_once "../db.php";


// Database Data

$property = [];

if (isset($_GET['id'])) {

    $propertyId = intval($_GET['id']);

    $query = mysqli_query(
        $connect,
        "SELECT * FROM housing WHERE id = $propertyId"
    );


    if (mysqli_num_rows($query) > 0) {

        $property = mysqli_fetch_assoc($query);
    }
}


// Features Data

$features = [];

$featuresQuery = mysqli_query(
    $connect,
    "SELECT * FROM facilities"
);


while ($row = mysqli_fetch_assoc($featuresQuery)) {

    $features[] = $row;
}


// Selected Features

$selectedFeatures = [];


if (!empty($property['id'])) {


    $selectedQuery = mysqli_query(
        $connect,
        "SELECT facility_id 
         FROM property_facilities 
         WHERE property_id = " . $property['id']
    );


    while ($row = mysqli_fetch_assoc($selectedQuery)) {

        $selectedFeatures[] = $row['facility_id'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Add Property</title>

    <link rel="stylesheet"
        href="../css/malaz.css">

</head>

<body>

    <div class="container">

        <div class="form-box">

            <form
                action="save-property-details.php"
                method="POST"
                enctype="multipart/form-data">


                <!-- Progress Bar -->

                <div class="progress-container">

                    <div class="progress-text">

                        <span>Step 1 of 3</span>

                        <span>Property Details</span>

                    </div>

                    <div class="progress-bar">

                        <div class="progress progress-step1"></div>

                    </div>

                </div>


                <hr class="divider">


                <h1>Add New Property</h1>

                <p>

                    Fill in the information below to list your property.

                </p>
                <!-- Property Information -->

                <div class="section-title">

                    <h2>Property Information</h2>

                    <p>
                        Enter the basic information about your property.
                    </p>

                </div>


                <div class="input-group">

                    <label for="propertyType">

                        Property Type

                    </label>


                    <select
                        id="propertyType"
                        name="propertyType"
                        required>


                        <option
                            value=""
                            disabled
                            <?php echo empty($property['category_id']) ? "selected" : ""; ?>>

                            Select Property Type

                        </option>


                        <?php

                        $categoryQuery = mysqli_query(
                            $connect,
                            "SELECT * FROM categories"
                        );


                        while ($category = mysqli_fetch_assoc($categoryQuery)) {


                        ?>


                            <option

                                value="<?php echo $category['id']; ?>"

                                <?php

                                if (

                                    isset($property['category_id']) &&

                                    $property['category_id'] == $category['id']

                                ) {

                                    echo "selected";
                                }

                                ?>>

                                <?php echo htmlspecialchars($category['name']); ?>

                            </option>


                        <?php

                        }

                        ?>


                    </select>

                </div>



                <div class="input-group">


                    <label for="monthlyRent">

                        Monthly Rent (EGP)

                    </label>


                    <input

                        type="number"

                        id="monthlyRent"

                        name="monthlyRent"

                        placeholder="Enter Monthly Rent"

                        min="500"

                        value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>"

                        required>


                </div>



                <!-- Property Location -->


                <div class="section-title">


                    <h2>Property Location</h2>


                    <p>

                        Enter the location of your property.

                    </p>


                </div>




                <div class="row">


                    <div class="input-group">


                        <label for="governorate">

                            Governorate

                        </label>


                        <select

                            id="governorate"

                            name="governorate"

                            required>


                            <option

                                value=""

                                disabled

                                <?php echo empty($property['governorate']) ? "selected" : ""; ?>>

                                Select Governorate

                            </option>



                            <?php


                            $governorates = [

                                "cairo" => "Cairo",

                                "giza" => "Giza",

                                "alexandria" => "Alexandria",

                                "sharkia" => "Sharkia",

                                "dakahlia" => "Dakahlia",

                                "ismailia" => "Ismailia",

                                "fayoum" => "Fayoum",

                                "gharbia" => "Gharbia",

                                "beni-suef" => "Beni Suef",

                                "qalyubia" => "Qalyubia",

                                "suez" => "Suez",

                                "port-said" => "Port Said",

                                "sohag" => "Sohag"

                            ];



                            foreach ($governorates as $key => $value) {


                            ?>


                                <option

                                    value="<?php echo $key; ?>"

                                    <?php

                                    if (

                                        ($property['governorate'] ?? '') == $key

                                    ) {

                                        echo "selected";
                                    }

                                    ?>>

                                    <?php echo $value; ?>

                                </option>


                            <?php

                            }

                            ?>


                        </select>


                    </div>
                    <div class="input-group">

                        <label for="city">

                            City

                        </label>


                        <input

                            type="text"

                            id="city"

                            name="city"

                            placeholder="Enter City"

                            value="<?php echo htmlspecialchars($property['city'] ?? ''); ?>"

                            required>


                    </div>


                </div>



                <div class="input-group">


                    <label for="address">

                        Full Address

                    </label>


                    <textarea

                        id="address"

                        name="address"

                        rows="3"

                        placeholder="Enter Full Address"

                        required><?php echo htmlspecialchars($property['address'] ?? ''); ?></textarea>


                </div>




                <div class="input-group">


                    <label for="googleMap">

                        Google Maps Link

                    </label>


                    <input

                        type="url"

                        id="googleMap"

                        name="googleMap"

                        placeholder="Paste Google Maps Link"

                        value="<?php echo htmlspecialchars($property['location_link'] ?? ''); ?>"

                        required>


                    <small class="note">

                        Open Google Maps, choose the property location, then copy and paste the location link here.

                    </small>


                </div>


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
        Describe the property, rooms, location, nearby services, and anything important for students.
    </small>

</div>

                <!-- Property Features -->


                <div class="section-title">


                    <h2>Property Features</h2>


                    <p>

                        Select all available features in your property.

                    </p>


                </div>




                <div class="input-group">


                    <label>

                        Property Features

                    </label>



                    <div class="checkbox-group">



                        <?php


                        foreach ($features as $feature) {


                        ?>


                            <label class="checkbox-item">


                                <input

                                    type="checkbox"

                                    name="features[]"

                                    value="<?php echo $feature['id']; ?>"

                                    <?php

                                    if (in_array($feature['id'], $selectedFeatures)) {

                                        echo "checked";
                                    }

                                    ?>>


                                <?php echo htmlspecialchars($feature['name']); ?>


                            </label>



                        <?php


                        }


                        ?>


                    </div>


                </div>
                <!-- Property Images -->


                <div class="section-title">


                    <h2>Property Images</h2>


                    <p>

                        Upload clear images of your property.

                    </p>


                </div>




                <div class="input-group">


                    <label for="propertyImages">

                        Upload Images

                    </label>


                    <input

                        type="file"

                        id="propertyImages"

                        name="propertyImages[]"

                        accept="image/*"

                        multiple

                        <?php

                        $imageQuery = [];

                        if (!empty($property['id'])) {


                            $imageResult = mysqli_query(

                                $connect,

                                "SELECT image FROM property_images WHERE property_id = " . $property['id']

                            );


                            while ($img = mysqli_fetch_assoc($imageResult)) {

                                $imageQuery[] = $img;
                            }
                        }



                        if (empty($imageQuery)) {

                            echo "required";
                        }

                        ?>>



                    <small class="note">

                        Upload clear photos of all available areas.

                        <br><br>

                        • If you are renting an empty apartment, upload photos of the living room, bedrooms, kitchen and bathroom.

                        <br><br>

                        • If you are renting a single or double room, upload clear photos of the room and any shared areas.

                    </small>



                </div>





                <!-- Navigation Buttons -->


                <div class="button-group first-page-buttons">


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