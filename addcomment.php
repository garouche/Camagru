<?php
    session_start();
    if (!isset($_SESSION["userId"]))
    {
        header ("Location: ./login.php");
        exit ;
    }
    include ("./db_connect.php");
    $img = $_POST["imgId"];
    if (isset($_POST["imgId"]) && isset($_POST["comment"]) && strlen($_POST["comment"]) <= 150 && isset($_POST["submit"]) && $_POST["submit"] == "submit" && $_POST["comment"] != ""){
        $req = $db->prepare("INSERT INTO comments (img_id, comment, userid) VALUES (?, ?, ?)");
        $req->execute(array(htmlspecialchars($_POST["imgId"]), htmlspecialchars($_POST["comment"]), htmlspecialchars($_SESSION["userId"])));

        $req = $db->prepare("SELECT notif FROM users WHERE userid = ?");
        $req->execute(array($_SESSION["userId"]));
        $ret = $req->fetch()["notif"];
        if ($ret) {
            $req = $db->prepare("SELECT email FROM users LEFT JOIN images ON users.userid = images.userid WHERE images.id = ?");
            $req->execute(array(htmlspecialchars($_POST["imgId"])));
            $mail = $req->fetch()["email"];
            $req = $db->prepare("SELECT login FROM users WHERE userid = ?");
            $req->execute(array($_SESSION["userId"]));
            $login = $req->fetch()["login"];
            $ret = mail($mail, "Nouveau commentaire", wordwrap($login . " a commente votre photo\n '" . $_POST["comment"] . "'", 70, "\r\n"));
        }
    }
?>
<form method="post" action="./galerie.php">
    <input type="hidden" name="displayImage" value="<?= $img; ?>">
</form>
<script>window.onload = function(){
    document.querySelector("form").submit();
    }
</script>
