<?php
/*
 * Ce fichier est un composant de base de l'application Tindour.
 * Il est utilisé pour afficher le menu de navigation de l'application.
 *
 * (c) Sacha Duvivier
 *
 */



if (isset($_SESSION['email'])) {

?>
<div class="absolute top-0 right-0 m-1 flex gap-3 items-center">
    <!-- on affiche le prénom avec un lien pour éditer le profile -->
    <a href="/editprofile.php">
        <?php echo $_SESSION['prenom']; ?>
    </a>

    <!-- bouton pour se déconnecter -->
    <a href="/methods/logout.php" style="background: #FD3A73" class="text-white px-3 py-2 rounded font-medium w-full">
        Se déconnecter
    </a>
</div>


<?php
}
?>

<div class="bg-white p-6 rounded-lg">
    <div class="flex items-center mt-4 justify-center gap-3 mb-3">
        <img src="https://uploads-ssl.webflow.com/619d152dea16aa3c8cc54252/61f0557eeca991e4a4aa6c45_flame-red-RGB%201.webp"
            alt="logo" width="20" height="20">
        <h1 class="text-2xl  font-bold text-center" style="color:#FD3A73">Tindour</h1>

    </div>
    <?php
    if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    ?>
    <!-- si l'utilisateur est un admin, il voit désormais un texte "ADMIN PANEL" 
    qui permet de aller vers /admin/ -->
    <a href="/admin/" class="nav-link pl-1 pr-2 bg-red-500 text-white rounded-sm m-auto">Admin Panel</a>
    <?php
    } ?>

    <!-- petite description de l'app -->
    <p class="text-gray-500">Un clone de tinder fait avec PHP et Tailwind CSS</p>
</div>