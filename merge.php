<?php
    session_start();
    include("./db_connect.php");
    if (isset($_SESSION["curImg"])){
        unset($_SESSION["curImg"]);
    }
    if (isset($_SESSION["curPic"])){
        unset($_SESSION["curPic"]);
    }
    if (isset($_SESSION["userId"]) && isset($_POST["finalPic"]) && isset($_POST["finalFilter"]) && isset($_POST["finalFilterPos"]) && isset($_POST["finalFilter"]) && isset($_POST["filterWidth"]) && isset($_POST["filterHeight"])) {
        $str_img = explode(",", $_POST["finalPic"]);
        $data_img = imagecreatefromstring(base64_decode($str_img[1]));
        $dataFilter = imagecreatefrompng($_POST["finalFilter"]);
        $coord = explode(";", $_POST["finalFilterPos"]);
        list ($width, $height) = getimagesize($_POST["finalFilter"]);
        $newWidth = $_POST["filterWidth"];
        $newHeight = $_POST["filterHeight"];
        $new_filter = imagecreatetruecolor($newWidth, $newHeight);

        imagealphablending($new_filter, false);
        imagesavealpha($new_filter, true);
        imagealphablending($dataFilter, true);
        imagecopyresampled($new_filter, $dataFilter, 0, 0 , 0, 0 ,$newWidth, $newHeight, $width, $height);
        imagecolortransparent($new_filter);
        imagecopy($data_img, $new_filter, $coord[0] , $coord[1] ,0, 0, $newWidth, $newHeight);
        ob_start();
        imagepng($data_img);
        $img =  ob_get_contents();
        ob_end_clean();
        $img = base64_encode($img);
        $_SESSION["curImg"] = "data:image/png;base64,".$img;
        $_SESSION["filter"] = $_POST["finalFilter"];
        unset($_SESSION["curPic"]);
    }
    header("Location: ./index.php");
?>
