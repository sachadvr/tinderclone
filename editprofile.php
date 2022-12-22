<?php
/*
 * Ce fichier permet l'edition de profile de l'application Tindour.
 * Il est utilisé pour modifier les informations du profil.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once 'methods\header.php';
?>
<title>Tinder Clone - Edit profile</title>

</head>

<body>
    <div class="container mx-auto">

        <div class="flex justify-center">
            <?php require_once './components/goback.component.php'; ?>
            <div class="w-1/2 max-md:w-full transition-all m-5">
                <?php
                session_start();
                require_once './components/nav.component.php';

                // si prenom, nom, description sont définis, on update le profil
                if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['description'])) {
                    require_once './methods/database.php';
                    require_once './methods/forbidsqlinjection.php';

                    //on récupère les données
                    $email = $_SESSION['email'];
                    $password = $_SESSION['password'];
                    $prenom = parseSQL($_POST['prenom']);
                    $nom = parseSQL($_POST['nom']);
                    $description = parseSQL($_POST['description']);


                    // si une image est téléchargée, on la met dans le dossier images, on remplace l'ancienne image par la nouvelle
                    if (isset($_FILES['imageToUpload'])) {

                        //si l'image est valide on continue
                        if ($_FILES['imageToUpload']['size'] > 0) {
                            if (file_exists("images/" . $email . ".jpg")) {

                                // on deplace l'image dans le dossier images avec le nom de l'utilisateur pour l'identifier
                                move_uploaded_file($_FILES['imageToUpload']['tmp_name'], "images/" . $email . ".jpg");

                                // on réduit la qualité de l'image pour la rendre plus légère
                                $image = imagecreatefromjpeg("images/" . $email . ".jpg");
                                imagejpeg($image, "images/" . $email . ".jpg", 50);
                            }
                        }
                    }

                    if ($conn) {
                        $sql = "UPDATE users SET prenom = '$prenom', nom = '$nom', description = '$description' WHERE email = '$email'";
                        if ($conn->query($sql)) {
                            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                            $result = $conn->query($sql);
                            if ($result->rowCount() > 0) {
                                $row = $result->fetch();
                                $_SESSION['nom'] = $row['nom'];
                                $_SESSION['prenom'] = $row['prenom'];
                                $_SESSION['description'] = $row['description'];
                                header('Location: editprofile.php?success=1');
                            } else {
                                header('Location: editprofile.php?success=0');
                            }
                        } else {
                            header('Location: editprofile.php?success=0');
                        }
                    }
                }


                if (isset($_GET['success']) && $_GET['success'] == 1) {
                ?>
                <div class="bg-green-500 p-4 rounded-lg text-white text-center mt-6">Modifications Effectuées</div>
                <?php }

                if (isset($_GET['success']) && $_GET['success'] == 0) {
                ?>
                <div class="bg-red-500 p-4 rounded-lg text-white text-center mt-6">Un problème est survenu</div>

                <?php }
                require_once './components/profile.component.php';
                ?>

                <!-- formulaire d'edition de profil -->
                <div class="bg-white p-6 rounded-lg mt-4 max-md w-full">
                    <h1 class="text-3xl font-bold mb-2">Modifie ton profil</h1>
                    <!-- toujours multipart/form-data pour envoyer une image -->
                    <form action="editprofile.php" method="post" enctype="multipart/form-data">
                        <div class="mb-4">

                            <!-- nom -->
                            <label for="nom" class="sr-only">Nom</label>
                            <input type="text" required name="nom" id="nom" placeholder="Nom"
                                value="<?= $_SESSION['nom'] ?>" class="bg-gray-100 border-2 w-full p-4 rounded-lg"
                                value="">
                        </div>
                        <div class="mb-4">

                            <!-- prénom -->
                            <label for="prenom" class="sr-only">Prenom</label>
                            <input type="text" required name="prenom" id="prenom" placeholder="Prénom"
                                value="<?= $_SESSION['prenom'] ?>" class="bg-gray-100 border-2 w-full p-4 rounded-lg"
                                value="">
                        </div>
                        <div class="mb-4">

                            <!-- description -->
                            <label for="description" class="sr-only">Description</label>
                            <input type="text" name="description" value="<?= $_SESSION['description'] ?>"
                                id="description" placeholder="Ta description"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>
                        <div class="mb-4">

                            <!-- image -->
                            <input type="file" name="imageToUpload" accept="image/*" id="imageToUpload"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">

                        </div>

                        <!-- bouton de validation -->
                        <button type="submit" style="background: #FD3A73"
                            class="text-white px-4 py-3 rounded font-medium w-full">Enregistrer les changements</button>
                </div>


            </div>
        </div>
    </div>
    </div>
    <?php
    require_once 'methods/footer.php'
        ?>
</body>