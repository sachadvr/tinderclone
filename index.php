<?php
/*
 * Ce fichier est le fichier principal de l'application Tindour.
 * Il contient en son sein une methode pour se connecter à l'application.
 * Lorsqu'il est connecté, il a accès à toutes les autres méthodes.
 *
 * (c) Sacha Duvivier
 *
 */
require_once 'methods\header.php';
session_start();
?>
<title>Tinder Clone</title>


</head>

<body class="h-screen ">
    <div class="">

        <div class="flex-col flex items-center max-w-5xl mx-auto">
            <div class=" h-screen w-1/2 max-md:w-full transition-all m-5">
                <?php require_once './components/nav.component.php'; ?>
                <?php


                if (isset($_GET['fail'])) {
                    if ($_GET['fail'] == 1) {
                        echo '<div class="bg-red-500 p-4 rounded-lg text-white text-center mt-6">Invalid email or password</div>';
                    }
                }
                if (isset($_GET['created'])) {
                    if ($_GET['created'] == 1) {
                        echo '<div class="bg-green-500 p-4 rounded-lg text-white text-center mt-6">Successfully created account
                </div>';
                    }
                }
                if (!isset($_SESSION['email'])) { ?>
                <div class="bg-white p-6 rounded-lg  max-md w-full">
                    <h1 class="text-3xl font-bold mb-2">Se connecter</h1>
                    <form action="./methods/login.php" method="post">

                        <div class="mb-4">
                            <label for="email" class="sr-only">Email</label>
                            <input type="text" name="email" id="email" placeholder="Ton Email"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" placeholder="Ton mot de passe"
                                class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>
                        <a href="register.php" class="text-blue-500">Pas de compte? Inscris-toi ici</a>
                        <div>
                            <button type="submit" id="submitbtn" style="background: #FD3A73"
                                class="text-white px-4 py-3 rounded font-medium w-full">
                                Connexion
                            </button>
                        </div>
                    </form>

                    <?php
                } else {
                    ?>
                    <div class="absolute top-0 left-0 m-1 flex gap-3 items-center">
                        <a href="messages.php" style="background: #FD3A73"
                            class="text-white px-3 py-2 rounded font-medium w-full">Voir les Matchs</a>
                    </div>
                    <div class=" grid ">
                        <p>Connecté avec <strong>
                                <?= $_SESSION['email'] ?>
                            </strong></p>
                    </div>
                    <?php
                    require_once './methods/user.php';
                    if (!isset($profile_nom)) {
                    ?>

                    <div class="mt-10 flex flex-col gap-3">
                        <img src="./assets/finish-line.gif" alt="tinder swipe right" class="w-full object-cover h-56
                                opacity-50 animate-fade-in-down
                                ">
                        <span>Tu peux modifier ton profil en attendant de nouveaux profils</span>
                        <a href="editprofile.php" style="background: #FD3A73"
                            class="text-white px-3 py-2 rounded font-medium w-fit">Modifier mon profil</a>
                    </div>
                    <?php
                    } else {


                    ?>

                    <!-- on affiche le profil de l'utilisateur s'il y en a 1 avec les valeurs stockées précedemment -->
                    <a href="report.php?id=<?= $profile_id ?>" class="text-red-500">Signaler</a>
                    <div class="container  rounded-lg ">
                        <div class="grid gap-4">
                            <div class="bg-container p-5 pt-28 rounded-lg relative" onclick="showImageFullscreen()">
                                <img id="pp" src="./images/<?= $profile_email ?>.jpg" alt=""
                                    class="rounded-lg absolute top-0 left-0 w-full h-full -z-10 transition-all object-contain bg-black">
                                <div id="content" class=" bottom-4 left-4">

                                    <h1 class="text-2xl font-bold text-white">
                                        <?= $profile_nom . " " . $profile_prenom ?>
                                    </h1>
                                    <p class="text-white">
                                        <?= $profile_age ?>ans
                                    </p>
                                    <!-- desc -->
                                    <p class="text-white">
                                        <?php
                        if (isset($profile_description)) {
                            echo $profile_description;
                        } else {
                            echo "Pas de description";
                        }
                                        ?>
                                    </p>
                                    <!-- nombre de km -->
                                    <p class="text-white">Distance: 1km</p>
                                </div>
                            </div>

                            <!-- les boutons pour Like & dislike -->
                            <div class="grid gap-4 grid-cols-2">
                                <button onclick="anim(0)" style="background: #eb5d6c"
                                    class="p-2 rounded-3xl cursor-pointer text-white text-center transition-all hover:-translate-y-1 hover:scale-105">Non</button>
                                <button onclick="anim(1)" style="background: #71c897"
                                    class="p-2 rounded-3xl cursor-pointer	text-white text-center transition-all  hover:-translate-y-1 hover:scale-105">Oui</button>
                            </div>
                            <!-- on affiche les contrôles pour les matchs -->
                            <div class="flex justify-center max-md:hidden">
                                <div class="flex gap-4">
                                    <!-- Gauche pour dislike -->
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 bg-gray-200 rounded-full mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Non</span>
                                    </div>

                                    <!-- Droite pour like -->
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 bg-gray-200 rounded-full mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span>Oui</span>
                                    </div>

                                    <!-- Fleche du Haut pour plein ecran -->
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 bg-gray-200 rounded-full mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                        <span>Plein écran</span>

                                    </div>


                                    <!-- M pour les matchs -->
                                    <div class="flex items-center justify-center">
                                        <span
                                            class="h-6 w-6 bg-gray-200 rounded-full mr-2 text-center font-bold">M</span>
                                        <span>Voir Matchs</span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <?php } ?>


                    </div>
                    <div class="container relative  p-10">
                        <?php require_once './components/profile.component.php'; ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- 
                        Le reste du fichier n'est pas important, c'est juste pour l'adaptation du site sur mobile 
                        et des animations en javascript si un match est trouvé et donc faire une redirection.

         -->

        <script>
        let toggle = false;

        function showImageFullscreen() {
            toggle = !toggle;
            if (toggle) {

                // pas beau mais ça marche, on affiche en grand l'image
                document.querySelector('.bg-container').style.position = "absolute";
                document.querySelector('.bg-container').style.top = "0";
                document.querySelector('.bg-container').style.left = "0";
                document.querySelector('.bg-container').style.width = "100%";
                document.querySelector('.bg-container').style.height = "100%";
                document.querySelector('.bg-container').style.zIndex = "100";
                document.querySelector('#content').style.position = "absolute";
                document.querySelector('#pp').classList.remove('object-contain');
                document.querySelector('#pp').classList.add('object-cover');
            } else {

                // on remet l'image en petit
                document.querySelector('.bg-container').style.position = "relative";
                document.querySelector('.bg-container').style.top = "unset";
                document.querySelector('.bg-container').style.left = "unset";
                document.querySelector('.bg-container').style.width = "unset";
                document.querySelector('.bg-container').style.height = "unset";
                document.querySelector('.bg-container').style.zIndex = "unset";
                document.querySelector('#content').style.position = "relative";
                document.querySelector('#pp').classList.remove('object-cover');
                document.querySelector('#pp').classList.add('object-contain');
            }

        }


        function anim(e) {
            switch (e) {
                case 0:
                    document.querySelector('.bg-container').classList.add('animatedOut');
                    setTimeout(() => {
                        window.location.href = "./methods/user.php?disliked=<?= $profile_id ?>";
                    }, 1000);
                    break;
                case 1:
                    const isamatch = <?= $profile_match ?>;
                    if (isamatch == 1) {
                        document.querySelector('.bg-container').classList.add('match');
                        setTimeout(() => {
                            window.location.href = "./methods/user.php?liked=<?= $profile_id ?>&match=1";
                        }, 5000);
                    } else {
                        document.querySelector('.bg-container').classList.add('animatedIn');
                        setTimeout(() => {
                            window.location.href = "./methods/user.php?liked=<?= $profile_id ?>";
                        }, 1000);
                    }

                    break;
            }


        }
        // on fait en sorte qu'il soit swipable sur mobile (c'est assez complexe) mais c'est pas du
        document.querySelector('.bg-container').addEventListener('touchstart', handleTouchStart, false);
        document.querySelector('.bg-container').addEventListener('touchmove', handleTouchMove, false);

        var xDown = null;
        var yDown = null;

        function getTouches(evt) {
            return evt.touches || // API de touch
                evt.originalEvent.touches; // jQuery (pour les vieux navigateurs)
        }

        // on récupère les coordonnées du doigt quand il commence à toucher l'écran
        function handleTouchStart(evt) {
            const firstTouch = getTouches(evt)[0];
            xDown = firstTouch.clientX;
            yDown = firstTouch.clientY;
        };

        // on récupère les coordonnées du doigt quand il bouge sur l'écran
        function handleTouchMove(evt) {
            if (!xDown || !yDown) {
                return;
            }

            var xUp = evt.touches[0].clientX;
            var yUp = evt.touches[0].clientY;

            var xDiff = xDown - xUp;
            var yDiff = yDown - yUp;

            // si le doigts à bouger vers la gauche on fait un dislike 
            // sinon on fait un like

            if (Math.abs(xDiff) > Math.abs(yDiff)) {
                if (xDiff > 0) {
                    anim(0);
                } else {
                    anim(1);
                }
            }
            /* on reset les valeurs */
            xDown = null;
            yDown = null;
        };

        //on ajoute un event listener pour le clavier
        document.body.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                anim(0);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                anim(1);
            } else if (e.key === 'm') {
                e.preventDefault();
                window.location.href = "./messages.php";
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                showImageFullscreen();
            }
        });
        </script>
        <style>
        @keyframes slide {
            0% {
                transform: translateX(200%) rotate(10deg);
                ;
            }

            100% {
                transform: translateX(0%) rotate(0deg);
            }
        }

        @keyframes slideIn {
            0% {
                transform: translateX(0%) rotate(0deg);
            }

            100% {
                transform: translateX(200%) rotate(10deg);
                ;
            }
        }


        @keyframes slideOut {
            0% {
                transform: translateX(0%) rotate(0deg);
                ;
            }

            100% {
                transform: translateX(-200%) rotate(-10deg);
            }
        }

        .image_container {
            animation: slide .5s ease-in-out;
            animation-fill-mode: forwards;
        }

        .animatedOut {
            animation: slideOut .5s ease-in-out;
            animation-fill-mode: forwards;
        }

        .animatedIn {
            animation: slideIn .5s ease-in-out;
            animation-fill-mode: forwards;
        }
        </style>
        <?php
        require_once 'methods/footer.php'
            ?>
</body>

</html>