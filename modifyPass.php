<?php
    session_start();
    include ("./db_connect.php");

    $error = "";

    function checkId(){
        global $db;

        if (isset($_GET["id"])){
            $req = $db->prepare("SELECT userid FROM users WHERE userid = ?");
            $req->execute(array(htmlspecialchars($_GET["id"])));
            $id = $req->fetch()["userid"];
            if ($id){
                return true;
            }
        }
        return false;
    }

    function checkKey(){
        global $db;

        if ($_GET["key"] && checkId()){
            $req = $db->prepare("SELECT hash FROM users WHERE userid = ?");
            $req->execute(array(htmlspecialchars($_GET["id"])));
            $hash = $req->fetch()["hash"];

            if ($hash == $_GET["key"]){
                return true;
            }
        }
        return false;

    }

    function check_pw(){
        if (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", htmlspecialchars($_POST["passwd"])))
            return true;
        else{
            global $error;
            $error = 1;
            return false;
        }
    }

    function validPass(){
        global $db;
        global $error;

        if (isset($_POST["passwd"]) && isset($_POST["submit"]) && $_POST["submit"] == "submit"){
            if (check_pw()) {
                $pass = password_hash(htmlspecialchars($_POST["passwd"]), PASSWORD_BCRYPT);
                $req = $db->prepare("UPDATE users SET passwd = ?, hash = NULL WHERE userid = ?");
                $req->execute(array($pass, htmlspecialchars($_GET["id"])));
                return true;
            }
            else{
                header("Location: ./modifyPass.php?id=".$_GET["id"]."&key=".$_GET["key"]."&error=".$error);
                exit;
            }
        }
        return false;
    }

    if (isset($_GET["error"]) && $_GET["error"] == 1)
        $error = "<p style='color: red;'> Le mot de passe doit au moins contenir une minuscule, une majuscule, un chiffre, et  faire 6+ caracteres.</p>";

    if (isset($_SESSION["userId"]) || !checkKey() || validPass()){
        header("Location: ./login.php");
        exit ;
    }

    include ("./templates/header.php")
?>
<div class="forgotContainer">
<div class="forgotPassContainer">
    <form method="post" action="./modifyPass.php?id=<?= $_GET["id"]; ?>&key=<?= $_GET["key"]; ?>">
        <?= $error; ?>
        <h3>Nouveau mot de passe</h3>
        <input type="password" name="passwd" placeholder="Nouveau mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}">
        <button name="submit" value="submit">Envoyer</button>
    </form>
</div>
</div>
<?php
    include ("./templates/footer.php");
    ?>