<?php

require "../config/auth.php";
require "../config/database.php";

$isDashboard = true;

require "../includes/header.php";
require "../includes/dashboard-navbar.php";

require "data/profile.php";


/*=====================================
Get User Data
=====================================*/

$user = getProfile(
    $conn,
    $_SESSION['user_id']
);


/*=====================================
Update Profile
=====================================*/

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $imageName = "";

   
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0)
{
    $imgTmpName = $_FILES['image']['tmp_name'];
    $imgOriginalName = $_FILES['image']['name'];
    
    $imageName = time() . "_" . uniqid() . "_" . basename($imgOriginalName);
    $uploadPath = "../" . $imageName;

    if(move_uploaded_file($imgTmpName, $uploadPath)) {
    } else {
        echo "خطأ: فشل في نقل الملف المرفع!";
        exit;
    }
}

    $success = updateProfile(
        $conn,
        $_SESSION['user_id'],
        $name,
        $email,
        $phone,
        $imageName
    );

    if($success)
    {
        $_SESSION['name'] = $name;

        header("Location: profile.php");

        exit;
    }
}

?>

<section class="edit-profile">

<div class="container">

<div class="profile-form">

<h2>Edit Profile</h2>

<form method="POST" enctype="multipart/form-data">

<div class="form-group">

<label>Profile Picture</label>

<input
type="file"
name="image"
accept="image/*">

</div>

<div class="form-group">

<label>Full Name</label>

<input
type="text"
name="full_name"
value="<?= htmlspecialchars($user['name']) ?>"
required>

</div>

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($user['email']) ?>"
required>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($user['phone']) ?>"
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

