<?php
    session_start();
    if (!isset($_SESSION["userId"])){
        header ("Location: ./login.php");
        exit ;
    }

    include ("./db_connect.php");

    if (isset($_POST["imgId"]) && isset($_POST["submit"]) && $_POST["submit"] == "submit"){
        $req = $db->prepare("DELETE FROM images WHERE id= ?");
        $req->execute(array(htmlspecialchars($_POST["imgId"])));
    }
    header("Location: ./galerie.php");
?>