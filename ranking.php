<?php


require_once 'connectToDB.php';

$db = connectToDB();

$leaderBoard = $db->prepare("SELECT users.username, COUNT(tasks.id) AS completed_tasks FROM users INNER JOIN tasks ON users.id = tasks.user_id WHERE tasks.completed = 1 GROUP BY users.username ORDER BY completed_tasks DESC LIMIT 5");

$leaderBoard->execute();

$rankedUsers =  $leaderBoard->fetchAll();







$title = 'ranking';

//on définit le template associé à la page
$template = "./template/ranking.phtml";

//on inclut le layout
include "./template/layout.phtml";