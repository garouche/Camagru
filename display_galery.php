<?php
$req = $db->prepare("SELECT COUNT(img_blob) as nb FROM images");
$req->execute();
$nb = $req->fetch()["nb"];
$links = floor($nb / 17) + 1;
$offset = isset($_GET["page"]) ? $_GET["page"] - 1: 0;
$req = $db->prepare("SELECT img_blob FROM images ORDER BY time DESC LIMIT 16 OFFSET ".$offset * 16);
$req->execute();
$img = $req->fetchAll();

function getId($blob){
    global $db;

    $req = $db->prepare("SELECT id FROM images WHERE img_blob = ?");
    $req->execute(array($blob));
    return $req->fetch()["id"];
}

?>
<div class="galeryContent">
    <ul>
        <?php foreach ($img as $item) {
            if ($item["img_blob"]){
                echo "<li><div class='galeryImgContainer'><form class='displayImage' action='./galerie.php' method='post'><input type='hidden' name='displayImage' id='displaySrc' value='".getId($item["img_blob"])."'></form><img src='".$item["img_blob"]."'></div></li>";
            }
        };
        ?>
    </ul>

</div>
<p style="position: relative; margin-top: 1.5vh;font-size: 2vmin;">
    <?php
    for ($i = 1; $i <= $links; $i++){
        if ($links > 1) {
            echo "<a href='./galerie.php?page=" . $i . "'>" . $i . "</a>";
            if ($i != $links)
                echo " - ";
        }
    };
    ?>
</p>
<script src="./script/galery.js"></script>