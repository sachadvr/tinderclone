<?php
/*
 * ADMINISTRATION
 * ------------------------------------------------------------
 * Ce fichier est la page de confirmation de suppression
 * d'un utilisateur l'application Tindour.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once '../methods/header.php';
session_start();
?>
<title>Tinder Clone - Delete</title>
</head>

<body>
    <?php
    // on vérifie que l'utilisateur est administrateur sinon on le redirige vers l'index
    // on importe les différents composants nécessaires
    require_once 'verifadmin.php';
    require_once '../methods/database.php';
    ?>

    <div class="container flex items-center justify-center w-full h-screen">
        <div class="p-6 rounded-lg">
            <div class="flex flex-col items-center justify-center w-full">
                <?php

                //
                // si l'uilisateur n'a pas encore confirmé la suppression on affiche le message de confirmation
                //
                
                if (!isset($_GET['confirm'])) {
                ?>
                <h1 class="text-2xl font-bold">Êtes-vous sûr de vouloir supprimer cet utilisateur ?</h1>
                <h2 class="text-1xl font-bold">Cette action est irréversible</h2>
                <?php } ?>


                <div class="bg-white p-6 rounded-lg shadow-lg w-full mt-5">
                    <?php

                    // on récupère l'id de l'utilisateur à supprimer
                    
                    $id = $_GET['id'];
                    if ($conn) {

                        // requête préparée pour selection l'utilisateur en question pour afficher son nom, prénom et sa photo
                        // pour être sûr de ne pas supprimer la mauvaise personne
                        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                        $stmt->execute(['id' => $id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (isset($_GET['confirm'])) {

                            // requête préparée pour supprimer l'utilisateur en question
                            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
                            $stmt->execute(['id' => $id]);

                            // on supprime l'image associée à l'utilisateur
                            unlink("../images/" . $user['email'] . ".jpg");


                    ?>
                    <h1 class="text-2xl font-bold">
                        Vous avez bien supprimé cet utilisateur !
                    </h1>
                    <a href="./index.php" class="nav-link pl-1 pr-2 bg-red-500 text-white rounded-sm m-auto">
                        Retour à l'accueil
                    </a>
                    <?php
                            // pour éviter que le code ci-dessous s'exécute on utilise die;
                            die();
                        }

                        if ($stmt->rowCount() > 0) {
                    ?>

                    <!-- 
                            On affiche la photo, le nom et le prénom de l'utilisateur

                     -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-6 rounded-lg">
                            <!-- photo -->
                            <img src="../images/<?php echo $user['email'] ?>.jpg" alt="profile pic"
                                class="object-cover w-40 h-40 mx-auto">
                            <h1 class="text-2xl font-bold">
                                <!-- prenom & nom -->
                                <?= $user['prenom']; ?>
                                <?= $user['nom']; ?>
                            </h1>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-6 rounded-lg">

                            <!-- on demande de confirmer par un bouton -->

                            <form action="./delete.php?id=<?= $_GET['id'] ?>&confirm=true" method="POST">
                                <button type="submit" style="background-color: #FD3A73"
                                    class="text-white font-bold p-2 rounded-lg w-full">Confirmer</button>
                            </form>

                        </div>

                        <a href="./index.php" class="text-blue-500">Retour</a>
                    </div>

                    <?php } else {
                    ?>
                    <span>Cet utilisateur n'existe pas</span> <br />
                    <a href="./index.php" class="text-blue-500">Retour</a>

                    <?php
                        }

                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</body>

</html>