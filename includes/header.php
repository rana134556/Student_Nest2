<!DOCTYPE html>
<html lang="an">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>StudentNest | About Us</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;800&display=swap" rel="stylesheet">
<?php if(!isset($isgallery)): ?>
<link rel="stylesheet" href="../assest/css/style.css">
<link rel="stylesheet" href="../assest/css/footer.css">
<?php endif;?>

<?php if(isset($isDashboard)): ?>

<link rel="stylesheet" href="../assest/css/tenant.css">


<?php endif; ?>
<?php if(isset($isgallery)): ?>
<link rel="stylesheet" href="assest/css/style.css">
<link rel="stylesheet" href="assest/css/footer.css">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/navbar.css">

    <?php endif; ?>
    <?php if(isset($ishome)): ?>
        
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="assest/css/style.css">
       <link rel="stylesheet" href="assest/css/footer.css">
    <?php endif; ?>
    <?php if(isset($isabout)): ?>
    <link rel="stylesheet" href="../css/navbar.css">

    <?php endif; ?>
    <?php if(isset($iscontact_us)): ?>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/contact_us.css">
    <link rel="stylesheet" href="assest/css/style.css">
  <link rel="stylesheet" href="assest/css/footer.css">



    <?php endif; ?>

</head>

<body>