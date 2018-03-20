<?php
    session_start();
    if (!isset($_SESSION["userId"])){
        header ("Location: ./index.php");
        exit ;
    }
    if (isset($_SESSION["curImg"])){
        unset($_SESSION["curImg"]);
    }
    if (isset($_SESSION["curPic"])){
        unset($_SESSION["curPic"]);
    }
    require_once ("./db_connect.php");
    function displayMsg($pos)
    {
        if (isset($_GET["error"])) {
            switch ($_GET["error"]) {
                case 1:
                    if ($pos == 1)
                        echo "Identifiant invalide et/ou deja existant";
                    break;
                case 2:
                    if ($pos == 2)
                        echo "Mot de passe invalide";
                    break;
                case 3:
                    if ($pos == 3)
                        echo "Email invalide et/ou deja existant";
                    break;
            }
        }
        if (isset($_GET["msg"]) && !isset($_GET["error"])) {
            switch ($_GET["msg"]) {
                case 1:
                    if ($pos == 1)
                        echo "Identifiant modifie avec succes";
                    break;
                case 2:
                    if ($pos == 2)
                        echo "Mot de passe modifie avec succes";
                    break;
                case 3:
                    if ($pos == 3)
                        echo "Email modifie avec succes";
                    break;
            }
        }
    }
    $req = $db->prepare("SELECT login FROM users WHERE userid = ?");
    $req->execute(array($_SESSION["userId"]));
    $login = $req->fetch()["login"];
    function displayCheckbox(){
        global $db;

        $req = $db->prepare("SELECT notif FROM users WHERE userid = ?");
        $req->execute(array($_SESSION["userId"]));
        $ret = $req->fetch()["notif"];
        if ($ret == 1){
            echo "<input type=\"checkbox\" name=\"checkbox\" checked>";
        }
        else if ($ret == 0){
            echo "<input type=\"checkbox\" name=\"checkbox\">";
        }
    }
    require_once ("./templates/header.php");
?>
<div class="forgotContainer">
    <div class="forgotPassContainer">
    <h2>Modification du compte</h2><br /><br /><br />
    <form method="post" action="./changeLogin.php">
        Login: <?= $login; ?><br /><p class="displayMsg"><?= displayMsg(1);?></p>
        <input type="text" maxlength="25" name="login" pattern="^[a-z\d\.]{5,}$">
        <button name="submit" value="submit">Modifier</button><br /><br />
    </form>
    <form method="post"  action="./changePass.php">
        Mot de passe<br /><p class="displayMsg"><?= displayMsg(2);?></p>
        <input maxlength="25" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="passwd">
        <button name="submit" value="submit">Modifier</button><br /><br />
    </form>
    <form method="post" action="./changeMail.php">
        Adresse Mail<br /><p class="displayMsg"><?= displayMsg(3);?></p>
        <input type="email" maxlength="40" name="mail">
        <button name="submit" value="submit">Modifier</button><br /><br />
    </form>
    <form method="post" action="./notif.php" id="checkbox"><p>Notifications</p>
        <?= displayCheckbox(); ?>
        <button name="submit" value="submit">Modifier</button><br /><br />
    </form>
</div>
</div>
<?php require_once ("./templates/footer.php"); ?>
<script>
    window.onload = function(){
        var error = <?php
            if (isset($_GET["error"]) && $_GET["error"] != ""){
                echo $_GET["error"];
             }
             else{
                echo "null";
             }
        ?>;

        var msg = <?php
            if (isset($_GET["msg"]) && $_GET["msg"] != ""){
                echo (isset($_GET["error"]) ? "null" : $_GET["msg"]);
            }
            else
                echo "null";
            ?>;

        var displaymsg = document.getElementsByClassName("displayMsg");

        if (displaymsg){
            for (var i = 0; i < displaymsg.length; i++){
                if (error){
                    displaymsg[i].style.color = "red";
                }
                else{
                    displaymsg[i].style.color = "green";
                }
            }
        }
    }
</script>