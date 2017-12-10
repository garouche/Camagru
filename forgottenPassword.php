<?php
    $error = "";
    session_start();
    if (isset($_SESSION["userId"])){
        header("Location: ./index.php");
    }
    if (isset($_POST["login"])){
        include ("./db_connect.php");
        $req = $db->prepare("SELECT login FROM users WHERE login = ?");
        $req->execute(array(htmlspecialchars($_POST["login"])));
        $login = $req->fetch()["login"];

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
                $error = 2;
                return false;
            }
        }

        if (!$login || !accountValid()){
            if (!$error){
                $error = 1;
            }
            header ("Location: ./forgottenPassword.php?error=".$error);
            exit;
        }
        else{
            $req = $db->prepare("SELECT email, userid FROM users WHERE login = ?");
            $req->execute(array($_POST["login"]));
            list($mail, $id) = $req->fetch();
            $key = md5(microtime().rand().$id);
            $host = $_SERVER["HTTP_HOST"].preg_split("/[a-zA-Z]*\./",$_SERVER["PHP_SELF"])[0];
            mail($mail, "Reinitialisation de mot de passe", "Bonjour,\n\nVeuillez visiter ce lien afin de reinitialiser votre mot de passe:\n\nhttp://".$host."modifyPass.php?id=".$id."&key=".$key." \n");
            $req = $db->prepare("UPDATE users SET hash = ? WHERE login = ? ");
            $req->execute(array($key, $_POST["login"]));
            header("Location: ./login.php");
            exit ;
        }
    }
    if (isset($_GET["error"])){
        $error = "Identifiant inconnu !";
    }
include ("./templates/header.php");
?>
<div class="forgotContainer">
<div class="forgotPassContainer">
    <h2>Reinitialiser mot de passe</h2>
    <p style="color: red;"><?= $error; ?><br /></p>
    <p>Veuillez entrer votre identifiant, un email vous sera envoye a l'adresse email associe </p>
    <form action="./forgottenPassword.php" method="post">
        <input type="text" name="login" placeholder="Identifiant" pattern="^[a-z\d\.]{5,}$"><br /><br/>
        <button name="submit" value="submit">Envoyer</button>
    </form>
</div>
</div>
<?php
    include("./templates/footer.php");

