<?php
/*
 * Ce fichier permet la messagerie de l'application Tindour.
 * Il contient en son sein une methode pour voir les matchs
 * lorsqu'on clique sur un utilisateur on peut voir les messages et discuter avec lui.
 * 
 * (c) Sacha Duvivier
 *
 */
require_once 'methods\header.php';
session_start();
?>
<title>Tinder Clone - Messages</title>

</head>

<body>
    <script>
    document.body.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            window.location.href = 'index.php';
        }
        if (e.key === 'Escape') {
            window.location.href = 'index.php';
        }
    });
    </script>

    <div class="container mx-auto">
        <div class="flex justify-center">
            <?php require_once './components/goback.component.php'; ?>
            <div class="w-1/2 max-md:w-full transition-all m-5">
                <?php require_once './components/nav.component.php'; ?>
                <!-- 
                    bloc par personne pour chaque match de l'utilisateur
                -->
                <div class="flex gap-3">
                    <div class="grid w-full">

                        <?php
                        require_once './methods/database.php';
                        if (!isset($_SESSION['email'])) {
                          session_start();
                        }


                        if ($conn) {
                          require_once './methods/forbidsqlinjection.php';
                          if (isset($_GET['user']) && isset($_GET['message'])) {
                            // on envoie le message si $_GET['user'] et $_GET['message'] sont set
                            // on insere le message dans la base de donnees
                        
                            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, date_message) VALUES (:sender, :receiver, :message, :date)");
                            $stmt->execute([
                              ':sender' => $_SESSION['id'],
                              ':receiver' => parseSQL($_GET['user']),
                              ':message' => parseSQL($_GET['message']),
                              ':date' => date("Y-m-d H:i:s")
                            ]);
                            header('Location: messages.php?user=' . $_GET['user']);
                          }

                          // on récupère tous les utilisateurs de la base de données
                          $resultatAfficherTous = $conn->prepare("SELECT * from users WHERE id IN (SELECT liked_id from likes WHERE user_id = :user_id)
                            AND id IN (SELECT user_id from likes WHERE liked_id = :user_id) AND id != :user_id");


                          $resultatAfficherTous->execute([
                            ':user_id' => $_SESSION['id']
                          ]);

                          if ($resultatAfficherTous->rowCount() > 0) {
                            $ligneAfficherTous = $resultatAfficherTous->fetchAll(PDO::FETCH_ASSOC);

                            if (isset($_GET['user'])) {
                        ?>
                        <!-- Bouton pour retourner à la liste des utilisateurs -->
                        <a href="messages.php" class="rounded-md text-white w-fit p-1 mb-1"
                            style="background-color: #FD3A73">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Retour aux messages</a>

                        <?php
                            }
                            foreach ($ligneAfficherTous as $user) {
                              // n'affiche que l'utilisateur sélectionné
                              if (isset($_GET['user']) && $_GET['user'] != $user['id']) {
                                continue;
                              }
                        ?>

                        <!-- on affiche les utilisateurs, le nom, prénom, photo de profil et le bouton pour voir les messages -->
                        <div class="grid-item flex gap-3 items-center mb-3 text-white font-bold cursor-pointer rounded-md overflow-hidden
                                            " style="background:#FD3A73;"
                            onclick="window.location.href = 'messages.php?user=<?php echo $user['id']; ?>'">
                            <img src='images/<?= $user['email'] ?>.jpg' width='50px'>
                            <?= $user['prenom'] ?>
                            <?= $user['nom'] ?>
                            <a href="report.php?id=<?= $user['id'] ?>"
                                class="text-black font-bold ml-auto mr-1 p-1 bg-white rounded-sm">Signaler</a>
                        </div>

                        <?php if (isset($_GET['user'])) {
                                if ($_GET['user'] == $user['id']) {
                        ?>
                        <div class="shadow-lg mb-3 p-3">
                            <!-- on affiche les messages en questions -->
                            <div name="message" id="message" cols="30" rows="10"
                                class="w-full flex flex-col p-3 overflow-x-hidden overflow-y-scroll h-40" disabled>
                                <?php
                                  // on prépare la requête pour récupérer les messages
                                  $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = :sender AND receiver_id = :receiver) OR (sender_id = :receiver AND receiver_id = :sender) ORDER BY date_message ASC");
                                  $stmt->execute(['sender' => $_SESSION['id'], 'receiver' => parseSQL($_GET['user'])]);
                                  $messages = $stmt->fetchAll();

                                  if ($stmt->rowCount() <= 0) {
                                    // si pas de messages on affiche un message
                                    echo "Vous venez de matcher avec cette personne, envoyez-lui un message !";
                                  } else {

                                    foreach ($messages as $message) {
                                      $sender = $message['sender_id'];
                                      $receiver = $message['receiver_id'];
                                      $message_text = $message['message'];
                                      if ($sender == $_SESSION['id']) {
                                ?>

                                <!-- 

                                    ICi, on affiche les messages envoyés par l'utilisateur et reçus vers l'autre utilisateur
                                    Bleu si envoyé par l'utilisateur
                                    Gris si reçu par l'utilisateur

                               -->

                                <span class="self-end
                            bg-blue-300 w-fit rounded-full p-2 relative
                            ">
                                    <?= $message_text ?>
                                </span>
                                <span class="self-end mt-1 mb-1 text-xs text-gray-500
                            ">
                                    <!-- on affiche la date du message -->
                                    <?php
                                        $date = new DateTime($message['date_message']);
                                        echo $date->format('d/m/Y H:i:s');

                                    ?>


                                </span>


                                <?php
                                      } else {
                                ?>
                                <span class="mb-3 text-left bg-gray-300 w-fit rounded-full p-2">
                                    <?= $user['prenom'] ?>: <?= $message_text ?>
                                </span>
                                <span class="self-end mt-1 mb-1 text-xs text-gray-500
                            ">
                                    <!-- on affiche la date du message -->
                                    <?php
                                        $date = new DateTime($message['date_message']);
                                        echo $date->format('d/m/Y H:i:s');

                                    ?>


                                </span>
                                <?php
                                      }
                                    }
                                  }
                                ?>
                            </div>
                            <!-- on permet d'envoyer un message -->
                            <div class="relative"><input type="text" placeholder="Rédigez un message" name="message"
                                    id="messagebtn" class="w-full border border-black p-2 h-18 rounded-full">
                                <button class="absolute right-0 text-blue-300 mr-1 p-2 rounded-full mb-3 rounded-md "
                                    onclick="sendMessage(<?= $_GET['user'] ?>)">Envoyer</button>
                            </div>
                        </div>
                        <?php
                                }
                              } ?>

                        <?php
                            }
                          } else {
                            // si il n'y a pas de match ou de like
                            echo "<span>Vous n'avez pas encore de match :(</span>";
                            die();
                          }


                        } else {
                          echo "Pas de connexion à la base de donnée :(";
                          die();
                        }

                        ?>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <?php
    require_once 'methods/footer.php'
      ?>


</body>
<script>
function sendMessage() {
    var message = document.getElementById('messagebtn').value;

    if (message == "" || message == null || message == undefined) {
        return;
    }
    var receiver = "<?php if (isset($_GET['user'])) {
      echo $_GET['user'];
  } ?> ";

    window.location.href = "messages.php?user=" + receiver + "&message=" + message;


}
window.onload = function() {
    document.getElementById('messagebtn').addEventListener('keydown', function(e) {
        if (e.keyCode == 13) {
            sendMessage();
        }
    });
    var textarea = document.getElementById('message');
    textarea.scrollTop = textarea.scrollHeight;



}
</script>

</html>