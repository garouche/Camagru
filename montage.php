<?php
    include("./db_connect.php");
    $error = "";
    $valid = "";
    $image = "";
    $upPic = "";
    $validUp = "";


    function displayFilters(){
        $dir = opendir("./filters");
        while (false !== ($filtre = readdir($dir))){
                if ($filtre != "." && $filtre != "..") {
                    echo "<button class=\"stickCursor\"><img src=\"./filters/".$filtre."\" class=\"mouseFollow\"></button>";
                }
            }
    }
    if (isset($_GET["error"])){
        if ($_GET["error"] == 1)
            $error = "<div class='upError'>Fichier invalide.</div>";
        else if ($_GET["error"] == 2)
            $error = "<div class='upError'>Fichier trop gros.</div>";
        else
            $error = "<div class='upError'>Erreure inconnue.</div>";
    }

    if (isset($_SESSION["curPic"])){
        $upPic = "<img src='".$_SESSION["curPic"]."' id='upPic'>";
        $validUp = "<form action='./merge.php' method='post'>
            <input type='hidden' id='finalUpFilter' name='finalFilter'>
            <input type='hidden' id='finalUpWidth' name='filterWidth'>
            <input type='hidden' id='finalUpHeight' name='filterHeight'>
            <input type='hidden' id='upFilterPos' name='finalFilterPos'>
            <input type='hidden' id='finalUpPic' name='finalPic'>
            <button class=\"styleButton\" id=\"buttonUpPic\" disabled>
                <img src=\"./pictures/valid.png\"><p>Finaliser montage</p>
            </button>
        </form>";
    }

    if (isset($_SESSION["curImg"])) {
        $valid = "    <form action='./upload.php' method='post'>
                            <input type='hidden' name='Pic' value='erase'>
                            <button class='styleButton' id='eraseButton'><img src='./pictures/delete.png'><p>Effacer</p></button>
                      </form>
                      <form action='./upload.php' method='post'>
                            <input type='hidden' id='Pic2' name='Pic'>
                            <button class='styleButton' id='publishButton'><img src='./pictures/valid.png'><p>Publier</p>
                            </button>
                      </form>";
        $image = $_SESSION["curImg"];
    }
        $req = $db->prepare("SELECT img_blob FROM images ORDER BY time DESC");
        $req->execute();
        $img = $req->fetchAll();
?>
<div class="mainContent">
    <div class="videoContent">
        <?= $upPic; ?>
        <img src='<?= $image; ?>' id='pic'>
        <video autoplay="true" id="videoElement">
        </video>
        <canvas id="canvas"></canvas>
    </div>
    <span class="displayContent">
            <legend>Photos</legend>
        <ul>
            <?php
                    foreach ($img as $elem)
                        if ($elem["img_blob"])
                            echo "<li><img class='minPic' src='" . $elem["img_blob"] . "'></li>";
            ?>
        </ul>
        </span>
</div>
<?= $error; ?>

<div style="width:100%;display:flex;text-align:center;justify-content: center;height:29vh;">
<div class="filtreContainer">
    <ul>
        <?php displayFilters(); ?>
    </ul>
    <div class="montageButton">
        <form action='./merge.php' method='post'>
            <input type='hidden' id='finalFilter' name='finalFilter'>
            <input type='hidden' id='filterWidth' name='filterWidth'>
            <input type='hidden' id='filterHeight' name='filterHeight'>
            <input type='hidden' id='finalFilterPos' name='finalFilterPos'>
            <input type='hidden' id='finalPic' name='finalPic'>
            <button class="styleButton" id="takePic" disabled>
                <img src="./pictures/apn.png"><p>Prendre une photo</p>
            </button>
        </form>
        <form method="post" action="./upPic.php" enctype="multipart/form-data" id="uploadForm">
        <button class="styleButton" id="uploadPic" >
            <input type="hidden" name="width" id="tmpUpWidth" />
            <input type="hidden" name="height" id="tmpUpHeight" />
            <input type="file" name="sendPic" class="input-file">
            <input type="hidden" name="test" value="lol">
            <img src="./pictures/upload.png" ><p>Uploader une image</p>
        </button>
        </form>
        <?= $valid; ?>
        <?= $validUp; ?>
    </div>
</div>
</div>
<script src="./script/script.js"></script>
