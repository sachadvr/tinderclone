<?php
/*
 * Ce fichier est la methode la plus importante de l'application Tindour.
 * Utilisée pour la connexion à la base de données initiale.
 *
 * (c) Sacha Duvivier
 *
 */

$conn = new PDO("mysql:host=localhost;dbname=tinder", "root", "");
$conn->exec("set names utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>