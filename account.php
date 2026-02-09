<?php

session_start();
 
require 'connectToDB.php';
 
// securite : utilisateur connecte obligatoire
if (!isset($_SESSION['user'])){
    header('location: login.php');
    exit;
}
 
$db= connectToDB();
$userId = $_SESSION['user']['id'];
 
// ajout d'une tache
if (isset($_POST['add_task'])){
    if(!empty($_POST['title'])){
        $title = htmlspecialchars($_POST['title']);
        $urgent = isset($_POST['urgent']) ? 1:0;
        $important = isset($_POST['important']) ? 1 : 0;
        try{
            $stmt = $db->prepare(
                "INSERT INTO tasks (title, urgent, important, user_id) 
                VALUES (:title, :urgent, :important, :user_id)"
                );
                $stmt->execute([
                    ':title' =>$title,
                    ':urgent' =>$urgent,
                    ':important' =>$important,
                    ':user_id' =>$userId
        ]);
    }catch (Exception $e){
        $error = "erreur lors de l'ajout de la tache";
    }
    }
}
//marquer comme accomplie
if (isset($_GET['complete'])){
    $taskId = (int) $_GET['complete'];
    $stmt = $db->prepare(
    "UPDATE tasks SET completed = 1 WHERE id = :id AND user_id = :user_id"
);
 
$stmt->execute([
    ':id' => $taskId,
    ':user_id' => $userId
]);
 
    
    header('Location: account.php');
    exit;
}
// supprimer une tache
if (isset($_GET['delete'])){
    $taskId= (int) $_GET['delete'];
  $stmt = $db->prepare(
    "DELETE FROM tasks WHERE id = :id AND user_id = :user_id"
);
 
$stmt->execute([
    ':id' => $taskId,
    ':user_id' => $userId
]);
 
            
            header('Location: account.php');
            exit;
}
// supprimer le compte 
if (isset($_POST['delete_account'])){
    $stmt= $db->prepare(
        "DELETE FROM users WHERE id = :id"
        );
        $stmt->execute([
            ':id' =>$userId
            ]);
            session_destroy();
            header('location: index.php');
            exit;
}
//recuperation des taches 
$stmt = $db->prepare(
    "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC"
);
$stmt->execute([
    ':user_id' => $userId
]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
 
//classement eisenhower
$UI =[];
$Ui=[];
$uI=[];
$ui=[];
 
$completedCount = 0;
 
foreach ($tasks as $task) {
 
    if ($task['completed']) {
        $completedCount++;
        continue;
    }
 
    if ($task['urgent'] && $task['important']) {
        $UI[] = $task;
    } elseif ($task['urgent'] && !$task['important']) {
        $Ui[] = $task;
    } elseif (!$task['urgent'] && $task['important']) {
        $uI[] = $task;
    } else {
        $ui[] = $task;
    }
}
 
 
// template
$title ='account';
$template = './template/account.phtml';
include './template/layout.phtml';