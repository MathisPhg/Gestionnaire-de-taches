<?php

// Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=localhost;dbname=todolist";
    $User = "root";
    $Password = "";
    $db = new PDO($dsn, $User, $Password);
    return $db;
}

