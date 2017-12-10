<?php
session_start();
$error = "";
$ext = pathinfo($_FILES['sendPic']['name'], PATHINFO_EXTENSION);
$src = "";
function validExt(){
    global $ext;
    $validExt = array("jpg", "jpeg", "png");

    if (!in_array($ext, $validExt)){
        return false;
    }
    return true;
}

if (!validExt() || !exif_imagetype($_FILES['sendPic']['tmp_name'])){
    $error = "?error=1";
}

else if ($_FILES["sendPic"]["size"] > 2097152){
    $error = "?error=2";
}

if (!$error){
    $imgDest = imagecreatetruecolor($_POST["width"], $_POST["height"]);
    if ($ext =="jpeg" || $ext == "jpg") {
        $src = imagecreatefromjpeg($_FILES["sendPic"]["tmp_name"]);
    }
    else if ($ext == "png"){
        $src = imagecreatefrompng($_FILES["sendPic"]["tmp_name"]);
    }
    list($width, $height) = getimagesize($_FILES["sendPic"]["tmp_name"]);
    imagecopyresampled($imgDest, $src, 0, 0, 0, 0, $_POST["width"], $_POST["height"], $width, $height);
    ob_start();
    imagejpeg($imgDest);
    $img = ob_get_contents();
    ob_end_clean();
    $img = "data:image/png;base64,".base64_encode($img);
    $_SESSION["curPic"] = $img;
}
    header("Location: index.php".$error);
?>
