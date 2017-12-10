<?php
include ("./db_connect.php");
session_start();
include ("./templates/header.php");
if (isset($_SESSION["curImg"])){
    unset($_SESSION["curImg"]);
}
if (isset($_SESSION["curPic"])){
    unset($_SESSION["curPic"]);
}
if (isset($_POST["displayImage"])) {
    include ("./display_img.php");
}
else {
    include("./display_galery.php");
}
include ("./templates/footer.php");
?>
