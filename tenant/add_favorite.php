<?php

require "../config/auth.php";
require "../config/database.php";

require "data/favorite.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0)
{
    addFavorite(
        $conn,
        $_SESSION['user_id'],
        $id
    );
}

header("Location: favorites.php");
exit;

?>