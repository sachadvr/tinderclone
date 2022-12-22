<?php
/*
 * Ce fichier contient la fonction parseSQL pour l'application Tindour.
 * Il est utilisé pour empêcher les injections SQL et de vérifier les données entrantes.
 *
 * (c) Sacha Duvivier
 *
 */
function parseSQL($escapedtext)
{
  // on empêche les injections SQL en utilisant la fonction htmlspecialchars 
  // stripslashes et en remplacant les caractères spéciaux
  $escapedtext = htmlspecialchars($escapedtext);
  $escapedtext = stripslashes($escapedtext);
  $escapedtext = str_replace("'", "", $escapedtext);
  $escapedtext = str_replace('"', "", $escapedtext);
  $escapedtext = str_replace(";", "", $escapedtext);
  $escapedtext = str_replace("=", "", $escapedtext);
  $escapedtext = str_replace("(", "", $escapedtext);
  $escapedtext = str_replace(")", "", $escapedtext);
  $escapedtext = str_replace(">", "", $escapedtext);
  $escapedtext = str_replace("<", "", $escapedtext);
  $escapedtext = str_replace("!", "", $escapedtext);
  $escapedtext = str_replace("?", "", $escapedtext);
  $escapedtext = str_replace(":", "", $escapedtext);
  $escapedtext = str_replace("}", "", $escapedtext);
  $escapedtext = str_replace("{", "", $escapedtext);
  $escapedtext = str_replace("[", "", $escapedtext);
  $escapedtext = str_replace("]", "", $escapedtext);
  $escapedtext = str_replace("||", "", $escapedtext);
  $escapedtext = str_replace("//", "", $escapedtext);
  $escapedtext = str_replace("/*", "", $escapedtext);
  $escapedtext = str_replace("*/", "", $escapedtext);
  $escapedtext = str_replace("-", "", $escapedtext);
  $escapedtext = str_replace("\\", "", $escapedtext);
  $escapedtext = str_replace("%", "", $escapedtext);
  $escapedtext = str_replace("$", "", $escapedtext);
  $escapedtext = str_replace("#", "", $escapedtext);
  $escapedtext = str_replace("&", "", $escapedtext);
  $escapedtext = str_replace("*", "", $escapedtext);
  $escapedtext = str_replace("+", "", $escapedtext);
  $escapedtext = str_replace(",", "", $escapedtext);
  $escapedtext = str_replace("`", "", $escapedtext);
  $escapedtext = str_replace("~", "", $escapedtext);
  $escapedtext = str_replace("^", "", $escapedtext);
  $escapedtext = str_replace("_", "", $escapedtext);
  $escapedtext = str_replace("|", "", $escapedtext);
  return $escapedtext;

}
?>