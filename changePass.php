
<?php

session_start();
if (!isset($_SESSION["userId"]) || !isset($_POST["passwd"]) || !isset($_POST["submit"]) || $_POST["submit"] != "submit"){
header("Location: ./account.php?error=0");
}

include("./db_connect.php");


function check_pw(){
    if (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", htmlspecialchars($_POST["passwd"]))) {
        return true;
    }
    else{
        return false;
    }
}

function validPass()
{
    global $db;

    if (isset($_POST["passwd"]) && strlen($_POST["passwd"]) <= 25 && check_pw()) {
        $pass = password_hash(htmlspecialchars($_POST["passwd"]), PASSWORD_BCRYPT);
        $req = $db->prepare("UPDATE users SET passwd = ?, hash = NULL WHERE userid = ?");
        $req->execute(array($pass, $_SESSION["userId"]));
        return true;
    } else {
        return false;
    }
}

if (validPass()){
    header("Location: ./account.php?msg=2");
    exit;
}
header("Location: ./account.php?error=2");
?>