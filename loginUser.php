<?php
    session_start();
    if (isset($_SESSION["userId"])) {
    header("Location: ./index.php");
    exit;
    }
    $error = "";
    include("./db_connect.php");
    function loginExist(){
        global $db;
        global $error;

        $req = $db->prepare("SELECT login FROM users WHERE login= ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $login = $req->fetch();
        if ($login["login"])
            return true;
        else {
            $error = 5;
            return false;
        }
    }
    function validPass(){
        global $db;
        global $error;

        $req = $db->prepare("SELECT passwd FROM users WHERE login = ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $pass = $req->fetch();

        if (password_verify(htmlspecialchars($_POST["passwd"]), $pass["passwd"])){
            return true;
        }
        else{
            $error = 5;
            return false;
        }
    }

    function accountValid(){
        global $db;
        global $error;

        $req = $db->prepare("SELECT valid FROM users WHERE login = ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $valid = $req->fetch()["valid"];
        if ($valid){
            return true;
        }
        else {
            $error = 6;
            return false;
        }
    }

    if (validPass() && loginExist() && accountValid()){
        $req = $db->prepare("SELECT userid FROM users WHERE login= ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $_SESSION["userId"] = $req->fetch()["userid"];
        header("Location: ./index.php");
        exit;
    }
    header("Location: ./login.php?error=".$error);
?>