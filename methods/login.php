<?php

/*
 * Ce fichier est une methode de l'application Tindour.
 * Il est utilisé pour se connecter à un compte utilisateur lorsque l'utilisateur se connecte à
 * l'aide du formulaire de connexion sur la page index.php.
 * 
 * (c) Sacha Duvivier
 *
 */

session_start();

// on vérifie si l'utilisateur est connecté
$email = strtolower($_POST['email']);
$password = $_POST['password'];

// on hash le mot de passe en md5 pour le comparer avec celui de la base de données
$password = md5($password);

require_once 'forbidsqlinjection.php';
// on utilise htmlspecialchars pour éviter les injections de code
$email = parseSQL($email);


require_once 'database.php';

if ($conn) {
  $result = $conn->prepare("SELECT users.*,role.role_name FROM users
        LEFT JOIN role ON users.id = role.user_id
        WHERE email = '$email' AND password = '$password'");
  $result->execute();
  if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);

    // on crée les variables de session
    $_SESSION['email'] = $email;
    $_SESSION['nom'] = $row['nom'];
    $_SESSION['prenom'] = $row['prenom'];
    $_SESSION['age'] = $row['age'];
    $_SESSION['description'] = $row['description'];
    $_SESSION['longitude'] = $row['longitude'];
    $_SESSION['latitude'] = $row['latitude'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['gender'] = $row['gender'];
    $_SESSION['liked'] = $row['liked'];
    $_SESSION['id'] = $row['id'];
    $_SESSION['role'] = $row['role_name'];

    // on redirige l'utilisateur vers la page d'accueil
    header('Location: ../index.php');


  } else {
    // si l'utilisateur n'existe pas, on le redirige vers la page de connexion avec un message d'erreur
    header('Location: ../index.php?fail=1');
  }
} else {
  echo "La connexion a échoué";
}