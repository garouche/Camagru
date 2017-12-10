<?php
session_start();
if (!isset($_SESSION["userId"]))
{
    header("Location: ./login.php");
    exit ;
}
include("./templates/header.php");
include("./montage.php");
include("./templates/footer.php");
?>

