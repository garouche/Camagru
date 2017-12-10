<?php
    session_start();
    if (!isset($_SESSION["userId"]) || !isset($_POST["login"]) || !isset($_POST["submit"]) || $_POST["submit"] != "submit"){
        header("Location: ./account.php?error=0");
    }

    include("./db_connect.php");

    function check_log(){
     global $db;

        $req = $db->prepare("SELECT login FROM users WHERE login= ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $login = $req->fetch();
        if ($login["login"]){
            return false;
        }
        else
            return true;
    }

    if (check_log() && strlen(htmlspecialchars($_POST["login"])) >= 6){
        $req = $db->prepare("UPDATE users SET login = ? WHERE userid = ?");
        $req->execute(array(htmlspecialchars($_POST["login"]), $_SESSION["userId"]));
        header("Location: ./account.php?msg=1");
        exit;
    }
    header("Location: ./account.php?error=1");
?>