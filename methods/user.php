<?php
/*
 * Ce fichier est une methode de l'application Tindour.
 * Il est utilisé pour selectionner les utilisateurs de la base de données.
 * Et stocker les données dans des variables.
 *
 * (c) Sacha Duvivier
 *
 */

require_once 'database.php';
if (!isset($_SESSION['email'])) {
  session_start();
}
function add_to_likes($liked)
{
  global $conn;
  if ($conn) {

    $stmt = $conn->prepare("INSERT INTO likes (user_id,liked_id) VALUES (:user_id, :liked_id)");
    $stmt->execute(['user_id' => $_SESSION['id'], 'liked_id' => $liked]);

    if ($stmt->rowCount() > 0) {
      header('Location: ../index.php');
    } else {
      echo "Erreur";
    }
  }
}
function add_to_dislike($disliked)
{
  global $conn;
  if ($conn) {

    $stmt = $conn->prepare("INSERT INTO dislike (user_id,disliked_id) VALUES (:user_id, :disliked_id)");
    $stmt->execute(['user_id' => $_SESSION['id'], 'disliked_id' => $disliked]);

    if ($stmt->rowCount() > 0) {
      header('Location: ../index.php');
    } else {
      echo "Erreur";
    }
  }
}
function fetch_new_profile()
{
  global $conn;

  if ($conn) {
    // si il like quelqu'un, on l'ajoute dans la table likes
    if (isset($_GET['liked'])) {
      add_to_likes($_GET['liked']);
    }
    // si il dislike quelqu'un, on l'ajoute dans la table dislike
    if (isset($_GET['disliked'])) {
      add_to_dislike($_GET['disliked']);
    }

    // on reecupere les utilisateurs qui correspondent aux criteres de recherche
    $gender = "('m','f','n')";
    switch ($_SESSION['gender']) {
      case 'm':
        $gender = "('f','n')";
        break;
      case 'f':
        $gender = "('m','n')";
        break;
      case 'n':
        $gender = "('m','f','n')";
        break;
      default:
        $gender = "('m','f','n')";
        break;
    }
    // cette requete permet de recuperer les utilisateurs qui correspondent aux criteres de recherche 
    // on exclut l'utilisateur connecté de la requete et on exclut les utilisateurs qui ont deja ete like ou dislike
    // on randomise les utilisateurs et on limite le nombre de resultat a 1

    $stmt = $conn->prepare("SELECT * from users WHERE id NOT IN (SELECT liked_id from likes WHERE user_id = :user_id) and 
        id NOT IN (SELECT disliked_id from dislike WHERE user_id = :user_id) and 
        gender in $gender and id != :user_id ORDER BY RAND() LIMIT 1");

    $stmt->execute(['user_id' => $_SESSION['id']]);



    if ($stmt->rowCount() > 0) {
      // on sauvagarde les données dans des variables de l'utilisateur trouvé
      global $profile_nom, $profile_id, $profile_prenom, $profile_email, $profile_age, $profile_description, $profile_longitude, $profile_latitude, $profile_match;
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $profile_id = $row['id'];
      $profile_nom = $row['nom'];
      $profile_prenom = $row['prenom'];
      $profile_email = $row['email'];
      $profile_age = $row['age'];
      $profile_description = $row['description'];
      $isamatch = $conn->prepare("SELECT * FROM `users` Where id In (SELECT liked_id From likes Where user_id = :user_id) and id = :id ");
      $isamatch->execute(['id' => $_SESSION['id'], 'user_id' => $profile_id]);
      $profile_match = $isamatch->rowCount();

    } else {
      echo 'Bien joué! Vous avez vu tous les profils!';
    }



  } else {
    echo "Erreur de connexion";
  }
}
fetch_new_profile();