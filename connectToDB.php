<?php

// Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=db.3wa.io;port=3306;dbname=mathisphuong_todolist;charset=utf8";
    $User = "mathisphuong";
    $Password = "81efb03e36b8ef8a7a00bf159d5bd77e";
    $db = new PDO($dsn, $User, $Password);
    return $db;
}



/*

$db = new PDO(
    'mysql:host=db.3wa.io;port=3306;dbname=mathisphuong_todolist;charset=utf8',
    'mathisphuong',
    '81efb03e36b8ef8a7a00bf159d5bd77e'
);

*/

