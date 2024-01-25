<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/pageProprio.css">

    <title>Document</title>
</head>
<body>

<header>
    <a href="index.php">
      <img src="asset/img/logo.png" alt="logo">
    </a>
    <div></div>
    <div id="headerEmptyDiv"></div>
    <nav>
      <div>
        <svg width="30" height="30" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M23.7497 10.7258C22.7264 4.4244 20.3126 0 17.5035 0C14.6945 0 12.2807 4.4244 11.2573 10.7258H23.7497ZM10.728 17.5C10.728 19.0665 10.8127 20.5696 10.9609 22.0161H24.0391C24.1873 20.5696 24.272 19.0665 24.272 17.5C24.272 15.9335 24.1873 14.4304 24.0391 12.9839H10.9609C10.8127 14.4304 10.728 15.9335 10.728 17.5ZM33.6449 10.7258C31.6263 5.93448 27.5398 2.22984 22.4934 0.733871C24.2156 3.11895 25.4013 6.71069 26.0224 10.7258H33.6449ZM12.5066 0.733871C7.46723 2.22984 3.37366 5.93448 1.36217 10.7258H8.98467C9.59871 6.71069 10.7844 3.11895 12.5066 0.733871ZM34.4001 12.9839H26.3047C26.4529 14.4657 26.5376 15.9829 26.5376 17.5C26.5376 19.0171 26.4529 20.5343 26.3047 22.0161H34.393C34.7812 20.5696 35 19.0665 35 17.5C35 15.9335 34.7812 14.4304 34.4001 12.9839ZM8.46945 17.5C8.46945 15.9829 8.55414 14.4657 8.70236 12.9839H0.606977C0.225852 14.4304 0 15.9335 0 17.5C0 19.0665 0.225852 20.5696 0.606977 22.0161H8.6953C8.55414 20.5343 8.46945 19.0171 8.46945 17.5ZM11.2573 24.2742C12.2807 30.5756 14.6945 35 17.5035 35C20.3126 35 22.7264 30.5756 23.7497 24.2742H11.2573ZM22.5005 34.2661C27.5398 32.7702 31.6334 29.0655 33.6519 24.2742H26.0294C25.4083 28.2893 24.2226 31.881 22.5005 34.2661ZM1.36217 24.2742C3.38072 29.0655 7.46723 32.7702 12.5136 34.2661C10.7915 31.881 9.60577 28.2893 8.98467 24.2742H1.36217Z" fill="#F5F5F5"/>
        </svg>
        <svg id="headerArrowLang" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.99141 13.4874C9.54926 14.1709 10.4552 14.1709 11.0131 13.4874L19.5816 2.98945C20.1395 2.30599 20.1395 1.19605 19.5816 0.512594C19.0238 -0.170866 18.1178 -0.170866 17.56 0.512594L10 9.77485L2.44003 0.518062C1.88218 -0.165399 0.976236 -0.165399 0.418387 0.518062C-0.139462 1.20152 -0.139462 2.31146 0.418387 2.99492L8.98695 13.4929L8.99141 13.4874Z" fill="#F5F5F5"/>
        </svg>
      </div>
      <h4><a href="">Messagerie</a></h4>

      <h4><a href="compte.php?res=res"><?php if ($_SESSION["userType"]=="proprietaire"){echo("Mes logements");} else {echo("Mes réservations");} ?></a></h4>
      <h4><a href="compte.php">Mon compte</a></h4>
    </nav>
    <div id="headerPopup">
      <ul>
        <li>français</li>
        <li>english</li>
        <li>español</li>
        <li>deutsch</li>
        <li>brezhonneg</li>
      </ul>
    </div>
    <div></div>
  </header>
  
  <main id="ensemble">

    
  <?php

    $id = $_GET["id"];
    $id_log = $_GET["id_log"];
    include('connect_params.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.proprietaire NATURAL JOIN test.compte NATURAL JOIN test.telephone WHERE id_compte = :id_compte";
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
        <img src="asset/icons/bleu/toBack.svg" alt="" id="pagePersoSvgBack">
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
                    <h2><?php echo $current['nom_affichage'] ?></h2>
                    <?php
                        $note = $current['note_proprio'];
                        if (isset($note)) {
                            ?>
                            <figure class="star">
                                <img src="asset/icons/bleu/star.svg" alt="">
                                <figcaption><?php echo htmlentities($note) ?></figcaption>
                            </figure>
                            <?php
                        }
                    ?>
                    
                        <figure class="tel">
                        <img src="asset/icons/bleu/tel.svg" alt="">
                        <figcaption><?php echo wordwrap($current["numero"], 2, " ", 1); ?></figcaption>
                        </figure>
                        <?php
                
                    ?>
                    <figure class="mail">
                        <img src="asset/icons/bleu/mail.svg" alt="">
                        <figcaption><?php echo $current['adresse_mail'] ?></figcaption>
                    </figure>
                </div>

            </div>
                <div class="separateur">
                </div>

                <div id="aProposDeMoi">
                    <div>
                        <h2>À propos de moi</h2>
                    </div>
                    <div class="descriptionPersonne">
                            <input type="submit" value="Enregistrer" id="modificationDescription" class="modifBtn">
                            <p id="champsDescription" class="descriptionCompte"><?php echo htmlentities($current["description"]) ?></p>
                        </form>
                    </div>

                </div>
        
        
        </div>
        <div id="logementPropo">
            <h2 id="titreLogement">Logements Proposé</h2>
            <div id="listeLogements">



            <?php

            try {
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $query = "SELECT COUNT(*) FROM test.logement WHERE id_compte = $id;";
                $stmt = $dbh->prepare($query);
                $stmt->execute();
                $nbLogements = $stmt->fetch();

                if ($nbLogements['count'] == 0) {
                    ?>
                    <p id="AucunLogement">Vous n'avez aucun logement en ligne</p>
                    <?php
                }
                

                foreach($dbh->query("SELECT * FROM test.logement WHERE id_compte = $id", PDO::FETCH_ASSOC) as $row) {
            
                    $info=$row;
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $query = "SELECT min(id_image) FROM test.photo_logement NATURAL JOIN test.image WHERE id_logement = :id_logement;";
            
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam('id_logement', $info["id_logement"], PDO::PARAM_STR);
                    $stmt->execute();
                    $photo = $stmt->fetch();

                    $query = "SELECT extension_image FROM test.image WHERE id_image = :id_image;";
            
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam('id_image', $photo["min"], PDO::PARAM_STR);
                    $stmt->execute();
                    $extention = $stmt->fetch();
                    ?>

                    <div class="listeUnLogement">
                        <div>
                            <a  href="logement.php?id='<?php echo($info["id_logement"]) ?>'">
                                <img class="imgPageProprioLog" src="asset/img/logements/<?php echo($photo["min"]); ?>.<?php echo $extention["extension_image"] ?>" width="300px" height="100%" alt="">
                            </a>
                        </div>
                        
                        <div class="unLogement">
                            <h2><?php echo($info["nature_logement"]); ?> <?php echo($info["type_logement"]); ?>, <?php echo($info["localisation"]); ?></h2>
                            <p><?php echo($info["code_postal"]); ?>, <U><?php echo($info["departement"]); ?></U></p>
                            <div class="noteAvis">
                                <img src="asset/icons/bleu/star.svg" alt="">
                                <p><?php echo($info["note_logement"]); ?>, 24 avis</p>
                            </div>
                            <a class="consulterLogement" href="logement.php?id=<?php echo $info["id_logement"] ?>"><em>Consulter le logement</em></a>
                        </div>
                        
                    </div>

                    <div class="separateur1">a</div>

                    <?php
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