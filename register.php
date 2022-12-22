<?php
/*
 * Ce fichier est le formulaire d'enregistrement de l'application Tindour.
 * Il fait appel à la methode create_account.php pour créer un compte.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once 'methods\header.php';
?>
<title>Tinder Clone - Enregistrement</title>
</head>

<body>
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-1/2 max-md:w-full transition-all">
                <?php require_once './components/nav.component.php';
                require_once './components/goback.component.php'; ?>
                <?php
                session_start();

                if (isset($_GET['fail'])) {
                    if ($_GET['fail'] == 1) {
                ?>
                <!-- Si une erreur est arrivée, on affiche le message d'erreur -->
                <div class="bg-red-500 p-4 rounded-lg text-white text-center mt-6">Un compte existe déjà
                    avec cette adresse email, veuillez en choisir une autre.</div>
                <?php
                    }
                }

                // si l'utilisateur n'est pas connecté, on affiche le formulaire
                if (!isset($_SESSION['email'])) { ?>
                <div class="bg-white p-6 rounded-lg mt-4 m-5">
                    <h1 class="text-3xl font-bold mb-2">Enregistrement</h1>

                    <!-- on utilise multipart/form-data pour permettre de tranmettre l'image -->
                    <form action="./methods/create_account.php" method="post" enctype="multipart/form-data">

                        <!-- nom -->
                        <div class="mb-4">
                            <label for="nom" class="sr-only">Nom</label>
                            <input type="text" required name="nom" id="nom" placeholder="Nom"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- prénom -->
                        <div class="mb-4">
                            <label for="prenom" class="sr-only">Prenom</label>
                            <input type="text" required name="prenom" id="prenom" placeholder="Prénom"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- choisir un sexe -->
                        <div class="mb-4">
                            <label for="gender" class="sr-only">Gender</label>
                            <select name="gender" id="gender" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                                <option value="m">Homme</option>
                                <option value="f">Femme</option>
                                <option value="n">Non-Binaire</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="sr-only">Email</label>
                            <input type="text" required
                                pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"
                                (?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])"
                                name="email" id="email" placeholder="Email"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- Mot de passe qui sera chiffré en MD5 -->
                        <div class="mb-4">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" required name="password" id="password" placeholder="Mot de passe"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- Age -->
                        <div class="mb-4">
                            <label for="age" class="sr-only">Age</label>
                            <input type="number" name="age" id="age" required pattern="[0-9]{2}" placeholder="Age"
                                min="18" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="sr-only">Description</label>
                            <input type="text" name="description" id="description" placeholder="Ta description"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>

                        <!-- Selection d'image -->
                        <div class="mb-4">
                            <input type="file" name="imageToUpload" accept="image/*" required id="imageToUpload"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">

                        </div>

                        <!-- Bouton d'inscription -->
                        <div class="mb-4">
                            <button type="submit" style="background: #FD3A73"
                                class="text-white px-4 py-3 rounded font-medium w-full">
                                S'inscrire
                            </button>
                        </div>
                    </form>

                    <?php
                } else {
                    ?>
                    <!-- SI déjà connecté on affiche une erreur -->
                    <div class="bg-red-500 p-4 rounded-lg text-white text-center mt-6">
                        Erreur, Tu es déjà connecté.
                    </div>
                    <?php
                }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    require_once('./methods/footer.php');
    ?>

</body>