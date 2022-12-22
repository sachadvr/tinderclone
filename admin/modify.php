<?php
/*
 * ADMINISTRATION
 * ------------------------------------------------------------
 * Ce fichier permet la modification des utilisateurs l'application Tindour.
 * Il est utilisé pour afficher un utilisateur et le modifier.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once '../methods/header.php';
session_start();
?>
<title>Tinder Clone - Administration</title>

</head>

<body>
    <?php
    // on vérifie que l'utilisateur est administrateur sinon on le redirige vers l'index
    // on importe les différents composants nécessaires
    require_once 'verifadmin.php';
    require_once '../components/nav.component.php';
    require_once '../methods/database.php';
    require_once '../components/goback.component.php';
    if (isset($_GET['id'])) {
        if (isset($_POST['nom'])) {
    ?>

    <!-- s'il y a des modifications on affiche une bannière -->
    <div class="bg-green-500 p-4 rounded-lg text-white text-center m-6">Modifications effectuées.</div>
    <?php

            // on récupère les données du formulaire car modification il y a
    
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $description = $_POST['description'];
            $age = $_POST['age'];

            // on recupère l'id de l'utilisateur à modifier
            $id = $_GET['id'];

            // on modifie l'utilisateur dans la base de données avec les nouvelles données
            $stmt = $conn->prepare("UPDATE users SET nom = :nom, prenom = :prenom, email = :email, age = :age, description = :description, gender = :gender WHERE id = :id");
            $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'age' => $age, 'description' => $description, 'id' => $id, 'gender' => $gender]);
        }
        $id = $_GET['id'];

        // on fait une requête pour afficher les informations de l'utilisateur avec son rôle s'il en a un
        $stmt = $conn->prepare("SELECT users.*,role.role_name FROM users LEFT JOIN role ON users.id = role.user_id
    where users.id = $id");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="flex flex-col items-center justify-center ">
        <div class="bg-white p-6 rounded-lg w-full">

            <form method="POST" class="p-5 shadow-md rounded-sm text-black
            focus:border-black focus:border border

            <?php if (isset($user['role_name'])) {
                echo "border-red-500";
            } ?>
            " tabindex="0">

                <div class="flex flex-col relative">
                    <img src="../images/<?= $user['email'] ?>.jpg " alt="profile picture" class="w-12 h-12">
                    <!-- id -->
                    <span class="text-sm absolute right-0 top-0">id: <?= $user['id'] ?></span>
                    <?php
            if (isset($user['role_name'])) {
                    ?>
                    <span class="text-red-500 font-bold">
                        [<?= strtoupper($user['role_name']) ?>]<br /> </span>
                    <?php } ?>
                    <h1 class="font-bold">
                        <?php

                        ?>

                        <div>

                            <input type="text" name="nom" value="<?= $user['nom'] ?>"
                                class="bg-transparent border focus:outline-none w-full max-w-full" />
                            <input type="text" name="prenom" value="<?= $user['prenom'] ?>"
                                class="bg-transparent border focus:outline-none w-full max-w-full" />
                            <input type="text" name="age" value="<?= $user['age'] ?>"
                                class="bg-transparent border w-full max-w-full  focus:outline-none" />
                        </div>
                    </h1>
                    <input type="text" name="email" value="<?= $user['email'] ?>"
                        class="bg-transparent border focus:outline-none w-full max-w-full" />
                    <!-- select option -->
                    <select name="gender" id="gender"
                        class="bg-transparent border focus:outline-none w-full max-w-full">
                        <option value="m" <?php if ($user['gender']=="m") { echo "selected"; } ?>>Homme</option>
                        <option value="f" <?php if ($user['gender']=="f") { echo "selected"; } ?>>Femme</option>
                        <option value="n" <?php if ($user['gender']=="n") { echo "selected"; } ?>>Non Binaire
                        </option>
                    </select>
                    <input type="text" name="description" value="<?= $user['description'] ?>"
                        class="bg-gray-300 p-3 border focus:outline-none w-full max-w-full" />
                    <button type="submit" class="bg-green-500 text-white p-3 rounded-md w-fit mt-3">Enregistrer</button>
                    <a href="delete.php?id=<?= $user['id'] ?>" class="text-red-500">Supprimer</a>
                </div>
            </form>
        </div>
    </div>
    <?php
        } else {
            echo "Aucun utilisateur trouvé";

        }

    }
    require_once '../methods/footer.php';
    ?>
</body>