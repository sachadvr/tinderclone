<?php
/*
 * ADMINISTRATION
 * ------------------------------------------------------------
 * Cette fonctionnalité permet d'empecher
 * l'accès à la page d'administration si l'utilisateur
 * n'est pas administrateur.
 * 
 * (c) Sacha Duvivier
 *
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../index.php");
}
?>