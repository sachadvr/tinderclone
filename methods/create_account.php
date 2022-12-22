<?php
/*
 * Ce fichier est une methode de l'application Tindour.
 * Il est utilisé pour créer un compte utilisateur lorsque l'utilisateur s'inscrit à
 * l'aide du formulaire d'inscription sur la page register.php.
 * 
 * (c) Sacha Duvivier
 *
 */

// on se connecte à la base de données
require_once 'database.php';

// check if there is POST data if not redirect to index.php
if (!isset($_POST['nom'])) {
  header('Location: ../index.php');
}
// on récupère les données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = strtolower($_POST['email']);
$password = $_POST['password'];
$age = $_POST['age'];
$description = $_POST['description'];
$gender = $_POST['gender'];

// on hash le mot de passe en md5
$password = md5($password);

require_once 'forbidsqlinjection.php';
$nom = parseSQL($nom);
$prenom = parseSQL($prenom);
$email = parseSQL($email);
$password = parseSQL($password);
$age = parseSQL($age);
$description = parseSQL($description);
$gender = parseSQL($gender);


// on upload l'image dans le dossier images avec le mail de l'utilisateur comme nom .jpg
if (isset($_FILES['imageToUpload'])) {
  // uniqueid with email
  move_uploaded_file($_FILES['imageToUpload']['tmp_name'], "../images/" . $email . ".jpg");
  // save file with 50% quality
  $image = imagecreatefromjpeg("../images/" . $email . ".jpg");
  imagejpeg($image, "../images/" . $email . ".jpg", 50);

} else {
  die("image not found!");
}

if ($conn) {
  $sql = "SELECT * FROM users WHERE email =  '$email'";
  $result = $conn->query($sql);

  if ($result->rowCount() > 0) {
    // on check si l'utilisateur existe déjà
    header('Location: ../register.php?fail=1');
  } else {
    // si l'utilisateur n'existe pas, on l'ajoute à la base de données
    $stmt = $conn->prepare("INSERT INTO users (nom, prenom, gender, email, password, age, description) VALUES ('$nom', '$prenom', '$gender', '$email', '$password', '$age', '$description')");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      // on redirige l'utilisateur vers la page de connexion
      header('Location: ../index.php?created=1');

    } else {
      // si l'utilisateur n'existe pas, on le redirige vers la page de connexion avec un message d'erreur
      header('Location: ../register.php?fail=1');
    }
  }
} else {
  echo "La connexion a échoué";
}