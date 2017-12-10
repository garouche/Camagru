<?php
    session_start();
    if (!isset($_SESSION["userId"]) || !isset($_POST["submit"]) || $_POST["submit"] != "submit"){
        header("Location: ./login.php");
        exit ;
    }
    include ("./db_connect.php");
    function modifNotif($str){
        global $db;

        $req = $db->prepare("UPDATE users SET notif = 0 WHERE userid = ?");
        if (isset($str) && $str == "on"){
            $req = $db->prepare("UPDATE users SET notif = 1 WHERE userid = ?");
        }
        $req->execute(array($_SESSION["userId"]));
    }
    if (isset($_POST["checkbox"])){
        modifNotif($_POST["checkbox"]);
    }
    else
        modifNotif(null);
    header("Location: ./account.php");
    ?>