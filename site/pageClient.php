<?php
    session_start();
    error_reporting(0);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/pagePerso.css">
    <link rel="stylesheet" href="asset/css/pageProprio.css">
    <title>Document</title>
</head>
<body>

<header>
    <a href="index.php">
      <img src="asset/img/logo.png" alt="logo Loquali">
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

  <!-- Cette pag est exactement la même que pagePersoProprio à la seul différence près que cette page n'as pas d'option de modification -->
  
  <main id="ensemble">

    
  <?php

    $id = $_GET["id"];
    $id_log = $_GET["id_log"];
    include('connect_params.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.client NATURAL JOIN test.compte WHERE id_compte = :id_compte";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
        $stmt->execute();
        $current = $stmt->fetch();

    }   catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    ?>


    <?php //récupération du nom de l'image (avec extension)
                                
    if ($images = opendir('asset/img/profils/')) {
        while (false !== ($fichier = readdir($images))) {
            $imgInfos = pathinfo($fichier);
            if ($imgInfos['filename'] == $id) {
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

 
    <a href="logement.php?id=<?php echo($id_log); ?>">
        <img src="asset/icons/bleu/toBack.svg" alt="Icone Retour" id="pagePersoSvgBack">
    </a>
        <div class="infosProprio">
            <div id="infosTous">
                <div id="photo_Profil">
                    <style>
                        #photo_Profil {
                            width:160px;
                            height:160px;
                            border-radius: 93.5px;

                            background: url("<?php echo($pathName) ?>") center/cover;
                        }

                        @media screen and (min-width: 0px) and (max-width: 400px) {
                            #photo_Profil {
                                width: 110px;
                                height: 110px;
                                border-radius: 110px;
                                position: relative;
                                left: -20px;
                                

                            }
                        }
                    </style>
                </div>
                <div class = "infos">
                    <h2><?php echo $current['nom'] ?> <?php echo($current['prenom']) ?></h2>



                </div>

            </div>
                <div class="separateur">
                </div>

                <div id="aProposDeMoi">
                    <div>
                        <h2>Compte Voyageur</h2>
                    </div>
                    <div class="descriptionPersonne">
                            <input type="submit" value="Enregistrer" id="modificationDescription" class="modifBtn">
                            <p id="champsDescription" class="descriptionCompte"><?php echo htmlentities($current["description"]) ?></p>
                        </form>
                    </div>

                </div>
        
        
        </div>
        <div id="logementPropo">
            <h2 id="titreLogement"><?php if ($current["userType"]=="propriétaire") ?>Avis Posté</h2>
            <div id="listeLogements">



            <?php

            try {
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $query = "SELECT COUNT(*) FROM test.avis WHERE id_compte = $id;";
                $stmt = $dbh->prepare($query);
                $stmt->execute();
                $nbLogements = $stmt->fetch();

                if ($nbLogements['count'] == 0) {
                    ?>
                    <p id="AucunLogement">Pas d'avis posté</p>
                    <?php
                }

                try {

                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $query = "SELECT * FROM test.avis NATURAL JOIN test.compte WHERE id_compte = :idcompte";

                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam('idcompte', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $aviss = $stmt->fetchAll();

                    $rrrrrrrrrrrowCountttt = $stmt->rowCount();

                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }
                $nbAvis = 0;
                
                            foreach ($aviss as $key => $unAvis) {
                                $nbAvis++;

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

                                            <a class="img_avis_log"
                                                href="pageProprio.php?id=<?php echo ($unAvis["id_compte"]); ?>&id_log=<?php echo ($id) ?>">
                                                <div class="photo_profil_proprio_log_avis ppAvisLogRrrrowcount<?php echo ($unAvis['id_compte']); ?>"
                                                    style='background: url("<?php echo ($pathName) ?>") center/cover;'>
                                                </div>

                                            </a>
                                            <div class="infoTxtProprioAvis">
                                                <h3>
                                                    <?= $unAvis["nom"] ?> <?= $unAvis['prenom'] ?>
                                                </h3>
                                                <?php
                                                try {

                                                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                                                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                                                    $query = "SELECT * FROM test.avis NATURAL JOIN test.compte WHERE id_compte = :idcompte";

                                                    $stmt = $dbh->prepare($query);
                                                    $stmt->bindParam('idcompte', $unAvis["id_compte"], PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $aviss = $stmt->fetchAll();

                                                    $rrrrrrrrrrrowCount2 = $stmt->rowCount();

                                                } catch (PDOException $e) {
                                                    print "Erreur !: " . $e->getMessage() . "<br/>";
                                                    die();
                                                }
                                                ?>
                                                <p>
                                                    <?= $rrrrrrrrrrrowCount2 ?> avis
                                                </p>
                                            </div>
                                        </div>

                                        <svg width="25" height="26" viewBox="0 0 25 26" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.0156 13C16.0156 14.9434 14.4434 16.5156 12.5 16.5156C10.5566 16.5156 8.98438 14.9434 8.98438 13C8.98438 11.0566 10.5566 9.48438 12.5 9.48438C14.4434 9.48438 16.0156 11.0566 16.0156 13ZM21.0938 9.48438C19.1504 9.48438 17.5781 11.0566 17.5781 13C17.5781 14.9434 19.1504 16.5156 21.0938 16.5156C23.0371 16.5156 24.6094 14.9434 24.6094 13C24.6094 11.0566 23.0371 9.48438 21.0938 9.48438ZM3.90625 9.48438C1.96289 9.48438 0.390625 11.0566 0.390625 13C0.390625 14.9434 1.96289 16.5156 3.90625 16.5156C5.84961 16.5156 7.42188 14.9434 7.42188 13C7.42188 11.0566 5.84961 9.48438 3.90625 9.48438Z"
                                                fill="#1D4C77" />
                                        </svg>
                                    </div>

                                    <div class="milieuAvis">

                                        <div class="noteAvisLog">
                                            <?php
                                            for ($i = 0; $i < $unAvis["note_avis"]; $i++) {

                                                ?>

                                                    <img src="asset/icons/bleu/star.svg" alt="Icone Etoile">



                                                <?php
                                            }
                                            ?>
                                        </div>

                                        <?php
                                            $dateAff = explode(" ", $unAvis["date_avis"]);
                                            $dateAff2 = explode('-', $dateAff[0]);
                                            $heureAff = explode(":",$dateAff[1]);
                                        ?>

                                        <p>Posté le <?php echo($dateAff2[2])?>/<?php echo($dateAff2[1])?>/<?php echo($dateAff2[0])?> à <?php echo($heureAff[0]);?>:<?php echo($heureAff[1]);?></p>
                                    </div>

                                    <div class="ContentAvis">
                                        <p>
                                            <?php echo ($unAvis["contenu"]); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <?php if ($nbAvis<$rrrrrrrrrrrowCountttt) {
                                    ?>
                                    <div class="separateur1">a</div>
                                    <?php
                                }
                            }
                            
        
            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }

            $dbh = null;

                    ?>

            </div>
        </div>
    </main>    
    
    <script src="asset/js/header.js"></script>
    <script src="asset/js/descriptionCompte.js"></script>


</body>
</html>