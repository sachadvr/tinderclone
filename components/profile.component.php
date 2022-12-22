<?php
/*
 * Ce fichier est un composant de base de l'application Tindour.
 * Il affiche le profil de l'utilisateur connecté.
 * 
 * notamment utilisé dans la page editprofile.php et 
 *
 * (c) Sacha Duvivier
 *
 */

?>
<div class="grid gap-4 bg-white rounded-sm ">
    <div class="flex flex-row gap-3 p-2 mt-5 mb-5 transition-all hover:scale-105 hover:rounded-2xl">
        <div class="img_container">

            <!--  l'image est sous la forme du [mail utilisateur].jpg 
            il faut donc récuperer le mail de l'utilisateur puis l'afficher 
            dans le src-->

            <img src="./images/<?= $_SESSION['email'] ?>.jpg" alt="profile" class="w-24 h-24  
            rounded-full object-cover">

        </div>
        <div class="text_container flex-1">
            <h1 class="text-2xl"> Ton profil </h1>
            <hr class="border-2 border-black" />
            <div class="grid">
                <div class="">

                    <!-- 
                    on affiche le nom, le prénom, l'âge et sa description
                    -->

                    <!-- nom -->
                    <h2 class="text-xl font-bold text-black">
                        <?= $_SESSION['nom'] . " " . $_SESSION['prenom'] ?>
                    </h2>

                    <!-- age -->
                    <p class="text-black">
                        <?= $_SESSION['age'] ?>ans
                    </p>

                    <!-- desc -->
                    <p class="text-black">
                        <?php
                        if (isset($_SESSION['description'])) {
                            echo $_SESSION['description'];
                        } else {
                            echo "Pas de description";
                        }
                        ?>
                    </p>

                    <!-- on permet de modifier le profile par ici -->
                    <a href="editprofile.php" class="mb-2" style="color:#FD3A73;">Modifier ton profil</a>
                </div>
            </div>

        </div>

    </div>
</div>