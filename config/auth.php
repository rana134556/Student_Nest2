<?php

require_once "session.php";

/*
======================================
Before Login System
======================================

*/

if (!isset($_SESSION['user_id'])) {

    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = "Roqaya Ahmed";
    $_SESSION['role'] = "tenant";

}

/*
======================================
After Login System
======================================

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] != "tenant") {
    header("Location: ../auth/login.php");
    exit;
}

*/