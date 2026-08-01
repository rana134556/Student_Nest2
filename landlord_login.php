<?php

session_start();
 include("db.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['login'])){
  $useremail = $_POST["useremail"];
   $userpassword= $_POST["userpassword"];
    $errors=[];
    if(empty($useremail)){
        $errors[]="Email is required";
         
    }
    if(!empty($useremail) && !filter_var($useremail,FILTER_VALIDATE_EMAIL)){
        $errors[]="Invalid email";
    
    }
     if(empty($userpassword)){
        $errors[]="password is required";
         
    }
    if(count($errors)>0){
        $_SESSION['errors']=$errors;
        header("Location:landlord_login.php");
        exit;
    }
    else{
         // Verifying that the data exists in the database

    $query = "SELECT * FROM users WHERE email='$useremail' AND password='$userpassword' AND role='landlord'";

    $result = mysqli_query($connect,$query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];

        header("Location:home.php");
        exit();
    }
    else{

       $errors[] = "Account not found. Please create an account first";
        $_SESSION['errors'] = $errors;
        header("Location:landlord_login.php");
         exit();

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
    <link rel="stylesheet" href="css/landlord_login.css">
</head>
<body>


<?php
// show errors
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
    <header>
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
<h1> Landlord Sign In</h1>
<p>Sign in to manage your properties</p>
</div>


<!-- form -->

<form action="" method="POST">
    <!--        The first box    -->
    <div class="input_group">                        
<label>Email  Address</label>
<br>
<div class="icon_1">
<input type="email" name="useremail" class="email_input" placeholder="  you@example.com">
<i class="fa-solid fa-envelope"></i>
</div>


<br>
<br>

<!--           The second box       -->
<label>Password</label>
<br>
<div class="icon_2">
<input type="password" name="userpassword" class="password_input"  placeholder="   ********"  >
<i class="fa-solid fa-lock"></i>
</div>

<br>

<!--        Remember Me        -->
<div class="forget">          <!-- forget class to control the line before sign in -->
       
    <input type="checkbox" > <label>Remember Me </label> 
    <a href="landlord_login.php"> Forget Password? </a>      
</div>

<br>
<!-- button -->
<button type="submit"  name="login">Sign In</button>

<br>

<div class="create">         <!-- create class to control the last line -->
<p>Don't have an account? <a href="create_landlord.php"> Create a Landlord Account</a></p>
</div>
</div>

</form>

</body>
</html>