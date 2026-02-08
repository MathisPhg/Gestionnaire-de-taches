<?php
session_start();
$title = 'Register';

require_once 'connectToDB.php';

if(!empty($_POST)){

    $db = connectToDB();
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = array();
    $didExist = array();
    
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
         
         
         try {
              
              $checkExist = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
              
              $checkExist->bindParam(":username", $username, PDO::PARAM_STR);
              
              $checkExist->execute();
              
              $doExist["user"] =  $checkExist->fetchColumn();
              
              
              if($doExist["user"] > 0) {
                   
                   $errors["name"] = "Username already existe";
                   
                }
              
              
              
              
              $checkExist = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
              
              $checkExist->bindParam(":email", $email, PDO::PARAM_STR);
              
              $checkExist->execute();
              
              $doExist["email"] =  $checkExist->fetchColumn();
              
              
              if($doExist["email"] > 0) {
                   
                   $errors["email"] = "Email already use";
                   
                }
              
              
               $mot_de_passe_hache = password_hash($password, PASSWORD_DEFAULT);
               
               $requete = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password )");
               
               $requete->bindParam(':username', $username, PDO::PARAM_STR);
               $requete->bindParam(':email', $email, PDO::PARAM_STR);
               $requete->bindParam(':password', $mot_de_passe_hache, PDO::PARAM_STR);
               
               $requete->execute();
               
               $userId = $db->lastInsertId();
               
               
               $_SESSION['user'] = [
                    'id' => $userId,
                    'username' => $username,
                    'email' => $email,
                    'created_at' => date("d-m-Y")
               ];
               
               
               
               header('Location: account.php');
               exit;
              
              
            } catch (Exception $e) {
              
              $errors["database"] = "Please try another username or email";
              
          }
     
     
     
     
     
     
     
     
     
     
        
    }
    
}


//on définit le template associé à la page
$template = "./template/register.phtml";


//on inclut le layout
include "./template/layout.phtml";