<?php
    $connect = "<li><a href=\"./login.php\"><h1>Connexion</h1></a></li>";
    $moncompte = "";
    if (isset($_SESSION["userId"]))
    {
        $connect = "<li><a href=\"./disconnect.php\"><h1>Deconnexion</h1></a></li>";
        $moncompte = "<li><a href=\"./account.php\"><h1>Mon Compte</h1></a></li>";
    }
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./styles/styles.css">
	<title>Camagru</title>
</head>
<body>
	<header>
        <ul>
            <li class="logo"><a href="./index.php"><h1>Camagru</h1></a></li>
            <li><a href="./galerie.php"><h1>Galerie</h1></a></li>
            <?= $moncompte; ?>
            <?= $connect ?>
        </ul>
	</header>
