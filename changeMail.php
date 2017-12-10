<?php

session_start();
if (!isset($_SESSION["userId"]) || !isset($_POST["mailgit"]) || !isset($_POST["submit"]) || $_POST["submit"] != "submit"){
    header("Location: ./account.php?error=0");
}

include("./db_connect.php");

function check_emailFilter(){
    if (filter_var(htmlspecialchars($_POST["mail"]), FILTER_VALIDATE_EMAIL))
        return true;
    else{
        return false;
    }
}

function checkCurMail($mail){
    global $db;

    $req = $db->prepare("SELECT userid FROM users WHERE email = ?");
    $req->execute(array($mail));
    return $req->fetch()["userid"];
}

function check_email(){
    global $db;

    if (check_emailFilter()) {
        $req = $db->prepare("SELECT email FROM users WHERE email= ?");
        $req->execute(array(htmlspecialchars($_POST["mail"])));
        $mail = $req->fetch();
        if ($mail["email"] && $_SESSION["userId"] != checkCurMail(htmlspecialchars($_POST["mail"]))) {
            return false;
        } else
            return true;
    }
}

if (check_email()){
    header("Location: ./account.php?msg=3");
    exit;
}
header("Location: ./account.php?error=3");
?>