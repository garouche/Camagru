<?php
    require_once ("./database.php");

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    }catch(PDOException $e){
        echo get_class($e);
        echo $e->getMessage();die;
    }

    $sql = "CREATE DATABASE camagru;
            USE camagru;
            CREATE TABLE users (
            userid int not null auto_increment primary key,
            login varchar(255) not null,
            passwd varchar(255) not null,
            email varchar(255) not null,
            valid boolean default 0,
            hash varchar(255),
            notif boolean default 1
            );
            CREATE TABLE images (
            id int not null AUTO_increment primary key,
            img_name varchar(255) not null,
            userid int not null,
            img_blob longtext not null,
            img_type varchar(255) not null,
            time bigint not null
            );
            CREATE TABLE comments(
            id int not null auto_increment primary key,
            img_id int not null,
            comment varchar(1024) not null,
            userid int not null
            );
            CREATE TABLE likes(
            id int auto_increment primary key,
            img_id int not null,
            userid int not null
            );";
    $db->exec("DROP DATABASE IF EXISTS camagru");
    $db->exec($sql);
    header("Location: http://localhost:8081/camagru/index.php");
?>