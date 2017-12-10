<?php
    require_once ("./config/database.php");
    try {
        $db = new PDO($DB_DSN.'dbname=camagru', $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo get_class($e);
        echo $e->getMessage();die;
    }
?>
