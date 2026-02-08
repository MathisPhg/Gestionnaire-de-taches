<?php
session_start();
require_once 'connectToDB.php';

$db = connectToDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        // Vérification des champs
        if (
            empty($_POST['username']) ||
            empty($_POST['email']) ||
            empty($_POST['password'])
        ) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        // Vérification email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }

        // Recherche de l'utilisateur
        $stmt = $db->prepare(
            "SELECT * FROM users WHERE username = ? AND email = ?"
        );
        $stmt->execute([
            $_POST['username'],
            $_POST['email']
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification mot de passe
        if (!$user || !password_verify($_POST['password'], $user['password'])) {
    throw new Exception("Identifiants incorrects");
}


        // Connexion réussie → session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ];

        // Redirection
        header('Location: account.php');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$title = 'Connexion';


//on définit le template associé à la page
$template = "./template/login.phtml";


//on inclut le layout
include "./template/layout.phtml";

?>


