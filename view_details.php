<?php 
session_start();
$id=$_GET['id'];


$details =[
1 =>[
    "title"=>"Single Room",
    "location" => "Sharqia - Zagazig",
    "price"=>"2500 EGP / Month",
    "desc" => "Comfortable single room in Zagazig, Sharqia. Perfect for students seeking a quiet place.",
    "services"=>["Wi-Fi",
                "Shared Kitchen",
                "Near Mosque",
                 "Balcony" ],
     "main_image"=>    "images/bedrooms_6.jfif",        
     "images"=>[ "images/living_Rooms_7.jfif",
                "images/kitchens_4.jfif",
                "images/bathrooms_3.jfif",
                "images/outdoor_Areas_1.jfif"]

]



];
$room=$details[$id];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/view_details.css">
</head>
<body>
    <div class="parent">
    <div class="left">
    <img src="<?php echo $room['main_image'];?>" class="main_image">
    <?php foreach($room['images'] as $image){?>
    <img src="<?php echo $image ?>"  class="images">
    <?php }?>
     </div>
    <div class="right">
    <h1><?php echo $room['title']; ?></h1>
    <h5><?php echo $room['location'];?></h5>
</div>
</div> 
</body>
</html>