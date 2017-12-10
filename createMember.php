<?php
    include("./db_connect.php");
    session_start();
    $error = 0;

    function check_pw(){
        if (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", htmlspecialchars($_POST["passwd"])))
            return true;
        else{
            global $error;
            $error = 1;
            return false;
        }
    }

    function check_log(){
        global $db;
        $req = $db->prepare("SELECT login FROM users WHERE login= ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $login = $req->fetch();
        if ($login["login"]){
            global $error;
            $error = 3;
            return false;
        }
        else
            return true;
    }

    function check_emailFilter(){
        if (filter_var(htmlspecialchars($_POST["mail"]), FILTER_VALIDATE_EMAIL))
            return true;
        else{
            global $error;
            $error = 2;
            return false;
        }
    }

    function check_email(){
        global $db;
        $req = $db->prepare("SELECT email FROM users WHERE email= ?");
        $req->execute(array(htmlspecialchars($_POST["mail"])));
        $mail = $req->fetch();
        if ($mail["email"]){
            global $error;
            $error = 4;
            return false;
        }
        else
            return true;
    }

    if (isset($_SESSION["userId"])){
        header("Location: ./index.php");
        exit;
    }

    if (htmlspecialchars($_POST["submit"]) == "Creer un compte" && htmlspecialchars($_POST["login"]) &&
        htmlspecialchars($_POST["passwd"]) && htmlspecialchars($_POST["mail"]) && check_pw() && check_emailFilter()) {

            if (check_log() && check_email())
            {
                $pass = password_hash(htmlspecialchars($_POST["passwd"]), PASSWORD_BCRYPT);
                $key =  md5(microtime().rand());
                $req = $db->prepare("INSERT INTO users (login, passwd, email, valid, hash, notif) VALUES (?, ?, ?, 0, ?, 1)");
                $req->execute(array(htmlspecialchars($_POST["login"]), $pass, htmlspecialchars($_POST["mail"]), $key));
                $req = $db->prepare("SELECT userid FROM users WHERE login= ?");
                $req->execute(array(htmlspecialchars($_POST["login"])));
                $id = $req->fetch()["userid"];
                $req = $db->prepare("SELECT email FROM users WHERE userid = ?");
                $req->execute(array($id));
                $mail = $req->fetch()["email"];
                $host = $_SERVER["HTTP_HOST"].preg_split("/[a-zA-Z]*\./",$_SERVER["PHP_SELF"])[0];
                mail($mail, "Validation de compte", "Bonjour,\n\nAfin de valider ce compte veuillez cliquer sur ce lien:\n\n http://".$host."validUser.php?id=".$id."&key=".$key);
                header("Location: ./login.php?msg=1");
                exit ;
            }
        }
    header("Location: ./login.php?error=".$error);
?>