<?php

/* Démarage de la session, et suppression du logement si le propriétaire décide d'annuler la création de logement */

  session_start();
  error_reporting(0);
    include('connect_params.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $query = $dbh->prepare("SELECT * FROM test.logement WHERE id_logement = :idlog");
    $query->bindParam('idlog', $_GET["confirmDelete"], PDO::PARAM_INT);
    $query->execute();
    $query = $query->fetchAll();
    if (isset($_GET["confirmDelete"]) ) {
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

?> 



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">


</head>
<body>
        <?php  /* Gros ajout à la bdd afin de crée un logement avec toutes les informations que le propriétaire à remplit */

            $info=$_POST;

            $photo=$_FILES;

            


            include('connect_params.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);



            /* liste de toutes les données à mettre dans la bdd dans l'ordre de l'insert dans la bdd*/ 


            $prix_TTC = $info["prix"]*1.1;

            $note_logement=0; // pas def
            $en_ligne=1;   // à def en fonction de si le boug veux ou ne veux pas le mettre en ligne
            $ouvert=0;

            $type_logement="T".$info["Pieces"];
            $nature_logement = $info["type"];
            $localisation = $info["ville"];
            $adresse= $info["adresse"];
            $complement_adresse= $info["appartement"];
            $descriptif = $info["description"];

            $surface = 20; // pas def

            $disponible_defaut = 1; // pas encore def


            $prix_base_HT = $info["prix"];


            $delai_annul_defaut = 8;  // pas def
            $pourcentage_retenu_defaut = 15; // je sais pas ce que c'est


            $libelle_logement = $info["titre"];
            $accroche="";
            $nb_pers_max = $info["Personne"];
            $nb_chambre = $info["nbChambre"];
            $nb_salle_de_bain = $info["nbSalle_bain"];
            $code_postal = 29370;
            $departement = htmlentities($info["dep"]);
            $info_arrivee = $info["info_arrive"];
            $info_depart = $info["info_depart"];
            $reglement_interieur = $info["Règlement"];

            $id_compte = $_SESSION['userId'];


            // Préparer la requête d'insertion
            $stmt = $dbh->prepare("
                INSERT INTO test.logement (
                    prix_TTC,
                    en_ligne,
                    type_logement,
                    nature_logement,
                    descriptif,
                    surface,
                    prix_base_HT,
                    libelle_logement,
                    accroche,
                    nb_pers_max,
                    nb_chambre,
                    nb_salle_de_bain,
                    code_postal,
                    departement,
                    localisation,
                    adresse,
                    complement_adresse,
                    info_arrivee,
                    info_depart,
                    reglement_interieur,
                    id_compte
                ) VALUES (
                    :prix_TTC,
                    :en_ligne,
                    :type_logement,
                    :nature_logement,
                    :descriptif,
                    :surface,
                    :prix_base_HT,
                    :libelle_logement,
                    :accroche,
                    :nb_pers_max,
                    :nb_chambre,
                    :nb_salle_de_bain,
                    :code_postal,
                    :departement,
                    :localisation,
                    :adresse,
                    :complement_adresse,
                    :info_arrivee,
                    :info_depart,
                    :reglement_interieur,
                    :id_compte
                )
            ");

            // Binder les valeurs
            $stmt->bindParam(':prix_TTC', $prix_TTC);
            $stmt->bindParam(':en_ligne', $en_ligne, PDO::PARAM_INT);
            $stmt->bindParam(':type_logement', $type_logement);
            $stmt->bindParam(':nature_logement', $nature_logement);
            $stmt->bindParam(':descriptif', $descriptif);
            $stmt->bindParam(':surface', $surface);
            $stmt->bindParam(':prix_base_HT', $prix_base_HT);
            $stmt->bindParam(':libelle_logement', $libelle_logement);
            $stmt->bindParam(':accroche', $accroche);
            $stmt->bindParam(':nb_pers_max', $nb_pers_max);
            $stmt->bindParam(':nb_chambre', $nb_chambre);
            $stmt->bindParam(':nb_salle_de_bain', $nb_salle_de_bain);
            $stmt->bindParam(':code_postal', $code_postal);
            $stmt->bindParam(':departement', $departement);
            $stmt->bindParam(':localisation', $localisation);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':complement_adresse', $complement_adresse);
            $stmt->bindParam(':info_arrivee', $info_arrivee);
            $stmt->bindParam(':info_depart', $info_depart);
            $stmt->bindParam(':reglement_interieur', $reglement_interieur);
            $stmt->bindParam(':id_compte', $id_compte);

            try {
                // Exécuter la requête
                $stmt->execute();


            } catch (PDOException $e) {
                // Afficher l'erreur en cas d'échec de la requête
                echo "Erreur lors de l'insertion : " . $e->getMessage();
            }



            /* Récupération de l'id du logement qu'on crée */

            $i=0;

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $query = "SELECT DISTINCT id_logement FROM test.logement ORDER BY id_logement DESC LIMIT 1";
    
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $id_log_T = $stmt->fetch();
            $id_log=$id_log_T["id_logement"];

            /* Ajout des aménagements */

            if (isset($_POST["amena"])){
                foreach ($info["amena"] as $ind => $val){
                    $stmt = $dbh->prepare("
                        INSERT INTO test.amenagement (
                            nom_amenagement,
                            id_logement
                        ) VALUES (
                            :nom_amenagement,
                            :id_logement
                        )
                    ");
    
                    $stmt->bindParam(':nom_amenagement', $val);
                    $stmt->bindParam(':id_logement', $id_log);
    
    
                    try {
                        // Exécuter la requête
                        $stmt->execute();
    
                    } catch (PDOException $e) { 
                        // Afficher l'erreur en cas d'échec de la requête
                        echo "Erreur lors de l'insertion : " . $e->getMessage();
                    }
                }
    
            }


            if (isset($_POST["instal"])){
            /* Ajout des installations */

                foreach ($info["instal"] as $ind => $val){
                    $stmt = $dbh->prepare("
                        INSERT INTO test.installation (
                            nom_installation,
                            id_logement
                        ) VALUES (
                            :nom_installation,
                            :id_logement
                        )
                    ");

                    $stmt->bindParam(':nom_installation', $val);
                    $stmt->bindParam(':id_logement', $id_log);


                    try {
                        // Exécuter la requête
                        $stmt->execute();
                    } catch (PDOException $e) { 
                        // Afficher l'erreur en cas d'échec de la requête
                        echo "Erreur lors de l'insertion : " . $e->getMessage();
                    }
                }
            }









            /* Ajout des services */

            if (isset($_POST["service"])){
                foreach ($info["service"] as $ind => $val){
                    $stmt = $dbh->prepare("
                        INSERT INTO test.service (
                            nom_service,
                            id_logement
                        ) VALUES (
                            :nom_service,
                            :id_logement
                        )
                    ");
    
                    $stmt->bindParam(':nom_service', $val);
                    $stmt->bindParam(':id_logement', $id_log);
    
    
                    try {
                        // Exécuter la requête
                        $stmt->execute();
                    } catch (PDOException $e) { 
                        // Afficher l'erreur en cas d'échec de la requête
                        echo "Erreur lors de l'insertion : " . $e->getMessage();
                    }
                }

       
            }


            /* Suite du code */
                        


            /* Gestion des photos */

            if (isset($_FILES["photo"])) {
                $img_dir = "asset/img/logements";
        

                
                foreach ($_FILES["photo"]["error"] as $key => $error) { /* ajout des photos à la bdd et déléchargement des photos */
                    if ($error == 0) {
                        $tmpName = $_FILES["photo"]["tmp_name"][$key];


                        
                        try {
                            $i=0;
                            foreach($dbh->query("SELECT DISTINCT id_image from test.image ORDER BY id_image DESC", PDO::FETCH_ASSOC) as $row) {
            
                                $photo2[$i]=$row;
                                $i++;
                            }
                        } catch (PDOException $e) {
                            print "Erreur !: " . $e->getMessage() . "<br/>";
                            die();
                        }
                
                        
                    



                        
                        $nom_photo = $_FILES["photo"]["name"][$key];
                        $extention=explode(".",$nom_photo);



                        $id_p = $photo2[0]["id_image"]+1;

                        $prev_photo[$j]=$id_p;
                        $j++;


                        $chemin = $img_dir . "/" . $id_p.".".$extention[1];
                        move_uploaded_file($tmpName, $chemin);

                        $i=0;



                        $stmt = $dbh->prepare("
                            INSERT INTO test.image (
                                extension_image
                            ) VALUES (
                                :extension_image
                            )
                        ");

                        $stmt->bindParam(':extension_image', $extention[1]);



                        try {
                            // Exécuter la requête
                            $stmt->execute();


                        } catch (PDOException $e) { 
                            // Afficher l'erreur en cas d'échec de la requête
                            echo "Erreur lors de l'insertion : " . $e->getMessage();
                        }




                        $stmt = $dbh->prepare("
                            INSERT INTO test.photo_logement (
                                id_logement,
                                id_image
                            ) VALUES (
                                :id_logement,
                                :id_image
                            )
                        ");

                        $stmt->bindParam(':id_image', $id_p);
                        $stmt->bindParam(':id_logement', $id_log);


                        try {
                            // Exécuter la requête
                            $stmt->execute();


                        } catch (PDOException $e) {
                            // Afficher l'erreur en cas d'échec de la requête
                            echo "Erreur lors de l'insertion : " . $e->getMessage();
                        }


                    }
                }





                $i=0; /* Réccupération des informations de la bdd pour afficher le logements */
                foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement =$id_log", PDO::FETCH_ASSOC) as $row) {

                    $photo3[$i]=$row;
                    $i++;
                }



            
                try {
                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $query = "SELECT * FROM test.compte NATURAL JOIN test.telephone WHERE id_compte = :id_compte";
            
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam('id_compte', $id_compte, PDO::PARAM_STR);
                    $stmt->execute();
                    $proprio = $stmt->fetch();
                }   catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }


                $stmt = $dbh->prepare("
                INSERT INTO test.lit (
                    nombre_lit,
                    id_logement
                ) VALUES (
                    :nombre_lit,
                    :id_logement
                )
                ");

                $stmt->bindParam(':nombre_lit', $info["nbLit"]);
                $stmt->bindParam(':id_logement', $id_log);


                try {
                    // Exécuter la requête
                    $stmt->execute();

                } catch (PDOException $e) { 
                    // Afficher l'erreur en cas d'échec de la requête
                    echo "Erreur lors de l'insertion : " . $e->getMessage();
                }


                $dbh = null;


            }

            


        ?>







    <div class="sticky_header_log">



    <?php
    session_start();
    $linkAccount = '/sae_martin/connexion.php';
    if (isset($_SESSION['username'])) {
        $linkAccount = '/sae_martin/account.php';
    }
    ?>
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
        <h4><a href="compte.php?res=res"><?php if ($_SESSION["userType"]=="proprietaire"){echo("Mes logements");} else {echo("Mes réservations");} ?></a></h4>
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
    <!-- Fin de la classe header -->



            <!-- La suite de cette page est exactement la même mis à part les bouton de confirmation de création qui ramène à l'acceuil et l'annulation de la création qui supprime le logement -->
    


        <div class="barre_fin_header_log"> <!-- Début de la barre de séparation du header -->
            <svg width="100%" height="23" viewBox="0 0 1920 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_15_355)">
                    <path d="M0 6H1920V9H0V6Z" fill="#97ADC1" />
                </g>
                <defs>
                    <filter id="filter0_d_15_355" x="-7" y="0" width="1940" height="23" filterUnits="userSpaceOnUse"
                        color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                            result="hardAlpha" />
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


    </div>  <!-- Fin du header -->


    <main> <!-- Début de la page  -->


        <div class="blanc"> <!-- Début du div pour mettre le background en blanc -->


            <div class="titre_log"> <!-- Partie pour afficher le "titre" du logement et le reste -->
                <h3><?php  echo($info["titre"]);  ?></h3>
                <div class="titre_log_bas">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.28537 8.95113L8.93285 8.85804L9.22545 8.27299L12.2766 2.17243C12.3034 2.11885 12.3339 2.09226 12.3627 2.0755C12.3962 2.056 12.4428 2.04182 12.4977 2.04199C12.6083 2.04235 12.6831 2.09285 12.724 2.17358C12.7241 2.17381 12.7242 2.17404 12.7243 2.17427L15.7745 8.27299L16.0672 8.85804L16.7146 8.95113L23.5417 9.93264L23.5426 9.93277C23.6081 9.94213 23.6457 9.96463 23.6704 9.98611C23.6989 10.0108 23.7235 10.0466 23.7379 10.0901C23.7522 10.1336 23.7526 10.174 23.7454 10.2055C23.7394 10.2319 23.7252 10.2672 23.681 10.3097L23.6806 10.31L18.7414 15.0563L18.2619 15.5171L18.376 16.1722L19.5441 22.876C19.5441 22.8762 19.5442 22.8765 19.5442 22.8767C19.5538 22.9329 19.545 22.9667 19.5345 22.9906C19.5216 23.0198 19.4959 23.0546 19.4538 23.0845C19.3684 23.1451 19.2732 23.1524 19.1829 23.1056L19.1827 23.1055L13.0752 19.9397L12.5 19.6416L11.9248 19.9397L5.81729 23.1054L5.81339 23.1075C5.72476 23.1538 5.63192 23.1471 5.54712 23.0869C5.50503 23.057 5.47899 23.0218 5.46577 22.9918C5.45488 22.9671 5.44612 22.9327 5.45587 22.8762C5.45589 22.8762 5.4559 22.8761 5.45591 22.876L6.62398 16.1722L6.73813 15.5171L6.25864 15.0563L1.31939 10.31L1.31904 10.3097C1.27481 10.2672 1.26058 10.2319 1.25457 10.2055C1.24738 10.174 1.24784 10.1336 1.26215 10.0901C1.27647 10.0466 1.30113 10.0108 1.3296 9.98611C1.35432 9.96463 1.39195 9.94213 1.45737 9.93277L1.45827 9.93264L8.28537 8.95113Z"
                            fill="#1D4C77" stroke="#1D4C77" stroke-width="2.5" />
                    </svg>
                    <p> <?php  echo($info["adresse"]);  ?>, <?php  echo($info["ville"]);  ?>, <?php  echo($info["code_postal"]);   ?></p>
                </div>
            </div>

            <div class="images_log"> <!-- Partie pour montrer les images (il manque le carrousel) -->

            <?php  
                $i =     0;
                $div = 0;
                $lig=1;
                $delai=-2;
                foreach ($photo3 as $ind => $nom) {

                    if ($i == 1) {
                        $div = 1;
                    ?>

                    <div class="images_log_droite">

                    <?php
                    }

                    if ($lig==2){
                        $delai=0;
                        $lig=0;
                    ?>

                    <div class="lig_images_log_droite">

                    <?php
                    }

                    if ($i < 5) {
                    ?>

                    <img src="asset/img/logements/<?php echo($nom["id_image"]); ?>.<?php echo($nom["extension_image"]); ?>" alt="problème">

                    <?php
                    }


                    if ($delai==1){
                        echo("</div>");
                    }

                    $lig++;
                    $delai++;
                    $i++;
                }

                if ($div == 1) {
                    echo("</div>");
                }

            ?>
            </div>



        </div> <!-- Fin du div pour le background blanc -->



        <div class="barre_log">  <!-- Barre de séparation -->
            <svg width="100%" height="10" viewBox="0 0 1920 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_60_122)">
                    <rect width="1920" height="1" transform="matrix(1 0 0 -1 0 4)" fill="#D9D9D9" />
                </g>
                <defs>
                    <filter id="filter0_d_60_122" x="-4" y="0" width="1928" height="9" filterUnits="userSpaceOnUse"
                        color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                            result="hardAlpha" />
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


        <div class="sticky_res_and_map_log">  <!-- Div pour rendre faire glisser la map et la case réservation -->

            <div class="barre_info_log">

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

                <div class="proprio_log">
                    
                    <a class="img_proprio_log" href="pageProprio.php?id=<?php echo ($proprio["id_compte"]); ?>&id_log=<?php echo ($id) ?>">
                        <div class="photo_profil_proprio_log">
                            <style>
                                .photo_profil_proprio_log {
                                    background: url("<?php echo($pathName) ?>") center/cover;
                                }
                            </style>
                        </div>

                    </a>
                    <div class="info_proprio_log">
                        <div class="block_info_log">
                            <h2><?php echo ($proprio["nom_affichage"]) ?></h2>
                        </div>
                        <?php
                        if ($proprio["note_proprio"]!=""){
                            ?>
                            <div class="note_proprio_log">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.3646 1.22353L7.5304 7.12042L1.18926 8.06909C0.052104 8.23834 -0.403625 9.67693 0.421028 10.5009L5.0087 15.0884L3.92363 21.5687C3.72832 22.7401 4.93058 23.6175 5.93752 23.0696L11.6103 20.0099L17.283 23.0696C18.29 23.613 19.4922 22.7401 19.2969 21.5687L18.2118 15.0884L22.7995 10.5009C23.6242 9.67693 23.1684 8.23834 22.0313 8.06909L15.6901 7.12042L12.8559 1.22353C12.3481 0.172417 10.8768 0.159056 10.3646 1.22353Z"
                                        fill="#F5F5F5" />
                                </svg>
                                <p><?php echo($proprio["note_proprio"]) ?></p>

                            </div>
                            <?php
                                }
                            ?>
                        <div class="block_info_log">
                            <div class="contact_proprio_log">
                                <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20.4011 16.0445L15.8073 14.0242C15.611 13.9384 15.3929 13.9203 15.1858 13.9727C14.9787 14.0251 14.7937 14.1451 14.6588 14.3146L12.6244 16.8653C9.4316 15.3205 6.86212 12.6838 5.35673 9.40742L7.84232 7.31978C8.0079 7.18159 8.12509 6.9918 8.17615 6.77916C8.22722 6.56651 8.20938 6.34258 8.12534 6.14127L6.15655 1.42723C6.06431 1.21022 5.90117 1.03304 5.69526 0.92624C5.48935 0.819439 5.25358 0.789713 5.0286 0.842188L0.762904 1.85234C0.545997 1.90374 0.352472 2.02906 0.213915 2.20786C0.0753574 2.38666 -4.99665e-05 2.60837 2.48403e-08 2.83681C2.48403e-08 13.6328 8.5273 22.3664 19.0316 22.3664C19.2543 22.3665 19.4704 22.2892 19.6447 22.147C19.8191 22.0048 19.9413 21.8062 19.9914 21.5835L20.9758 17.2062C21.0266 16.9742 20.997 16.7313 20.8921 16.5193C20.7872 16.3073 20.6136 16.1394 20.4011 16.0445Z"
                                        fill="#F5F5F5" />
                                </svg>
                                <p><?php  echo wordwrap($proprio["numero"], 2, " ", 1); ?></p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="details_log">
                    <div class="txt_details_log">
                        <p class="txt_info_log"> <?php echo($info["type"]); ?> · <?php echo($info["Pieces"]); ?> pièces · <?php echo($info["Personne"]); ?> personnes</p>
                        <p class="txt_info_log"><?php echo($info["nbChambre"]); ?> chambres · <?php echo($info["nbLit"]); ?> lits · <?php echo($info["nbSalle_bain"]); ?> salle de bain</p>
                        <p class="txt_info_log"><?php  echo($info["adresse"]);  ?>, <?php  echo($info["ville"]);  ?></p>
                        <p class="txt_info_log petit">Posté le 13/09/2023 à 20h56</p>
                    </div>
                </div>
            </div>




            <div class="res_and_map_log">



                <div class="dispo_date_log">
                    <p><span> Disponibilité de réservation : </span></p>
                    <a class="bouton_date_log" >
                        <svg width="26" height="31" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.43766 17.2659H6.15721C5.78094 17.2659 5.47307 16.9499 5.47307 16.5638V14.2237C5.47307 13.8376 5.78094 13.5217 6.15721 13.5217H8.43766C8.81393 13.5217 9.12179 13.8376 9.12179 14.2237V16.5638C9.12179 16.9499 8.81393 17.2659 8.43766 17.2659ZM14.5949 16.5638V14.2237C14.5949 13.8376 14.287 13.5217 13.9107 13.5217H11.6303C11.254 13.5217 10.9461 13.8376 10.9461 14.2237V16.5638C10.9461 16.9499 11.254 17.2659 11.6303 17.2659H13.9107C14.287 17.2659 14.5949 16.9499 14.5949 16.5638ZM20.0679 16.5638V14.2237C20.0679 13.8376 19.7601 13.5217 19.3838 13.5217H17.1034C16.7271 13.5217 16.4192 13.8376 16.4192 14.2237V16.5638C16.4192 16.9499 16.7271 17.2659 17.1034 17.2659H19.3838C19.7601 17.2659 20.0679 16.9499 20.0679 16.5638ZM14.5949 22.1801V19.84C14.5949 19.4539 14.287 19.1379 13.9107 19.1379H11.6303C11.254 19.1379 10.9461 19.4539 10.9461 19.84V22.1801C10.9461 22.5662 11.254 22.8821 11.6303 22.8821H13.9107C14.287 22.8821 14.5949 22.5662 14.5949 22.1801ZM9.12179 22.1801V19.84C9.12179 19.4539 8.81393 19.1379 8.43766 19.1379H6.15721C5.78094 19.1379 5.47307 19.4539 5.47307 19.84V22.1801C5.47307 22.5662 5.78094 22.8821 6.15721 22.8821H8.43766C8.81393 22.8821 9.12179 22.5662 9.12179 22.1801ZM20.0679 22.1801V19.84C20.0679 19.4539 19.7601 19.1379 19.3838 19.1379H17.1034C16.7271 19.1379 16.4192 19.4539 16.4192 19.84V22.1801C16.4192 22.5662 16.7271 22.8821 17.1034 22.8821H19.3838C19.7601 22.8821 20.0679 22.5662 20.0679 22.1801ZM25.541 6.96933V27.5624C25.541 29.1127 24.3153 30.3705 22.8045 30.3705H2.73654C1.22574 30.3705 0 29.1127 0 27.5624V6.96933C0 5.419 1.22574 4.16118 2.73654 4.16118H5.47307V1.11903C5.47307 0.732908 5.78094 0.416992 6.15721 0.416992H8.43766C8.81393 0.416992 9.12179 0.732908 9.12179 1.11903V4.16118H16.4192V1.11903C16.4192 0.732908 16.7271 0.416992 17.1034 0.416992H19.3838C19.7601 0.416992 20.0679 0.732908 20.0679 1.11903V4.16118H22.8045C24.3153 4.16118 25.541 5.419 25.541 6.96933ZM22.8045 27.2114V9.77747H2.73654V27.2114C2.73654 27.4044 2.89047 27.5624 3.0786 27.5624H22.4624C22.6505 27.5624 22.8045 27.4044 22.8045 27.2114Z"
                                fill="#F5F5F5" />
                        </svg>
                        <p>Dates</p>
                    </a>
                </div>

                <div class="rerservation_log">
                    <div class="haut_rerservation_log">
                        <h2><span><?php echo($info["prix"]); ?> €</span>  / nuit</h2>
                        <a class="bouton_res_log">
                            <h1>Réserver</h1>
                        </a>
                    </div>
                    <div class="bare_res"></div>
                    <div class="detail_reservation_log">

                    <?php
                    $t=0;
                    foreach ($info as $nom => $menfou) {
                        if ($nom=="amena") {
                            $t=1;
                        }
                    }
                    
                    if ($t==1) {

                    ?>
                    

                        <p>Aménagments proposés : </p>
                        <div class="amenagement_log">


                            <?php
                            $i=0;
                            $magouille='<div class="ligne_elem_res_log">';
                            
                            foreach($info["amena"] as $ind => $key) {

                            ?>


                                
                                <?php
                                    if ($i==0){
                                        echo($magouille);
                                    }
                                ?> 


                                <div class="elem_reservation_log">
                                    <img src="asset/icons/blanc/<?php echo($key);?>.svg" alt="">
                                    <p><?php echo($key);?></p>
                                </div>



                                <?php 
                                
                                    $i++;
                                    if ($i>=2){
                                        echo("</div>");
                                        $i=0;
                                    }

                                ?>
                            
                            <?php } 
                            
                                if ($i!=0){
                                    echo("</div>");
                                }

                            ?>
                        </div>
                    <?php } else {
                        echo("<br/><p>Aucun aménagement</p><br/>");
                    } ?>


                    <?php
                    $t=0;
                    foreach ($info as $nom => $menfou) {
                        if ($nom=="instal") {
                            $t=1;
                        }
                    }
                    
                    if ($t==1) {

                    ?>

                        <p>Installations : </p>
                        <div class="installations_log">

                            <?php
                            $i=0;
                            $magouille='<div class="ligne_elem_res_log">';
                            
                            foreach($info["instal"] as $ind => $key) {

                            ?>



                                <?php
                                    if ($i==0){
                                        echo($magouille);
                                    }
                                ?> 
                                
                                

                                <div class="elem_reservation_log">
                                    <img src="asset/icons/blanc/<?php echo($key);?>.svg" alt="">
                                    <p> <?php echo($key);?> </p>
                                </div>


                                
                                <?php 
                                
                                    $i++;
                                    if ($i>=2){
                                        echo("</div>");
                                        $i=0;
                                    }

                                ?>

                            <?php } 
                            
                                if ($i!=0){
                                    echo("</div>");
                                }

                            ?>

                        </div>

                        <?php } else {
                        echo("<br/><p>Aucune instalation</p><br/>");
                    } ?>


                        <?php
                            $t=0;
                            foreach ($info as $nom => $menfou) {
                                if ($nom=="service") {
                                    $t=1;
                                }
                            }
                            
                            if ($t==1) {

                        ?>
                        
                        <p>Services compris : </p>
                        <div class="services_log">

                            <?php
                            $i=0;
                            $magouille='<div class="ligne_elem_res_log">';
                            foreach($info["service"] as $ind => $key) {

                            ?>

                                <?php
                                    if ($i==0){
                                        echo($magouille);
                                    }
                                ?>

                                <div class="elem_reservation_log">

                                <img src="asset/icons/blanc/<?php echo($key);?>.svg" alt="">
                                <p> <?php echo($key);?> </p>



                                </div>

                                <?php 
                                
                                $i++;
                                if ($i>=2){
                                    echo("</div>");
                                    $i=0;
                                }

                            ?>

                        <?php } 
                        
                            if ($i!=0){
                                echo("</div>");
                            }

                        ?>
                        </div>

  
                        <?php } else {
                        echo("<br/><p>Aucun service proposé</p><br/>");
                    } ?>

                        
                    </div>
                    
                </div>

            
                <div class="button_valider">
                    <a href="index.php">Crée le logement</a>
                </div>
                    
                <div class="button_refuser">
                    <button  onclick="openModal()">Annuler</button>
                </div>


                <div class="confirmation-modal button_annuler" id="myModal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <p>Êtes-vous sûr de vouloir annuler la création de ce logement ?</p>
                        <form method="GET" action="logement.php">
                            <input type="hidden" name="confirmDelete" value="<?php echo $id_log ?>">
                            <button class="confirm-button">Confirmer</button>
                        </form>
                    
                    </div>
                </div>

            </div>


            <div class="description_log">
                <h4>Description :</h4>
                <p class="txt_descr_log"><?php echo($info["description"]); ?></p>

            </div>

            <div class="sep_descri_regle_log">

            </div>


            <div class="reglement_log">
                <h4>Réglement intérieur : </h4>
                <p class="txt_descr_log"><?php echo($info["Règlement"]); ?></p>

            </div>

            <div class="sep_descri_regle_log">

            </div>


            <div class="info_arrive_log">
                <h4>Informations d’arrivée :</h4>
                <p class="txt_descr_log"><?php echo($info["info_arrive"]);?></p>

            </div>

            <div class="sep_descri_regle_log">

            </div>


            <div class="info_depart_log">
                <h4>Informations de départ :</h4>
                <p class="txt_descr_log"><?php echo($info["info_depart"]);?></p>

            </div>
        </div>




        <div class="barre_log">
            <svg width="100%" height="10" viewBox="0 0 1920 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_60_122)">
                    <rect width="1920" height="1" transform="matrix(1 0 0 -1 0 4)" fill="#D9D9D9" />
                </g>
                <defs>
                    <filter id="filter0_d_60_122" x="-4" y="0" width="1928" height="9" filterUnits="userSpaceOnUse"
                        color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                            result="hardAlpha" />
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






        </div>
    </main>




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
    <script src="asset/js/script.js"></script>
    <script src = "asset/js/boutonSupprimer.js"></script>

</body>
</html>