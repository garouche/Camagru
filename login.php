<?php
    session_start();
    if (isset($_SESSION["userId"])){
        header("Location: ./index.php");
        exit ;
    }
    include("./db_connect.php");
    $error = "";
    $msg = "";
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case 1:
                $error = "Le mot de passe doit au moins contenir une minuscule, une majuscule, un chiffre, et  faire 6+ caracteres.";
                break;
            case 2:
                $error = "Email invalide.";
                break;
            case 3:
                $error = "Ce login existe deja.";
                break;
            case 4:
                $error = "Email deja utilise.";
                break;
            case 5:
                $error = "Login ou mot de passe incorrecte.";
                break;
            case 6:
                $error = "Validez votre compte via le mail recu.";
                break ;
        }
    }
    if (isset($_GET["msg"])){
        if ($_GET["msg"] == 1){
            $msg = "Compte cree avec succes veuillez le valider via le lien recu par mail";
        }
        else if ($_GET["msg"] == 2){
            $msg = "Compte valide avec succes vous pouvez vous connecter";
        }
    }
    include("./templates/header.php");
?>
<div class="content">
    <form class="login" action="./loginUser.php" method="post">
        <fieldset>
            <legend>Se Connecter</legend><br />
        <input type="text" name="login" placeholder="Identifiant*"><br />
        <input type="password" name="passwd" placeholder="Mot de passe*" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"><br />
            <?php if (isset($_GET["error"]) && ($_GET["error"] == 5 || $_GET["error"] == 6)) echo ('<p class="errorMsg">'.$error.'</p>'); ?>
            <a href="./forgottenPassword.php"><p>Mot de passe oublie</p></a></p>
            <p style="font-size: 1.5vmin;">* Champs obligatoires</p>
        <input class="submit" name="submit" type="submit" value="Se Connecter">
        </fieldset>
    </form>
    <form class="register" action="./createMember.php" method="post">
        <fieldset>
            <legend>Creer un compte</legend>
            <p class="validAccMsg"><?= $msg; ?></p>
            <input type="text" name="login" placeholder="Identifiant*"><br />
            <input type="password" name="passwd" placeholder="Mot de passe*" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"><br />
            <input type="email" name="mail" placeholder="E-mail*"><br />
            <?php if (isset($_GET["error"]) && ($_GET["error"] != 5 && $_GET["error"] != 6)) echo ('<p class="errorMsg">'.$error.'</p>'); ?>
            <p style="font-size: 1.5vmin;">* Champs obligatoires</p>
            <input class="submit" name="submit" type="submit" value="Creer un compte">
        </fieldset>
    </form>
</div>
<?php
    include("./templates/footer.php");
    ?>