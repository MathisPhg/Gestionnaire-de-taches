<?php

// Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=localhost;dbname=todolist";
    $db = new PDO($dsn, "root", "");
    return $db;
}

