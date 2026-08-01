<?php

require "../config/auth.php";
require "../config/database.php";

require "data/favorite.php";


$id = $_GET['id'] ?? null;


if($id){

    removeFavorite(
    $conn,
    $_SESSION['user_id'],
    $id
);

}


header("Location: favorites.php");
exit;

?>