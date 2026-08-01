<?php

session_start();

$iscontact_us= true;
include("includes/header.php");
include("constants.php");
include("navbar.php");

require_once "db.php";


$page = "contact-us";



/* Contact Information */


$contactInfo = [


        "email" => "info@studentnest.com",


        "phone" => "01000000000",


        "address" => "Cairo, Egypt",


        "workingHours" => "9:00 AM - 6:00 PM"


];





/* Handle Contact Form */


if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $name = $_POST['name'] ?? "";

        $email = $_POST['email'] ?? "";

        $subject = $_POST['subject'] ?? "";

        $message = $_POST['message'] ?? "";



        /*
    Later:

    Insert into contact_messages table

    */
}
?>



        <div class="dashboard-container">



                <div class="contact-page">



                        <div class="contact-header">


                                <h1>

                                        Contact Us

                                </h1>


                                <p>

                                        Have a question or need help?
                                        We are here to assist you.

                                </p>


                        </div>





                        <div class="contact-info-cards">



                                <div class="contact-card">


                                        <i class="fa-solid fa-envelope"></i>


                                        <h3>

                                                Email

                                        </h3>


                                        <p>

                                                <?php

                                                echo htmlspecialchars($contactInfo['email']);

                                                ?>

                                        </p>


                                </div>





                                <div class="contact-card">


                                        <i class="fa-solid fa-phone"></i>


                                        <h3>

                                                Phone

                                        </h3>


                                        <p>

                                                <?php

                                                echo htmlspecialchars($contactInfo['phone']);

                                                ?>

                                        </p>


                                </div>





                                <div class="contact-card">


                                        <i class="fa-solid fa-location-dot"></i>


                                        <h3>

                                                Address

                                        </h3>


                                        <p>

                                                <?php

                                                echo htmlspecialchars($contactInfo['address']);

                                                ?>

                                        </p>


                                </div>





                                <div class="contact-card">


                                        <i class="fa-solid fa-clock"></i>


                                        <h3>

                                                Working Hours

                                        </h3>


                                        <p>

                                                <?php

                                                echo htmlspecialchars($contactInfo['workingHours']);

                                                ?>

                                        </p>


                                </div>



                        </div>





                        <div class="contact-form-container">


                                <h2>

                                        Send Us A Message

                                </h2>




                                <form action="" method="POST">



                                        <div class="form-group">


                                                <label>

                                                        Full Name

                                                </label>


                                                <input

                                                        type="text"

                                                        name="name"

                                                        placeholder="Enter your name"

                                                        required>


                                        </div>





                                        <div class="form-group">


                                                <label>

                                                        Email Address

                                                </label>


                                                <input

                                                        type="email"

                                                        name="email"

                                                        placeholder="Enter your email"

                                                        required>


                                        </div>





                                        <div class="form-group">


                                                <label>

                                                        Subject

                                                </label>


                                                <input

                                                        type="text"

                                                        name="subject"

                                                        placeholder="Message subject"

                                                        required>


                                        </div>





                                        <div class="form-group">


                                                <label>

                                                        Message

                                                </label>


                                                <textarea

                                                        name="message"

                                                        rows="5"

                                                        placeholder="Write your message..."

                                                        required></textarea>


                                        </div>





                                        <button

                                                type="submit"

                                                class="send-contact-btn">


                                                <i class="fa-solid fa-paper-plane"></i>


                                                Send Message


                                        </button>




                                </form>



                        </div>



                </div>


        </div>


<?php include("includes/footer.php");?>