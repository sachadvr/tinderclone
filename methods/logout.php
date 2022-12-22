<?php
/*
 * Ce fichier est une methode de l'application Tindour.
 * Il est utilisé pour se déconnecter de l'application.
 * 
 * (c) Sacha Duvivier
 *
 */

session_start();
session_destroy();
header('Location: ../index.php');
?>