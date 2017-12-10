<?php
    session_start();
    include ("./db_connect.php");
    if ((!isset($_SESSION["userId"])) || (!isset($_POST["Pic"]))){
        header("Location: ./index.php");
        exit;
    }
    if ($_POST["Pic"] != "erase")
    {
        $req = $db->prepare("INSERT INTO images (img_name, userid, img_blob, img_type, time) VALUES (?, ?, ?, ?, ?)");
        $req->execute(array(uniqid(), $_SESSION["userId"], htmlspecialchars($_POST["Pic"]), "pic", time()));
    }
    unset($_SESSION["curImg"]);
    header("Location: ./index.php");
?>

