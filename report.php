<?php
/*
 * Ce fichier permet le signalement de l'application Tindour.
 * Il est utilisé pour signaler un utilisateur.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once 'methods\header.php';
?>
<title>Tinder Clone - Signaler</title>
</head>

<body>
    <div class="container flex items-center justify-center w-full h-screen">
        <div class="p-6 rounded-lg">
            <div class="flex flex-col items-center justify-center w-full">
                <?php if (!isset($_GET['confirm'])) { ?>

                <!-- on demande à l'utilisateur s'il est sûr de vouloir signaler -->

                <h1 class="text-2xl font-bold">Êtes-vous sûr de vouloir signaler cet utilisateur ?</h1>
                <div class="bg-white p-6 rounded-lg shadow-lg w-full mt-5">

                    <?php
                }
                require_once './methods/database.php';

                // on récupère l'id de l'utilisateur à signaler
                $id = $_GET['id'];

                if ($conn) {
                    if (isset($_GET['confirm'])) {

                        session_start();

                        // l'utilisateur a confirmé le signalement, vu que c'est un utilisateur, il se peut qu'il essaye de faire des injections SQL
                        // on importe forbidsqlinjection pour éviter les injections SQL et utiliser parseSQL
                
                        require_once './methods/forbidsqlinjection.php';

                        $stmt = $conn->prepare("INSERT INTO report (user_id, reporter_id, reason) VALUES (:id, :reporter_id, :reason)");

                        $stmt->execute(['id' => $id, 'reporter_id' => $_SESSION['id'], 'reason' => parseSQL($_POST['reason'])]);

                    ?>

                    <!-- message de confirmation du signalement -->

                    <h1 class="text-2xl font-bold">Vous avez signalé cet utilisateur !</h1>
                    <a href="index.php" class="nav-link pl-1 pr-2 bg-red-500 text-white rounded-sm m-auto">Retour à
                        l'accueil</a>
                    <?php
                        // on ne charge pas le reste du code avec die;
                        die();
                    }
                    // si l'utilisateur n'a pas encore confirmé le signalement, on affiche le formulaire de signalement
                    // là on récupère les informations de l'utilisateur à signaler
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                    ?>
                    <!-- on affiche ses informations -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-6 rounded-lg">
                            <!-- photo -->
                            <img src="./images/<?php echo $user['email'] ?>.jpg" alt="profile pic"
                                class="object-cover w-40 h-40 mx-auto">
                            <!-- Prénom & Nom -->
                            <h1 class="text-2xl font-bold">
                                <?= $user['prenom']; ?>
                                <?= $user['nom']; ?>
                            </h1>
                        </div>
                    </div>
                    <?php }

                }
                    ?>

                    <!-- On demande la raison et on affiche le boutton confirmer -->

                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-6 rounded-lg">
                            <h1 class="text-2xl font-bold">Raison</h1>
                            <form action="./report.php?id=<?= $_GET['id'] ?>&confirm=true" method="POST">
                                <input type="text" name="reason" class="bg-gray-200 p-2 rounded-lg w-full "
                                    placeholder="Raison">
                                <button type="submit" style="background-color: #FD3A73"
                                    class="text-white font-bold p-2 rounded-lg w-full mt-2">Confirmer</button>
                            </form>
                        </div>
                        <!-- Bouton pour retourner -->
                        <a href="/index.php" class="text-blue-500">Retour</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php
        require_once 'methods/footer.php';
        ?>
</body>

</html>