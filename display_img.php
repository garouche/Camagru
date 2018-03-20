<?php
    $req = $db->prepare("SELECT img_blob FROM images WHERE id = ?");
    $req->execute(array(htmlspecialchars($_POST["displayImage"])));
    $img = $req->fetch()["img_blob"];
    $previous = "";
    $next = "";
    $req = $db->prepare("SELECT id FROM images ORDER BY id ASC LIMIT 1");
    $req->execute();
    $first = $req->fetch()["id"];
    $req = $db->prepare("SELECT id FROM images ORDER BY id DESC LIMIT 1");
    $req->execute();
    $last = $req->fetch()["id"];
    $likeButton = "";
    $deleteButton = "";

    if ($_POST["displayImage"] != $first){
        $req = $db->prepare("SELECT id FROM images WHERE id < ?  ORDER BY id DESC LIMIT 1");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        $previousId = $req->fetch()["id"];
        $previous = "<form action='./galerie.php' method='post'>
                        <input type='hidden' name='displayImage' value='".$previousId."'>
                        <button><img id='leftArrow' src='./pictures/right_arrow.png'></button>
                        </form>";
    }

    if ($_POST["displayImage"] != $last){
        $req = $db->prepare("SELECT id FROM images WHERE id > ?  ORDER BY id ASC LIMIT 1");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        $nextId = $req->fetch()["id"];
        $next = "<form action='./galerie.php' method='post'>
                    <input type='hidden' name='displayImage' value='".$nextId."'>
                    <button><img id='rightArrow' src='./pictures/left_arrow.png'></button>
                    </form>";
    }

    function allowDelete(){
        global $db;

        $req = $db->prepare("SELECT id FROM images WHERE id = ? AND userid = ?");
        $req->execute(array(htmlspecialchars($_POST["displayImage"]), $_SESSION["userId"]));
        $img = $req->fetch()["id"];

        if ($img){
            return true;
        }
        else{
            return false;
        }
    }

    if (allowDelete()){
        $deleteButton = "<form method='post' action='./delete.php' >
                         <input type='hidden' name='imgId' value='".$_POST["displayImage"]."'>
                         <button class='styleButton' id='deleteButton' name='submit' value='submit'>Supprimer</button>
                         </form>";
    }

    function userComment(){
        global $db;
        $req = $db->prepare("SELECT login FROM users LEFT JOIN comments ON users.userid = comments.userid WHERE comments.img_id = ?");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        return $req;
    }

    function displayComment(){
        global $db;
        $req = $db->prepare("SELECT comment FROM comments WHERE img_id = ?");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        $comment = $req->fetchAll();
        $user = userComment();
        foreach ($comment as $elem){
            if ($elem["comment"]){
                if (!($login = $user->fetch()["login"])){
                    $login = "Utilisateur Supprime";
                }
                echo "<li>".$login.": <i>".$elem["comment"]."</i></li>";
            }
        };
    }

    function nbComment(){
        global $db;

        $req = $db->prepare("SELECT COUNT(comment) as nb FROM comments WHERE img_id = ?");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        return $req->fetch()["nb"];
    }

    $nbComment = nbComment();

    function getlike(){
        global $db;
        $id = NULL;

        if (isset($_SESSION["userId"])) {
            $req = $db->prepare("SELECT id FROM likes WHERE img_id = ? AND userid = ?");
            $req->execute(array(htmlspecialchars($_POST["displayImage"]), $_SESSION["userId"]));
            $id = $req->fetchAll();
        }
        if (!$id){
            return false;
        }
        else {
            return true;
        }
    }

    if (!getlike() && isset($_SESSION["userId"])){
        $likeButton = "<form method=\"post\" action=\"./addlike.php\">
                <input type=\"hidden\" name=\"imgId\" value='" . $_POST["displayImage"] . "'>
                <button id=\"likeButton\" name='submit' value='submit'>J'aime &hearts;</button></form>";
    }

    function nbLike(){
        global $db;

        $req = $db->prepare("SELECT COUNT(id) as nb FROM LIKES WHERE img_id = ?");
        $req->execute(array(htmlspecialchars($_POST["displayImage"])));
        return $req->fetch()["nb"];
    }
?>
<div class="displayImgContainer">
    <?= $next; ?>
    <img src="<?=$img; ?>" id="displayImage">
    <?= $previous; ?>
</div>
<div id="likes"><?= $deleteButton; ?><?= $likeButton; ?><p><?= nbLike();?> &hearts;<h3><?= $nbComment; ?>  Commentaire(s)</h3></p></div>

<div class="bottomDisplayImg">
    <ul class="commentContainer">
        <?php displayComment(); ?>
    </ul>

    <form method="post" id="commentForm" action="./addcomment.php">
        <h3>Ajouter un commentaire</h3>
        <textarea maxlength="150" name="comment"></textarea>
        <input type="hidden" name="imgId" value="<?= $_POST["displayImage"]; ?>">
        <button name="submit" value="submit">Ajouter</button>
</form>
</div>
<script src="./script/galery.js"></script>