<?php
/*
 * ADMINISTRATION
 * ------------------------------------------------------------
 * Ce fichier est l'accueil de l'administration de l'application Tindour.
 * Il est utilisé pour afficher les différentes options de l'administration. 
 * Notamment, le tri, la suppression et la modification des utilisateurs.
 * Ainsi qu'avoir accès aux signalements.
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
    require_once '../components/goback.component.php'; ?>
    <div class="flex flex-col items-center justify-center">
        <div class="bg-white p-6 rounded-lg">
            <!-- 
            on permet de trier les utilisateurs par un ordre spécifique 
            ex: trier par nom, par age, par gendre, par mail..

            -->
            <label for="sortby">Trier par:</label>

            <!-- 

            on récupère la valeur du tri dans la variable $_GET['order']
            on selectionne l'option correspondante dans le select
            dès lors qu'on change la valeur du select, on redirige vers la même page avec la nouvelle valeur de tri
            
             -->
            <select name="sortby" id="sortby" class="bg-white border border-gray-300 rounded-full p-1">
                <option value="id" <?php if (isset($_GET['order']) && $_GET['order']=='id') echo 'selected'; ?>>
                    ID</option>
                <option value="nom" <?php if (isset($_GET['order']) && $_GET['order']=='nom') echo 'selected'; ?>>
                    Nom</option>
                <option value="prenom" <?php if (isset($_GET['order']) && $_GET['order']=='prenom') echo 'selected'; ?>>
                    Prénom</option>
                <option value="age" <?php if (isset($_GET['order']) && $_GET['order']=='age') echo 'selected'; ?>>
                    Age</option>
                <option value="gender" <?php if (isset($_GET['order']) && $_GET['order']=='gender') echo 'selected'; ?>>
                    Sexe</option>
                <option value="email" <?php if (isset($_GET['order']) && $_GET['order']=='email') echo 'selected'; ?>>
                    Mail</option>
            </select>



        </div>
        <div class="bg-white p-6 rounded-lg">
            Afficher par:
            <a href="?signalement=oui"
                class="nav-link pl-1 pr-2 bg-red-500 text-white rounded-sm m-auto">Signalements</a>
        </div>
        <div class="grid md:grid-cols-4 gap-4">
            <?php
            if ($conn) {
                // on ordonne les utilisateurs par l'ordre spécifié dans la variable $_GET['order'] 
                // par défaut, on ordonne par l'id
                $order = 'id';
                if (isset($_GET['order'])) {
                    $order = $_GET['order'];
                }


                // on change la requête en fonction de si on veut afficher les signalements ou non
                if (isset($_GET['signalement'])) {
                    $sql = "SELECT users.id, users.nom, users.prenom, users.age, users.gender, users.description, users.email, report.reason
                    FROM users RIGHT JOIN report ON users.id = report.user_id ORDER BY $order";
                } else {
                    $sql = "SELECT users.*,role.role_name
                    FROM users LEFT JOIN role ON users.id = role.user_id ORDER BY $order";
                }
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // pour chaque utilisateur, on affiche un bloc avec les informations de l'utilisateur,
                // ainsi qu'un bouton pour modifier l'utilisateur ou double click
                foreach ($result as $user) {
            ?>
            <div class="p-5 shadow-md rounded-sm text-black
            focus:border-black focus:border border

            <?php
                    // si l'utilisateur est administrateur, on affiche un cadre rouge
                    if (isset($user['role_name'])) {
                        echo "border-red-500";
                    }
            ?>
            " tabindex="0" ondblclick="modify(<?= $user['id'] ?>)">

                <div class="flex flex-col relative">
                    <!-- on affiche la photo de l'utilisateur, son id, son rôle s'il y a, son prenom, nom -->

                    <!-- photo -->
                    <img src="../images/<?= $user['email'] ?>.jpg " alt="profile picture" class="w-12 h-12">

                    <!-- id en haut à droite -->
                    <span class="text-sm absolute right-0 top-0">id: <?= $user['id'] ?></span>

                    <?php
                    // si l'utilisateur a un rôle on l'affiche
                    if (isset($user['role_name'])) {
                    ?>
                    <span class="text-red-500 font-bold">
                        [<?= strtoupper($user['role_name']) ?>]<br /> </span>
                    <?php } ?>

                    <!-- on affiche : nom prenom (âge) -->
                    <h1 class="font-bold">
                        <?php echo $user['prenom'] . ' ' . $user['nom'] . ' (' . $user['age'] . ')' ?>
                    </h1>



                    <!-- 
                        Maintenant on est dans index.php?signalement=oui
                        On affiche une croix pour directement supprimer l'utilisateur
                        ainsi qu'une raison pour le signalement
                    -->


                    <?php if (isset($_GET['signalement'])) { ?>
                    <br />
                    <div id="deletebtn" class="text-red-500 p-1 absolute top-2 right-0 font-bold">
                        <a href="./delete.php?id=<?= $user['id'] ?>&reason=<?= $user['reason'] ?>">X</a>
                    </div>
                    <br />
                    <span class="bg-red-500 text-white p-3">Cet utilisateur a été signalé pour:
                        <?= $user['reason'] ?>
                    </span>
                    <?php }


                    // ici on est plus dans index.php?signalement=oui on est dans index.php
                    // on affiche l'email, l'age, le sexe, la description
                    else { ?>

                    <!-- email -->
                    <span class="text-white-400">
                        <?php echo $user['email']; ?>
                    </span>

                    <!-- age -->
                    <span class="text-white-500">
                        <?php echo $user['age']; ?>
                    </span>

                    <!-- sexe mais formaté pour qu'on comprenne -->
                    <span class="text-white-500">
                        <?php
                        switch ($user['gender']) {
                            case 'm':
                                echo 'Homme';
                                break;
                            case 'n':
                                echo 'Non Binaire';
                                break;
                            case 'f':
                                echo 'Femme';
                                break;
                        }
                        ?>
                    </span>

                    <!-- la description -->
                    <span class="text-black p-3 bg-slate-100">
                        <?= $user['description'] ?>
                    </span>

                    <!-- modification de l'utilisateur -->
                    <a href="./modify.php?id=<?= $user['id'] ?>" class="text-red-500 w-fit mt-1">Modifier</a>

                    <?php } ?>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>

        <!-- pas important, redirection quand on change le select ou quand on modifie un utilisateur -->
    </div>
    <script>
    const sortby = document.getElementById('sortby');

    sortby.addEventListener('change', (e) => {
        window.location.href = `?order=${e.target.value}`;

    });

    function modify($id) {
        window.location.href = `./modify.php?id=${$id}`;
    }
    </script>
    <?php
        require_once '../methods/footer.php';
        ?>

</body>

</html>