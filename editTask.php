<?php
session_start();
require 'connectToDB.php';


//verif si l'user s'est connecté 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = connectToDB();


//verif que la tache a un id 
if (!isset($_GET['id'])) {
    header('Location: account.php');
    exit;
}

$taskId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];




//recup la tâche + verif le createur 
$request = $db->prepare("
    SELECT * FROM tasks 
    WHERE id = :id AND user_id = :user_id
");
$request->execute([
    ':id' => $taskId,
    ':user_id' => $userId
]);
$task = $request->fetch(PDO::FETCH_ASSOC);



//SI task appartient a personne / existe pas
if (!$task) {
    header('Location: account.php');
    exit;
}

//update 
if (!empty($_POST)) {

    $title = trim($_POST['title']);
    $urgent = isset($_POST['urgent']) ? 1 : 0;
    $important = isset($_POST['important']) ? 1 : 0;
    $completed = isset($_POST['completed']) ? 1 : 0;

    $update = $db->prepare("
        UPDATE tasks SET 
        title = :title,
        urgent = :urgent,
        important = :important,
        completed = :completed
        WHERE id = :id AND user_id = :user_id
    ");

    $update->execute([
        ':title' => $title,
        ':urgent' => $urgent,
        ':important' => $important,
        ':completed' => $completed,
        ':id' => $taskId,
        ':user_id' => $userId
    ]);

    header('Location: account.php');
    exit;
}


$template = './template/editTask.phtml';
include 'template/layout.phtml';
