<?php
session_start();
if (!isset($_SESSION["userId"])){
    header ("Location: ./login.php");
    exit ;
}

include ("./db_connect.php");

function getlike(){
    global $db;

    $req = $db->prepare("SELECT id FROM likes WHERE img_id = ? AND userid = ?");
    $req->execute(array(htmlspecialchars($_POST["imgId"]), $_SESSION["userId"]));
    $id = $req->fetchAll();
    if (!$id){
        return false;
    }
    else{
        return true;
    }
}

if (isset($_POST["imgId"]) && isset($_POST["submit"]) && $_POST["submit"] == "submit" && (!($like = getlike()))){
    $req = $db->prepare("INSERT INTO likes (img_id, userid) VALUES (?, ?)");
    $req->execute(array(htmlspecialchars($_POST["imgId"]), $_SESSION["userId"]));
}
?>
<form method="post" action="./galerie.php">
    <input type="hidden" name="displayImage" value="<?= htmlspecialchars($_POST["imgId"]); ?>">
</form>
<script>
    document.querySelector("form").submit();
</script>