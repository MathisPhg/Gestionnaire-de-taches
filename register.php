<?php


require('connectToDB.php');

if(!empty($_POST)){

    $db = connectToDB();
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = array();
    
    $uppercase = preg_match("/[A-Z]/", $password);
    
    $lowercase = preg_match("/[a-z]/", $password);
    
    $number = preg_match("/[0-9]/", $password);
    
    $emailCheck = preg_match("/^[A-Za-z0-9._-]+@[A-Za-z0-9.]+[A-Za-z]+$/", $email);
    
    if (empty($username)) {
        $errors["name"] = "Please enter a Username";
    }
    
    if (empty($email) || !$emailCheck) {
        $errors["email"] = "Please enter a valide Email";
    }    
    
    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        $errors["password"] = "The password must contain a least 8 character, one lower case, one upper case and one number.";
    } else if (empty($errors)) {
        
        $mot_de_passe_hache = password_hash($password, PASSWORD_DEFAULT);
        
        
        
        $requete = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password )");
        
        $requete->bindParam(':username', $username, PDO::PARAM_STR);
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
        $requete->bindParam(':password', $mot_de_passe_hache, PDO::PARAM_STR);
        
        
       $requete->execute();
    }
    
}


//on définit le template associé à la page
$template = "register.phtml";


//on inclut le layout
include "layout.phtml";