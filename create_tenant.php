<?php

session_start();
 include("db.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['create'])){
   $full_name=$_POST['full_name'];
   $useremail_2 = $_POST["useremail_2"];
   $userpassword_2= $_POST["userpassword_2"];
   $phone_number=$_POST["phone_number"];
   $confirmpassword=$_POST["confirmpassword"];
    $errors=[];
     // validations
     if(empty($full_name)){
        $errors[]="User name is required";
    }
    if(empty($useremail_2)){
        $errors[]="Email is required";
    }
    if(!empty($useremail_2) && !filter_var($useremail_2,FILTER_VALIDATE_EMAIL)){
        $errors[]="Invalid email";
    }
      if(empty($phone_number)){
        $errors[]="Phone number is required";
    }
     if(empty($userpassword_2)){
        $errors[]="Password is required";}
    
    if(empty($confirmpassword)){
        $errors[]="Confirm Password is required";
    }
    if($userpassword_2!=$confirmpassword){
        $errors[]="Password do not match";
    }
   
    if (!isset($_POST['agree'])) {
    $errors[] = "You must agree to the Terms & Conditions and Privacy Policy.";
}

    if(count($errors)>0){
        $_SESSION['errors']=$errors;
        header("Location:create_tenant.php");
        exit;
    }
    else{

    
    // check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$useremail_2'";
    $result = mysqli_query($connect, $check_email);

    if(mysqli_num_rows($result) > 0){

        $_SESSION['errors'] = ["Email already exists"];
        header("Location:create_tenant.php");
        exit;

    }else{
         // Adding data to the database
        
        $query = "INSERT INTO users (name, email, phone, password, role)
        VALUES ('$full_name', '$useremail_2', '$phone_number', '$userpassword_2', 'tenant')";


       if(mysqli_query($connect, $query)){

    $_SESSION['user_id'] = mysqli_insert_id($connect);
    $_SESSION['name'] = $full_name;
    $_SESSION['role'] = "tenant";

    $_SESSION['useremail_2'] = $useremail_2;
    $_SESSION['full_name'] = $full_name;
    $_SESSION['phone_number'] = $phone_number;

    header("Location:tenant/dashboard.php");
    exit;

}else{

            echo "Database Error: " . mysqli_error($connect);

             }
          }
        }
      }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/create_tenant.css">
</head>
<body>


<?php
// Show errors
  if(isset($_SESSION['errors'])){
  foreach( $_SESSION['errors']  as $error){
    ?>
 <div class="error">

 <?php echo $error ."<br>"; ?>
 </div>
    <?php
   
  
}
unset($_SESSION['errors']);
}

?>
     <!--The navbar contains the website name and a link to return to the homepage  -->
<nav class="nav_bar">
<div>
<a href="home.php"> <i class="fa-solid fa-arrow-left"></i>    Back to home</a>
</div>
<div>
<h2><span>Student</span>Nest</h2>
</div>
    </nav>

</header>


<!-- Title and description -->
    <div>
<h1> Create Tenant Account</h1>
<p>Get started in just a few minutes</p>
</div>


<!-- form -->

<form action="" method="POST">


   
   
    <div class="input_group">    
                <!--             The first box            -->
<label>Full name </label>
<br>
<div class="icon_3">
   <input type="text" name="full_name"  class="name_input" placeholder="  Enter your full name">
   <i class="fa-solid fa-user"></i>
</div>

    <br>
      <!--           The second box       -->
<label>Email  Address</label>
<br>

<div class="icon_1">
<input type="email" name="useremail_2" class="email_input" placeholder="  you@example.com">
<i class="fa-solid fa-envelope"></i>
</div>

<br>

<!--         The third box             -->

<label>Phone Number </label>
<br>
<div class="icon_4">
   <input type="text" name="phone_number"  class="phone_input" placeholder="  +20 100 000 0000">
   <i class="fa-solid fa-phone"></i>
</div>

    <br>

<!--           The Fourth box       -->
           <label> Password</label>
           <br>
<div class="icon_2">
<input type="password" name="userpassword_2" class="password_input"  placeholder="  Enter your Password"  >
<i class="fa-solid fa-lock"></i>
</div>

<br>
<!--          The fifth  box        -->
<label> Confirm Password</label>
<br>
<div class="icon_2">
<input type="password" name="confirmpassword" class="confirm_password_input"  placeholder="  Confirm your Password"  >
<i class="fa-solid fa-lock"></i>
</div>

<br>

<!--          agree        -->
<div class="check_box">          <!-- check_box class to control the line before create-->
       
    <input type="checkbox" name="agree" > <label>I agree to the Terms and conditions and Privacy Policy  </label>       
</div>

<br>
<!-- button -->
<button type="submit"  name="create">Create Account</button>

<br>

<div class="create">         <!-- create class to control the last line -->
<p>Already have an account? <a href="tenant_login.php">Login</a></p>
</div>
</div>

</form>
</body>
</html>
