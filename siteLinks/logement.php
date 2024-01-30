<?php

/* Lancement de la Session */
session_start();
error_reporting(0);

/* Suppression du logement si demandé */
include('connect_params.php');
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$query = $dbh->prepare("SELECT * FROM test.logement WHERE id_logement = :idlog");
$query->bindParam('idlog', $_GET["confirmDelete"], PDO::PARAM_INT);
$query->execute();
$query = $query->fetchAll();
if (isset($_GET["confirmDelete"])) {
    try {
        $query = "DELETE FROM test.logement WHERE test.logement.id_logement = :id_log";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_log', $_GET["confirmDelete"], PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    header("Location: index.php");
}


/* Passage du logement en mode en ligne ou hors ligne si demandé*/

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$query = $dbh->prepare("SELECT * FROM test.logement WHERE id_logement = :idlog");
$query->bindParam('idlog', $_GET["confirmHorsligne"], PDO::PARAM_INT);
$query->execute();
$query = $query->fetchAll();
if (isset($_GET["confirmHorsligne"])) {
    try {
        $updateQuery = "UPDATE test.logement SET en_ligne = FALSE WHERE id_logement = :id_log";
        $updateStmt = $dbh->prepare($updateQuery);
        $updateStmt->bindParam('id_log', $_GET["confirmHorsligne"], PDO::PARAM_INT);
        $updateStmt->execute();

        $_GET["id"] = $_GET["confirmHorsligne"];
        unset($_GET["confirmHorsligne"]);

        $selectQuery = "SELECT * FROM test.logement WHERE id_logement = :idlog";
        $selectStmt = $dbh->prepare($selectQuery);
        $selectStmt->bindParam('idlog', $_GET["id"], PDO::PARAM_INT);
        $selectStmt->execute();
        $result = $selectStmt->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$query = $dbh->prepare("SELECT * FROM test.logement WHERE id_logement = :idlog");
$query->bindParam('idlog', $_GET["confirmligne"], PDO::PARAM_INT);
$query->execute();
$query = $query->fetchAll();
if (isset($_GET["confirmligne"])) {
    try {
        $updateQuery = "UPDATE test.logement SET en_ligne = TRUE WHERE id_logement = :id_log";
        $updateStmt = $dbh->prepare($updateQuery);
        $updateStmt->bindParam('id_log', $_GET["confirmligne"], PDO::PARAM_INT);
        $updateStmt->execute();

        $_GET["id"] = $_GET["confirmligne"];
        unset($_GET["confirmligne"]);

        $selectQuery = "SELECT * FROM test.logement WHERE id_logement = :idlog";
        $selectStmt = $dbh->prepare($selectQuery);
        $selectStmt->bindParam('idlog', $_GET["id"], PDO::PARAM_INT);
        $selectStmt->execute();
        $result = $selectStmt->fetchAll();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}
/* Début de la page en html css */


try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT * FROM test.avis NATURAL JOIN test.logement WHERE id_logement = :idlog";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam('idlog', $_GET["id"], PDO::PARAM_INT);
    $stmt->execute();
    $aviss = $stmt->fetchAll();

    $rrrrrrrrrrrowCount = $stmt->rowCount();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/datePicker.css">
    <link rel="stylesheet" href="asset/css/logement.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="asset/js/boutonSupprimer.js"></script>
    <script src="asset/js/ligneHorsligne.js"></script>
    <script src="asset/js/redirectionConnect.js"></script>

</head>

<body id="bg">
    <script>
        /* Scripte pour avoir la disponibilité au niveau des dates */
        const dateIndispo = [
            <?php
            $id = $_GET["id"];
            foreach ($dbh->query("SELECT * from test.planning WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {
                $time = strval($row["jour"]);
                if (!$row["disponibilite"]) {
                    echo "new Date(\"$time\"),";
                }
            }
            ?>
        ];
        console.log(dateIndispo);
    </script>

    <?php  /* Récupération de toutes les informations concernant le logement via la BDD */

    include('connect_params.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $id = $_GET["id"];
        foreach ($dbh->query("SELECT * from test.logement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {
            $info = $row;
            $i = 0;
            foreach ($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $photo[$i] = $row;
                $i++;
            }
            $i = 0;
            $amena = [];
            foreach ($dbh->query("SELECT * from test.amenagement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $amena[] = $row;
            }
            $instal = [];
            foreach ($dbh->query("SELECT * from test.installation WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $instal[] = $row;
            }
            $service = [];
            foreach ($dbh->query("SELECT * from test.service WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $service[] = $row;
            }
            foreach ($dbh->query("SELECT * from test.lit WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $lit = $row;
            }
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }


    /* Récupération de tout les inforamtion sur le propriétaire */

    try {
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.compte NATURAL JOIN test.telephone NATURAL JOIN test.proprietaire WHERE id_compte = :id_compte";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_compte', $info["id_compte"], PDO::PARAM_STR);
        $stmt->execute();
        $proprio = $stmt->fetch();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    $dbh = null;
    ?>






    <div class="sticky_header_log">


        <!-- Header -->
        <header>
            <a href="index.php">
                <img src="asset/img/logo.png" alt="logo">
            </a>
            <div></div>
            <div id="headerEmptyDiv"></div>
            <nav>
                <div>
                    <svg width="30" height="30" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M23.7497 10.7258C22.7264 4.4244 20.3126 0 17.5035 0C14.6945 0 12.2807 4.4244 11.2573 10.7258H23.7497ZM10.728 17.5C10.728 19.0665 10.8127 20.5696 10.9609 22.0161H24.0391C24.1873 20.5696 24.272 19.0665 24.272 17.5C24.272 15.9335 24.1873 14.4304 24.0391 12.9839H10.9609C10.8127 14.4304 10.728 15.9335 10.728 17.5ZM33.6449 10.7258C31.6263 5.93448 27.5398 2.22984 22.4934 0.733871C24.2156 3.11895 25.4013 6.71069 26.0224 10.7258H33.6449ZM12.5066 0.733871C7.46723 2.22984 3.37366 5.93448 1.36217 10.7258H8.98467C9.59871 6.71069 10.7844 3.11895 12.5066 0.733871ZM34.4001 12.9839H26.3047C26.4529 14.4657 26.5376 15.9829 26.5376 17.5C26.5376 19.0171 26.4529 20.5343 26.3047 22.0161H34.393C34.7812 20.5696 35 19.0665 35 17.5C35 15.9335 34.7812 14.4304 34.4001 12.9839ZM8.46945 17.5C8.46945 15.9829 8.55414 14.4657 8.70236 12.9839H0.606977C0.225852 14.4304 0 15.9335 0 17.5C0 19.0665 0.225852 20.5696 0.606977 22.0161H8.6953C8.55414 20.5343 8.46945 19.0171 8.46945 17.5ZM11.2573 24.2742C12.2807 30.5756 14.6945 35 17.5035 35C20.3126 35 22.7264 30.5756 23.7497 24.2742H11.2573ZM22.5005 34.2661C27.5398 32.7702 31.6334 29.0655 33.6519 24.2742H26.0294C25.4083 28.2893 24.2226 31.881 22.5005 34.2661ZM1.36217 24.2742C3.38072 29.0655 7.46723 32.7702 12.5136 34.2661C10.7915 31.881 9.60577 28.2893 8.98467 24.2742H1.36217Z" fill="#F5F5F5" />
                    </svg>
                    <svg id="headerArrowLang" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.99141 13.4874C9.54926 14.1709 10.4552 14.1709 11.0131 13.4874L19.5816 2.98945C20.1395 2.30599 20.1395 1.19605 19.5816 0.512594C19.0238 -0.170866 18.1178 -0.170866 17.56 0.512594L10 9.77485L2.44003 0.518062C1.88218 -0.165399 0.976236 -0.165399 0.418387 0.518062C-0.139462 1.20152 -0.139462 2.31146 0.418387 2.99492L8.98695 13.4929L8.99141 13.4874Z" fill="#F5F5F5" />
                    </svg>
                </div>
                <svg id="headerHamburger" width="28" height="31" viewBox="0 0 28 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect y="0.738281" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
                    <rect y="13.6523" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
                    <rect y="26.5645" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
                </svg>
                <?php
                if (isset($_SESSION['userId'])) {
                ?>
                    <h4><a href="compte.php?res=res"><?php if ($_SESSION["userType"] == "proprietaire") {
                                                            echo ("Mes logements");
                                                        } else {
                                                            echo ("Mes réservations");
                                                        } ?></a></h4>
                    <h4><a href="compte.php">Mon compte</a></h4>
                <?php } else {
                ?>
                    <h4><a href="connexion.php">Se connecter</a></h4>
                <?php
                }
                ?>
            </nav>
            <div></div>
        </header>


        <div class="barre_fin_header_log"> <!-- Début de la barre de séparation du header -->
            <svg width="100%" height="23" viewBox="0 0 1920 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_15_355)">
                    <path d="M0 6H1920V9H0V6Z" fill="#97ADC1" />
                </g>
                <defs>
                    <filter id="filter0_d_15_355" x="-7" y="0" width="1940" height="23" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                        <feOffset dx="3" dy="4" />
                        <feGaussianBlur stdDeviation="5" />
                        <feComposite in2="hardAlpha" operator="out" />
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_15_355" />
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_15_355" result="shape" />
                    </filter>
                </defs>
            </svg>
        </div> <!-- Fin de la barre de séparation du header -->


    </div> <!-- Fin du header -->


    <main> <!-- Début de la page  -->


        <div class="blanc"> <!-- Début du div pour mettre le background en blanc -->


            <div class="titre_log"> <!-- Partie pour afficher le "titre" du logement et le reste -->
                <h3><?php echo ($info["libelle_logement"]);  ?></h3>
                <div class="titre_log_bas">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                    </svg>
                    <p id="notegenre"><?php echo ($info["note_logement"]);  ?></p>
                    <p id="detailgenre"><?php echo ($info["localisation"]);   ?> , <?php echo ($info["code_postal"]); ?></p>
                </div>
            </div>

            <div class="images_log"> <!-- Partie pour montrer les images -->

                <!-- Cette partie est un peut dificile à comprendre mais elle crée une image puis si il y a plus de 1 image, un div "images_log_droite" est crée puis dedans une ligne nommé "lig_images_log_droite" est crée. Cette ligne contien 2 image et si la première ligne est saturé (si il y a plus de 3 image), une deuxième ligne est crée. Si la deuxième ligne est aussi saturé, les autres images sont ignoré afin de concervé un aspect visuel beau  -->

                <?php
                $i = 0;
                $div = 0;
                $lig = 1;
                $delai = -2;
                foreach ($photo as $ind => $nom) {

                    if ($i == 1) {
                        $div = 1;
                ?>

                        <div class="images_log_droite">

                        <?php
                    }

                    if ($lig == 2) {
                        $delai = 0;
                        $lig = 0;
                        ?>

                            <div class="lig_images_log_droite">

                            <?php
                        }

                        if ($i < 5) {
                            ?>

                                <img src="asset/img/logements/<?php echo ($nom['id_image'] . "." . $nom['extension_image']); ?>" alt="problème">

                        <?php
                        }


                        if ($delai == 1) {
                            echo ("</div>");
                        }

                        $lig++;
                        $delai++;
                        $i++;
                    }

                    if ($div == 1) {
                        echo ("</div>");
                    }

                        ?>
                            </div>



                        </div> <!-- Fin du div pour le background blanc -->

                        <div class="barre_log"> <!-- Barre de séparation -->
                            <svg width="100%" height="10" viewBox="0 0 1920 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_60_122)">
                                    <rect width="1920" height="1" transform="matrix(1 0 0 -1 0 4)" fill="#D9D9D9" />
                                </g>
                                <defs>
                                    <filter id="filter0_d_60_122" x="-4" y="0" width="1928" height="9" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                        <feOffset dy="1" />
                                        <feGaussianBlur stdDeviation="2" />
                                        <feComposite in2="hardAlpha" operator="out" />
                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_60_122" />
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_60_122" result="shape" />
                                    </filter>
                                </defs>
                            </svg>
                        </div>


                        <div class="sticky_res_and_map_log"> <!-- Div pour rendre faire glisser la map et la case réservation -->

                            <div class="barre_info_log"> <!-- Partie avec les informations du logement -->

                                <div class="proprio_log"> <!-- Partie avec les information de propriétaire -->

                                    <?php //récupération du nom de l'image (avec extension)

                                    if ($images = opendir('asset/img/profils/')) {
                                        while (false !== ($fichier = readdir($images))) {
                                            $imgInfos = pathinfo($fichier);
                                            if ($imgInfos['filename'] == $proprio["id_compte"]) {
                                                $pathName = 'asset/img/profils/' . $fichier;
                                                break;
                                            }
                                        }
                                        if ($pathName == '') {
                                            $pathName = 'asset/img/profils/default.jpg';
                                        }
                                        closedir($images);
                                    }
                                    ?>
                                    <!-- Photo de profil du proprio (en backgroud pour avoir l'effet du cercle) -->
                                    <a class="img_proprio_log" href="pageProprio.php?id=<?php echo ($proprio["id_compte"]); ?>&id_log=<?php echo ($id) ?>">
                                        <div class="photo_profil_proprio_log">
                                            <style>
                                                .photo_profil_proprio_log {
                                                    background: url("<?php echo ($pathName) ?>") center/cover;
                                                }
                                            </style>
                                        </div>

                                    </a>


                                    <div class="info_proprio_log">

                                        <div class="block_info_log" id="nomeuhgenre">
                                            <h2><?php echo ($proprio["nom_affichage"]) ?></h2>
                                        </div>
                                        <div class="block_info_log">
                                            <!--        Parti supprimer sue la note du logement                                   
                                        <?php
                                        if ($proprio["note_proprio"] != "") {
                                        ?>
                                                <div class="note_proprio_log">

                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.3646 1.22353L7.5304 7.12042L1.18926 8.06909C0.052104 8.23834 -0.403625 9.67693 0.421028 10.5009L5.0087 15.0884L3.92363 21.5687C3.72832 22.7401 4.93058 23.6175 5.93752 23.0696L11.6103 20.0099L17.283 23.0696C18.29 23.613 19.4922 22.7401 19.2969 21.5687L18.2118 15.0884L22.7995 10.5009C23.6242 9.67693 23.1684 8.23834 22.0313 8.06909L15.6901 7.12042L12.8559 1.22353C12.3481 0.172417 10.8768 0.159056 10.3646 1.22353Z" fill="#F5F5F5" />
                                                    </svg>
                                                    <p><?php echo ($proprio["note_proprio"]) ?></p>

                                                </div>
                                            <?php
                                        }
                                            ?> -->
                                        </div>
                                        <div class="block_info_log">
                                            <div class="contact_proprio_log"> <!-- Numéro de téléphone de propriétaire -->
                                                <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20.4011 16.0445L15.8073 14.0242C15.611 13.9384 15.3929 13.9203 15.1858 13.9727C14.9787 14.0251 14.7937 14.1451 14.6588 14.3146L12.6244 16.8653C9.4316 15.3205 6.86212 12.6838 5.35673 9.40742L7.84232 7.31978C8.0079 7.18159 8.12509 6.9918 8.17615 6.77916C8.22722 6.56651 8.20938 6.34258 8.12534 6.14127L6.15655 1.42723C6.06431 1.21022 5.90117 1.03304 5.69526 0.92624C5.48935 0.819439 5.25358 0.789713 5.0286 0.842188L0.762904 1.85234C0.545997 1.90374 0.352472 2.02906 0.213915 2.20786C0.0753574 2.38666 -4.99665e-05 2.60837 2.48403e-08 2.83681C2.48403e-08 13.6328 8.5273 22.3664 19.0316 22.3664C19.2543 22.3665 19.4704 22.2892 19.6447 22.147C19.8191 22.0048 19.9413 21.8062 19.9914 21.5835L20.9758 17.2062C21.0266 16.9742 20.997 16.7313 20.8921 16.5193C20.7872 16.3073 20.6136 16.1394 20.4011 16.0445Z" fill="#F5F5F5" />
                                                </svg>
                                                <p><?php echo wordwrap($proprio["numero"], 2, " ", 1); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                                $nb_piece = $info["type_logement"];
                                $test = explode("T", $nb_piece);
                                ?>


                                <div class="details_log"> <!-- Détails du logements : nombre de chambre, de pièces etc ... -->
                                    <div class="txt_details_log">
                                        <p class="txt_info_log"> <?php echo ($info["nature_logement"]); ?> · <?php echo ($test[1]); ?> pièces · <?php echo ($info["nb_pers_max"]); ?> personnes</p>
                                        <p class="txt_info_log"><?php echo ($info["nb_chambre"]); ?> chambres · <?php echo ($lit["nombre_lit"]); ?> lits · <?php echo ($info["nb_salle_de_bain"]); ?> salle de bain</p>
                                        <p class="txt_info_log"><?php echo ($info["localisation"]);  ?></p>
                                        <p class="txt_info_log petit">Posté le 13/09/2023 à 20h56</p>
                                    </div>
                                </div>
                            </div>


                            <div class="group_res_info_log">


                                <div class="res_and_map_log">

                                    <div id="map" style="height: 400px; margin-top: -20px; margin-bottom:10px;"></div>

                                    <script>
                                        var map = L.map('map').setView([0, 0], 2);

                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '© OpenStreetMap contributors'
                                        }).addTo(map);

                                        var cityName = '<?php echo ($info["localisation"]); ?>';

                                        var geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(cityName);

                                        fetch(geocodeUrl)
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.length > 0) {
                                                    var lat = parseFloat(data[0].lat);
                                                    var lon = parseFloat(data[0].lon);

                                                    map.setView([lat, lon], 10);

                                                    L.marker([lat, lon]).addTo(map)
                                                        .bindPopup(cityName)
                                                        .openPopup();

                                                    console.log('Latitude:', lat);
                                                    console.log('Longitude:', lon);
                                                } else {
                                                    console.error('Aucune donnée de géocodage trouvée pour la ville:', cityName);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Erreur lors de la requête de géocodage:', error);
                                            });
                                    </script>

                                    <div class="dispo_date_log">
                                        <p><span> Disponibilité de réservation : </span></p>
                                        <button class="bouton_date_log" onclick="toggleDP('DPDisplay', this)" style="margin: 1em;">
                                            <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.43766 17.2659H6.15721C5.78094 17.2659 5.47307 16.9499 5.47307 16.5638V14.2237C5.47307 13.8376 5.78094 13.5217 6.15721 13.5217H8.43766C8.81393 13.5217 9.12179 13.8376 9.12179 14.2237V16.5638C9.12179 16.9499 8.81393 17.2659 8.43766 17.2659ZM14.5949 16.5638V14.2237C14.5949 13.8376 14.287 13.5217 13.9107 13.5217H11.6303C11.254 13.5217 10.9461 13.8376 10.9461 14.2237V16.5638C10.9461 16.9499 11.254 17.2659 11.6303 17.2659H13.9107C14.287 17.2659 14.5949 16.9499 14.5949 16.5638ZM20.0679 16.5638V14.2237C20.0679 13.8376 19.7601 13.5217 19.3838 13.5217H17.1034C16.7271 13.5217 16.4192 13.8376 16.4192 14.2237V16.5638C16.4192 16.9499 16.7271 17.2659 17.1034 17.2659H19.3838C19.7601 17.2659 20.0679 16.9499 20.0679 16.5638ZM14.5949 22.1801V19.84C14.5949 19.4539 14.287 19.1379 13.9107 19.1379H11.6303C11.254 19.1379 10.9461 19.4539 10.9461 19.84V22.1801C10.9461 22.5662 11.254 22.8821 11.6303 22.8821H13.9107C14.287 22.8821 14.5949 22.5662 14.5949 22.1801ZM9.12179 22.1801V19.84C9.12179 19.4539 8.81393 19.1379 8.43766 19.1379H6.15721C5.78094 19.1379 5.47307 19.4539 5.47307 19.84V22.1801C5.47307 22.5662 5.78094 22.8821 6.15721 22.8821H8.43766C8.81393 22.8821 9.12179 22.5662 9.12179 22.1801ZM20.0679 22.1801V19.84C20.0679 19.4539 19.7601 19.1379 19.3838 19.1379H17.1034C16.7271 19.1379 16.4192 19.4539 16.4192 19.84V22.1801C16.4192 22.5662 16.7271 22.8821 17.1034 22.8821H19.3838C19.7601 22.8821 20.0679 22.5662 20.0679 22.1801ZM25.541 6.96933V27.5624C25.541 29.1127 24.3153 30.3705 22.8045 30.3705H2.73654C1.22574 30.3705 0 29.1127 0 27.5624V6.96933C0 5.419 1.22574 4.16118 2.73654 4.16118H5.47307V1.11903C5.47307 0.732908 5.78094 0.416992 6.15721 0.416992H8.43766C8.81393 0.416992 9.12179 0.732908 9.12179 1.11903V4.16118H16.4192V1.11903C16.4192 0.732908 16.7271 0.416992 17.1034 0.416992H19.3838C19.7601 0.416992 20.0679 0.732908 20.0679 1.11903V4.16118H22.8045C24.3153 4.16118 25.541 5.419 25.541 6.96933ZM22.8045 27.2114V9.77747H2.73654V27.2114C2.73654 27.4044 2.89047 27.5624 3.0786 27.5624H22.4624C22.6505 27.5624 22.8045 27.4044 22.8045 27.2114Z" fill="#F5F5F5" />
                                            </svg> &#160;Dates</button>
                                        <div id="DPDisplay" class="dateDisplay"></div>
                                    </div>


                                    <div class="rerservation_log"> <!-- Partie avec tout les détails de la réservation : aménagement, prix etc ... -->
                                        <div class="haut_rerservation_log">
                                            <h2><span><?php echo ($info["prix_ttc"]); ?> €</span> / nuit</h2>
                                            <?php // (isset($_SESSION['userType']) ? 'demandeDevis.php' : 'connexion.php') 
                                            ?>
                                            <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                                            <input name="qui" value="" hidden readonly>

                                            <?php

                                            if (!isset($_SESSION["userType"])) {
                                            ?>

                                                <button class="bouton_res_log" onclick=modalRedirect()>
                                                    <h1>Réserver</h1>
                                                </button>


                                            <?php
                                            } else {

                                            ?>

                                                <?php if ($_SESSION['userType'] == "client") { ?>
                                                    <form action="demandeDevis.php" method="POST">
                                                    <?php } ?>
                                                    <button class="bouton_res_log">
                                                        <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                                                        <input name="qui" value="" hidden readonly>
                                                        <h1>Réserver</h1>
                                                    </button>
                                                    <?php if ($_SESSION['userType'] == "client") { ?>
                                                    </form>
                                                <?php } ?>


                                            <?php
                                            }
                                            ?>



                                            <div class="confirmation-modal" id="myModal4">
                                                <div class="modal-content">
                                                    <span class="close" onclick="refusRedirect()">&times;</span>
                                                    <p>Vous n'êtes pas authentifier, voulez vous crée un compte ?</p>
                                                    <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
                                                    <form action="<?php if ($_SESSION['userType']) { ?>demandeDevis.php<?php } else { ?>connexion.php<?php } ?>" method="POST">
                                                        <button class="confirm-button">Confirmer</button>
                                                    </form>


                                                </div>
                                            </div>




                                        </div>
                                        <div class="bare_res"></div>
                                        <div class="detail_reservation_log"> <!-- Début de la gestion de l'affichage des aménagement, installations et services -->


                                            <!-- Cette partie peut être difficile à comprendre mais c'est juste une façon d'avoir un div global, regroupant toute les ligne qui contienne par exemple tout les aménagements. Chaque ligne possède 2 aménagements et si il y en a 3, ça crée 2 ligne et la 2ème n'en possède qu'un seul. -->

                                            <?php
                                            $t = 0;
                                            if (count($amena) > 0) {
                                                $t = 1;
                                            }

                                            /* Gestion de l'affichages des amménagements */

                                            if ($amena != null) {

                                            ?>


                                                <p>Aménagments proposés : </p>
                                                <div class="amenagement_log">


                                                    <?php
                                                    $i = 0;
                                                    $div_ligne_elem = '<div class="ligne_elem_res_log">';

                                                    foreach ($amena as $ind => $key) {

                                                    ?>



                                                        <?php
                                                        if ($i == 0) {
                                                            echo ($div_ligne_elem);
                                                        }
                                                        ?>


                                                        <div class="elem_reservation_log">
                                                            <img src="asset/icons/blanc/<?php echo ($key["nom_amenagement"]); ?>.svg" alt="">
                                                            <p><?php echo ($key["nom_amenagement"]); ?></p>
                                                        </div>



                                                        <?php

                                                        $i++;
                                                        if ($i >= 2) {
                                                            echo ("</div>");
                                                            $i = 0;
                                                        }

                                                        ?>

                                                    <?php }

                                                    if ($i != 0) {
                                                        echo ("</div>");
                                                    }

                                                    ?>
                                                </div>
                                            <?php } else {
                                                echo ("<br/><p>Aucun aménagement</p><br/>");
                                            } ?>


                                            <?php
                                            $t = 0;

                                            if ($instal != null) {
                                                $t = 1;
                                            }

                                            /* Gestion de l'affichange des installations */

                                            if ($t == 1) {

                                            ?>

                                                <p>Installations : </p>
                                                <div class="installations_log">

                                                    <?php
                                                    $i = 0;
                                                    $div_ligne_elem = '<div class="ligne_elem_res_log">';

                                                    foreach ($instal as $ind => $key) {

                                                    ?>



                                                        <?php
                                                        if ($i == 0) {
                                                            echo ($div_ligne_elem);
                                                        }
                                                        ?>



                                                        <div class="elem_reservation_log">
                                                            <img src="asset/icons/blanc/<?php echo ($key["nom_installation"]); ?>.svg" alt="">
                                                            <p> <?php echo ($key["nom_installation"]); ?> </p>
                                                        </div>



                                                        <?php

                                                        $i++;
                                                        if ($i >= 2) {
                                                            echo ("</div>");
                                                            $i = 0;
                                                        }

                                                        ?>

                                                    <?php }

                                                    if ($i != 0) {
                                                        echo ("</div>");
                                                    }

                                                    ?>

                                                </div>

                                            <?php } else {
                                                echo ("<br/><p>Aucune instalation</p><br/>");
                                            } ?>


                                            <?php
                                            $t = 0;
                                            if ($service != null) {
                                                $t = 1;
                                            }

                                            /* Gestion de l'affichage des services */

                                            if ($t == 1) {

                                            ?>

                                                <p>Services compris : </p>
                                                <div class="services_log">

                                                    <?php
                                                    $i = 0;
                                                    $div_ligne_elem = '<div class="ligne_elem_res_log">';
                                                    foreach ($service as $ind => $key) {

                                                    ?>

                                                        <?php
                                                        if ($i == 0) {
                                                            echo ($div_ligne_elem);
                                                        }
                                                        ?>

                                                        <div class="elem_reservation_log">

                                                            <img src="asset/icons/blanc/<?php echo ($key["nom_service"]); ?>.svg" alt="">
                                                            <p> <?php echo ($key["nom_service"]); ?> </p>



                                                        </div>

                                                        <?php

                                                        $i++;
                                                        if ($i >= 2) {
                                                            echo ("</div>");
                                                            $i = 0;
                                                        }

                                                        ?>

                                                    <?php }

                                                    if ($i != 0) {
                                                        echo ("</div>");
                                                    }

                                                    ?>
                                                </div>


                                            <?php } else {
                                                echo ("<br/><p>Aucun service proposé</p><br/>");
                                            } ?>


                                        </div>

                                    </div> <!-- Fin de la partie sur l'ajout des aménagements, services et installation -->


                                </div>

                                <div class="dutexte"> <!-- Ajout de tout les paragraphes liés au logements style description, règles etc ... -->
                                    <div class="description_log">
                                        <h4>Description :</h4>
                                        <p class="txt_descr_log"><?php echo ($info["descriptif"]); ?></p>

                                    </div>

                                    <div class="sep_descri_regle_log">
                                    </div>


                                    <div class="reglement_log">
                                        <h4>Réglement intérieur : </h4>
                                        <p class="txt_descr_log"><?php echo ($info["reglement_interieur"]); ?></p>

                                    </div>

                                    <div class="sep_descri_regle_log">
                                    </div>


                                    <div class="info_arrive_log">
                                        <h4>Informations d’arrivée :</h4>
                                        <p class="txt_descr_log"><?php echo ($info["info_arrivee"]); ?></p>

                                    </div>

                                    <div class="sep_descri_regle_log">
                                    </div>


                                    <div class="info_depart_log">
                                        <h4>Informations de départ :</h4>
                                        <p class="txt_descr_log"><?php echo ($info["info_depart"]); ?></p>

                                    </div>
                                </div>


                            </div>
                        </div>





                        <?php /* Affichage des bouton modifier, supprimer et mettre hors ligne / en ligne si connecter comme propriétaire */

                        if ($_SESSION['userId'] == $info["id_compte"]) {
                            $statue;
                            if ($info["en_ligne"]) {
                                $statue = "Mettre Hors Ligne";
                            } else {
                                $statue = "Mettre en Ligne";
                            }
                        ?>
                            <div class="barre_btn_ajustement_log">
                                <div class="button_valider2">
                                    <a href="modifLogement.php?id=<?php echo ($id) ?>">
                                        <h2>Modifier</h2>
                                    </a>
                                </div>

                                <div class="button_refuser2">
                                    <button onclick="openModal()">supprimer</button>
                                </div>
                                <div class="button_ligne2">
                                    <button onclick="ouvreModal()"><?php echo $statue ?></button>
                                </div>
                            </div>



                            <div class="confirmation-modal" id="myModal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <p>Êtes-vous sûr de vouloir supprimer ce logement ?</p>
                                    <form method="GET" action="logement.php">
                                        <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">

                                        <button class="confirm-button">Confirmer</button>
                                    </form>

                                </div>
                            </div>
                            <?php
                            if ($info["en_ligne"]) {
                            ?>
                                <div class="confirmation-modal" id="myModal2">
                                    <div class="modal-content">
                                        <span class="close" onclick="fermeModal()">&times;</span>
                                        <p>Êtes-vous sûr de vouloir mettre ce logement hors ligne ?</p>
                                        <form method="GET" action="logement.php">
                                            <input type="hidden" name="confirmHorsligne" value="<?php echo $id ?>">

                                            <button class="confirm-button">Confirmer</button>
                                        </form>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if (!$info["en_ligne"]) {
                            ?>
                                <div class="confirmation-modal" id="myModal2">
                                    <div class="modal-content">
                                        <span class="close" onclick="fermeModal()">&times;</span>
                                        <p>Êtes-vous sûr de vouloir mettre ce logement en ligne ?</p>
                                        <form method="GET" action="logement.php">
                                            <input type="hidden" name="confirmligne" value="<?php echo $id ?>">

                                            <button class="confirm-button">Confirmer</button>
                                        </form>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?> <!-- Fin de la partie avec les bouton de modification et autres -->
                        <div class="barre_log">
                            <svg width="100%" height="10" viewBox="0 0 1920 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_60_122)">
                                    <rect width="1920" height="1" transform="matrix(1 0 0 -1 0 4)" fill="#D9D9D9" />
                                </g>
                                <defs>
                                    <filter id="filter0_d_60_122" x="-4" y="0" width="1928" height="9" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                        <feOffset dy="1" />
                                        <feGaussianBlur stdDeviation="2" />
                                        <feComposite in2="hardAlpha" operator="out" />
                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_60_122" />
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_60_122" result="shape" />
                                    </filter>
                                </defs>
                            </svg>
                        </div>


                        <div class="blanc2">










                            <div id="AvisSection">

                                <div class="barreHautAvis">
                                    <div class="gaucheBarreHautAvis">
                                        <p> <span>Avis</span> : <?php echo ($rrrrrrrrrrrowCount); ?> avis</p>
                                    </div>
                                    <div class="droiteBarreHautAvis">
                                        <button id="AjoutAvis">Ajouter un avis</button>
                                    </div>
                                </div>

                                <div id="ListeAvis">

                                    <?php
                                    foreach ($aviss as $key => $unAvis) {

                                        if ($images = opendir('asset/img/profils/')) {
                                            while (false !== ($fichier = readdir($images))) {
                                                $imgInfos = pathinfo($fichier);
                                                if ($imgInfos['filename'] == $unAvis["id_compte"]) {
                                                    $pathName = 'asset/img/profils/' . $fichier;
                                                    break;
                                                }
                                            }
                                            if ($pathName == '') {
                                                $pathName = 'asset/img/profils/default.jpg';
                                            }
                                            closedir($images);
                                        }
                                    ?>

                                        <div class="unAvis">

                                            <div class="hautAvis">

                                                <div class="infoPropioAvis">

                                                    <a class="img_avis_log" href="pageProprio.php?id=<?php echo ($proprio["id_compte"]); ?>&id_log=<?php echo ($id) ?>">
                                                        <div class="photo_profil_proprio_log_avis">
                                                            <style>
                                                                .photo_profil_proprio_log_avis {
                                                                    background: url("<?php echo ($pathName) ?>") center/cover;
                                                                }
                                                            </style>
                                                        </div>

                                                    </a>
                                                    <div class="infoTxtProprioAvis">
                                                        <h3>Tom Eilis</h3>
                                                        <p>120 avis</p>
                                                    </div>
                                                </div>

                                                <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16.0156 13C16.0156 14.9434 14.4434 16.5156 12.5 16.5156C10.5566 16.5156 8.98438 14.9434 8.98438 13C8.98438 11.0566 10.5566 9.48438 12.5 9.48438C14.4434 9.48438 16.0156 11.0566 16.0156 13ZM21.0938 9.48438C19.1504 9.48438 17.5781 11.0566 17.5781 13C17.5781 14.9434 19.1504 16.5156 21.0938 16.5156C23.0371 16.5156 24.6094 14.9434 24.6094 13C24.6094 11.0566 23.0371 9.48438 21.0938 9.48438ZM3.90625 9.48438C1.96289 9.48438 0.390625 11.0566 0.390625 13C0.390625 14.9434 1.96289 16.5156 3.90625 16.5156C5.84961 16.5156 7.42188 14.9434 7.42188 13C7.42188 11.0566 5.84961 9.48438 3.90625 9.48438Z" fill="#1D4C77" />
                                                </svg>
                                            </div>

                                            <div class="milieuAvis">

                                                <div class="noteAvisLog">

                                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                                                    </svg> <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                                                    </svg> <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                                                    </svg> <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                                                    </svg> <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z" fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                                                    </svg>
                                                </div>

                                                <p>Il y a 2 jours</p>
                                            </div>

                                            <div class="ContentAvis">
                                                <p><?php echo ($unAvis["contenu"]); ?></p>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>



    </main>





    <!-- Footer -->
    <footer>

        <div id="infosFooter">
            <div id="footerCercleLogo">
                <img src="asset/img/logoRond.svg" alt="logo">
            </div>
            <div id="textefooter">
                <div id="infosLegal">
                    <h2>Informations légales</h2>
                    <ul>
                        <li><a href="">Plan du site</a></li>
                        <li><a href="mentionsLegales.php">Mentions légales</a></li>
                        <li><a href="cgv.php">Conditions générales de ventes</a></li>
                        <li><a href="cgu.php">Conditions générales d'utilisation</a></li>
                        <li><a href="">Truc utile a savoir</a></li>
                    </ul>
                </div>
                <div id="support">
                    <h2>Support client</h2>
                    <a href="">Contacter le support</a>
                </div>
                <div id="reseaux">
                    <h2>Suivez nous</h2>
                    <div id="logoReseaux">
                        <a href=""><img src="asset/icons/blanc/facebook.svg" alt=""></a>
                        <a href=""><img src="asset/icons/blanc/instagram.svg" alt=""></a>
                        <a href=""><img src="asset/icons/blanc/steam.svg" alt=""></a>
                    </div>
                </div>
                <div id="contact">
                    <h2>Nous contacter</h2>
                    <p>Rue Édouard Branly, 22300 Lannion</p>
                    <p>02 96 46 93 00</p>
                    <p>iut-lannion.univ-rennes.fr</p>
                </div>
            </div>
        </div>

        <div class="basFooter">
            <p>Copyright @ 2023 LoQuali.com</p>
        </div>

    </footer>
    <script src="./asset/js/dateReservDisplay.js"></script>
</body>

</html>