<?php
session_start();
//suppression de session si la popupDéco est validée
if (isset($_POST['hidden'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}
include('connect_params.php');
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$query = $dbh->prepare("SELECT * FROM test.compte WHERE id_compte = :idcompte");
$query->bindParam('idcompte', $_GET["confirmDelete"], PDO::PARAM_INT);
$query->execute();
$query = $query->fetchAll();
if (isset($_GET["confirmDelete"])) {
  try {
    $query = "DELETE FROM test.compte WHERE test.compte.id_compte = :id_compte";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam('id_compte', $_GET["confirmDelete"], PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
  }
  session_destroy();
  header("Location: index.php");
  exit();
}
?>



<?php

  include('connect_params.php');
  $id = $_SESSION['userId'];

  try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    if (isset($_POST['prenom'])) {
      $query = "UPDATE test.compte SET prenom=:newValue WHERE id_compte = :id_compte;";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('newValue', $_POST['prenom'], PDO::PARAM_STR);

      $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
      $stmt->execute();
      $infos2 = $stmt->fetch();
      $_SESSION['prenom'] = $_POST['prenom'];
    }

    if (isset($_POST['nom'])) {
      $query = "UPDATE test.compte SET nom=:newValue WHERE id_compte = :id_compte;";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('newValue', $_POST['nom'], PDO::PARAM_STR);

      $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
      $stmt->execute();
      $infos2 = $stmt->fetch();
      $_SESSION['nom'] = $_POST['nom'];
    }

    if (isset($_POST['adresse_mail'])) {
      $query = "UPDATE test.compte SET adresse_mail=:newValue WHERE id_compte = :id_compte;";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('newValue', $_POST['adresse_mail'], PDO::PARAM_STR);

      $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
      $stmt->execute();
      $infos2 = $stmt->fetch();
      $_SESSION['adresse_mail'] = $_POST['adresse_mail'];
    }




    if (isset($_POST['numero'])) {


      $query = "SELECT * FROM test.telephone WHERE id_compte = :id_compte";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('id_compte', $_SESSION['userId'], PDO::PARAM_STR);
      $stmt->execute();
      $telephone = $stmt->fetch();
      $rowCount = $stmt->rowCount();

      if ($rowCount == 0) {



        $stmt = $dbh->prepare("
            INSERT INTO test.telephone (
              numero,
              id_compte
            ) VALUES (
              :numero,
              :id_compte
            )");

        $stmt->bindParam(':numero', $_POST["numero"]);
        $stmt->bindParam(':id_compte', $id);


        try {
          // Exécuter la requête
          $stmt->execute();
        } catch (PDOException $e) {
          // Afficher l'erreur en cas d'échec de la requête
          echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
      } else {



        $query = "UPDATE test.telephone SET numero=:newValue WHERE id_compte = :id_compte;";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('newValue', $_POST['numero'], PDO::PARAM_STR);
        $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
        $stmt->execute();
        $tel1 = $stmt->fetch();
        $_SESSION['numero'] = $_POST['numero'];
      }
    }

    if (isset($_POST['adresse'])) {
      $query = "UPDATE test.compte SET adresse=:newValue WHERE id_compte = :id_compte;";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('newValue', $_POST['adresse'], PDO::PARAM_STR);

      $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
      $stmt->execute();
      $tel2 = $stmt->fetch();
      $_SESSION['adresse'] = $_POST['adresse'];
    }


    if ($_SESSION['userType'] == 'client') {
      $query = "SELECT * FROM test.compte WHERE id_compte = :id_compte";
    } else {
      $query = "SELECT * FROM test.proprietaire NATURAL JOIN test.compte WHERE id_compte = :id_compte";
    }

    $stmt = $dbh->prepare($query);
    $stmt->bindParam('id_compte', $_SESSION['userId'], PDO::PARAM_STR);
    $stmt->execute();
    $infos = $stmt->fetch();
    $query = "SELECT * FROM test.telephone WHERE id_compte = :id_compte";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam('id_compte', $_SESSION['userId'], PDO::PARAM_STR);
    $stmt->execute();
    $telephone = $stmt->fetch();
  } catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
  }

  ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/headerAndFooter.css">
  <link rel="stylesheet" href="asset/css/style.css">
  <script src="asset/js/boutonSupprimer.js"></script>
  <title>Comptes - Infos personnelles</title>
</head>

<body>
  <header id=headerCompte>
    <a href="index.php">
      <img src="asset/img/logo.png" alt="logo">
    </a>

      <?php
        if ($_SESSION['userType'] === 'proprietaire') {
      ?>
        <p class="personneheader"><span class="nompersonne"> <?php echo ($infos['nom']) ?> <?php echo ($infos['prenom']) ?> &#160;&#160;&#160;&#160; ● </span><span class="personne">propriétaire</span></p>
      
        <style>

          body {
            background-color: #C6D1DA;
          }
          
          .displayInfos, .displayInfos2, .displayInfos3, .displayInfos4, .displayInfos5
            {
            color: white;
          }

          #AucunLogementCompte {
            color: white;
            text-shadow: -1px -1px 1px rgba(255, 255, 255, 0.2), 1px 1px 1px rgba(0, 0, 0, 0.6);
          }

          #bonjour, #textchange, #compteInfosPerso, #compteConnection
           {
            color: #1D4C77;
          }

          header > div:first-of-type {
          width: 25%;
          }


        </style>
      
      <?php
      }
      ?>

      <?php
        if ($_SESSION['userType'] === 'client') {
      ?>
        <p class="personneheader"><span class="nompersonne"> <?php echo ($infos['nom']) ?> <?php echo ($infos['prenom']) ?> &#160;&#160;&#160;&#160; ● </span><span class="personne">voyageur</span></p>
      
        <style>
        header > div:first-of-type {
        width: 25%;
        }
        </style>
      <?php
      }
      ?>
   
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
      <h4><a id="accountDisconnect">Se déconnecter</a></h4>
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

  <?php
     foreach ($_FILES["photo"]["error"] as $key => $error) {

      if (isset($_FILES["photo"]["tmp_name"])){



        $repertoireImages = 'asset/img/profils/';
        $userId = $_SESSION['userId'];
        
        if ($images = opendir($repertoireImages)) {
            while (false !== ($fichier = readdir($images))) {
                $imgInfos = pathinfo($fichier);
                if ($imgInfos['filename'] == $userId) {
                    $chemin = $repertoireImages . $fichier;
        
                    if (unlink($chemin)) {

                    }
                }
            }
            closedir($images);
        }



        $img_dir = "asset/img/profils";
        $tmpName = $_FILES["photo"]["tmp_name"][$key];

        $nom_photo = $_FILES["photo"]["name"][$key];
        $extention=explode(".",$nom_photo);

        
        $chemin = $img_dir . "/" . $_SESSION['userId'].".".$extention[1];


        move_uploaded_file($tmpName, $chemin);
      }
    }
  ?> 

  <div id="compteContainer">
    <div class="nav">

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/accountBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/account.svg" alt="Infos Persos" class="img-front">
          </div>
          <figcaption>Accueil</figcaption>
        </figure>
      </div>


      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/personalInfosBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/personalInfos.svg" alt="Infos Persos" class="img-front">
          </div>
          <figcaption>Informations personnelles</figcaption>
        </figure>
      </div>


      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/connexionBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/connexion.svg" alt="Infos Persos" class="img-front">
          </div>
          <figcaption>Connexion et sécurité</figcaption>
        </figure>
      </div>

      <?php
      if ($_SESSION['userType'] === 'proprietaire') {
      ?>

        <div class="nav-item" data-color="account">
          <figure>
            <div class="img-area">
              <img src="asset/icons/bleu/logementBlue.svg" alt="Infos Persos" class="img-back">
              <img src="asset/icons/blanc/logement.svg" alt="Infos Persos" class="img-front">
            </div>
            <figcaption>Mes logements</figcaption>
          </figure>
        </div>

      <?php
      }
      ?>



      <?php
        if ($_SESSION['userType'] === 'client') {
      ?>

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/reservationsBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/reservations.svg" alt="Infos Persos" class="img-front">
          </div>

          <figcaption>Mes réservations</figcaption>
        </figure>
      </div>

      <?php
      }
      ?>

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/messageBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/message.svg" alt="Infos Persos" class="img-front">
          </div>
          <figcaption>Devis</figcaption>
        </figure>
      </div>

      <?php
      if ($_SESSION['userType'] === 'client') {
      ?>
      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/paiementBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/paiement.svg" alt="Infos Persos" class="img-front">
          </div>
          <figcaption>Paiement</figcaption>
        </figure>
      </div>
      <?php
      }
      ?>


      <div id="compteAcc">
      <!-- Accueil -->
      </div>




    </div>
<!-- ACCUEIL -->


<div id="compteAccueil">

  <div class="accueil">
  <p id="bonjour">Bonjour <?php echo ($infos['nom']) ?> !</p>
    <div class="container">
    
        <label for="fileInput">
            <?php //récupération du nom de l'image (avec extension)
        
            if ($images = opendir('asset/img/profils/')) {
                while (false !== ($fichier = readdir($images))) {
                    $imgInfos = pathinfo($fichier);
                    if ($imgInfos['filename'] == $_SESSION['userId']) {
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
            <img src=<?php echo $pathName ?> alt="" id="photoProfil">
        </label>
        
        <input type="file" id="fileInput" style="display: none;" accept="image/jpeg, image/png" onchange="changeProfilePhoto(event)">
        <form method="post" enctype="multipart/form-data" id="profileForm">

          <div class="middle">

            <input type="file" id="profilImage" name="profilImage" accept="image/*" style="color:transparent;" onchange="submitForm()"/>

            <label for="photo" id="custom-button-pp" aria-placeholder="">                <img src="asset/icons/blanc/photo.svg" alt="">
</label>

            <input type="file" id="photo" name="photo[]" multiple/>

          </div>
        </form>
    </div>
    <p id="textchange">changer votre photo de profil</p>
    

    <div id="caseAccueil">
      <p class="bienvenue">Accédez à votre <a href="pagePersoProprio.php" class="lienPagePerso">page personnel</a>.</p>
      <?php
                if ($_SESSION['userType'] === 'proprietaire') {
                ?>
                    <div class="separateurCompte"></div>
                    <a href="newLogement.php" id="comptePro">Créer une annonce</a>
                <?php
                }
            ?>
    </div>

</div>
</div>

<!--  INFORMATION  -->
    <div id="compteInfosPerso">
      <div class="lignes">
        <form method="post" action="compte.php">
          <p>Nom</p>
          <p id="nom" class="displayInfos"><?php echo ($infos['nom']) ?></p>
          <input type="text" name="nom" id="nom" class="modifInfos" cols="30" rows="10" value="<?php echo ($infos['nom']) ?>">
          <a href="#" id="boutonInfos" class="modificationsBtn boutonInfosstyle" alt="">Modifier</a>
          <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton">
        </form>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <form method="post" action="compte.php">
          <p>Prénom</p>
          <p id="prenom" class="displayInfos2"><?php echo ($infos['prenom']) ?></p>
          <input type="text" name="prenom" id="prenom" class="modifInfos2" cols="30" rows="10" value="<?php echo ($infos['prenom']) ?>">
          <a href="#" id="boutonInfos" class="modificationsBtn2 boutonInfosstyle" alt="">Modifier</a>
          <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton2">
        </form>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <form method="post" action="compte.php">
          <p>Adresse e-mail</p>
          <p id="adresse_mail" class="displayInfos3"><?php echo ($infos['adresse_mail']) ?></p>
          <input type="text" name="adresse_mail" id="adresse_mail" class="modifInfos3" cols="30" rows="10" value="<?php echo ($infos['adresse_mail']) ?>">
          <a href="#" id="boutonInfos" class="modificationsBtn3 boutonInfosstyle" alt="">Modifier</a>
          <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton3">
        </form>
      </div>
      <div class="separateurgenre"></div>

      <div class="lignes">
        <form method="post" action="compte.php">
          <p>Numéros de téléphone</p>
          <?php
          $tel = isset($telephone['numero']) ? $telephone["numero"] :  'Information non renseignée';
          ?>
          <p id="numero" class="displayInfos4"><?php echo htmlentities($tel) ?></p>
          <input type="text" name="numero" id="numero" class="modifInfos4" cols="30" rows="10" value="<?php echo ($telephone['numero']) ?>">
          <a href="#" id="boutonInfos" class="modificationsBtn4 boutonInfosstyle" alt="">Modifier</a>
          <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton4">
        </form>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <form method="post" action="compte.php">
          <p>Adresse</p>
          <?php
          $adresse = isset($infos['adresse']) ? $infos["adresse"] :  'Information non renseignée';
          ?>
          <p id="adresse" class="displayInfos5"><?php echo htmlentities($adresse) ?></p>
          <input type="text" name="adressePersonne" id="adressePersonne" class="modifInfos5" cols="30" rows="10" value="<?php echo ($infos['adresse']) ?>">
          <a href="#" id="boutonInfos" class="modificationsBtn5 boutonInfosstyle" alt="">Modifier</a>
          <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton5">
        </form>
      </div>
    </div>

<!--  CONNEXION  -->
    <div id="compteConnection">
      <div class="lignes">
        <p>Mot de passe</p>
        <button class="modifications" id="modifmaj">Mettre à jour</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Historique de l’appareil</p>
        <p class="displayInfos">Session en cours</p>
        <button id="accountDisconnect2" class="modifications">Se déconnecter</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Compte</p>
        <p class="displayInfos">Désactivez votre compte</p>
        <button class="modifications" onclick="openModal()">Désactiver</button>
        <div class="confirmation-modal" id="myModal">
          <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Êtes-vous sûr de vouloir supprimer ce compte ?</p>
            <form method="GET" action="compte.php">
              <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
              <button class="confirm-button">Confirmer</button>
              <?php
              ?>
            </form>
          </div>
        </div>
      </div>

    </div>

  </div> 

  <div id="compteLogements">
<!-- logements --> <!-- reservations -->
      <?php
        if ($_SESSION['userType'] == 'proprietaire') {
        ?>
        <div id="compteLogementPropo">

            <div class="compteAjout_log">
              <a href="newLogement.php">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M23.7768 9.92104H15.7411V1.88532C15.7411 0.899275 14.9414 0.0996094 13.9554 0.0996094H12.1696C11.1836 0.0996094 10.3839 0.899275 10.3839 1.88532V9.92104H2.34821C1.36217 9.92104 0.5625 10.7207 0.5625 11.7068V13.4925C0.5625 14.4785 1.36217 15.2782 2.34821 15.2782H10.3839V23.3139C10.3839 24.2999 11.1836 25.0996 12.1696 25.0996H13.9554C14.9414 25.0996 15.7411 24.2999 15.7411 23.3139V15.2782H23.7768C24.7628 15.2782 25.5625 14.4785 25.5625 13.4925V11.7068C25.5625 10.7207 24.7628 9.92104 23.7768 9.92104Z" fill="#F5F5F5" />
                </svg>
                <p>Créer une annonce</p>
              </a>
            </div>

          <div id="compteListeLogements">
            <?php

            try {
              $id = $_SESSION['userId'];
              $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

              $query = "SELECT COUNT(*) FROM test.logement WHERE id_compte = $id;";
              $stmt = $dbh->prepare($query);
              $stmt->execute();
              $nbLogements = $stmt->fetch();

              if ($nbLogements['count'] == 0) {
            ?>
                <p id="AucunLogementCompte">Vous n'avez aucun logement en ligne</p>
              <?php
              }


              foreach ($dbh->query("SELECT * FROM test.logement WHERE id_compte = $id", PDO::FETCH_ASSOC) as $row) {

                $info = $row;
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

                <div class="compteListeUnLogement">

                  <div class="toutLogement">


                    <div>
                      <img src="asset/img/logements/<?php echo ($photo["min"]); ?>.<?php echo $extention["extension_image"] ?>" width="100%" height="100%" alt="" class="imgListeLogementProprio">
                    </div>

                    <div class="unLogement">
                      <div class="log_info_liste">
                        <h2><?php echo ($info["libelle_logement"]);?>, <?php echo ($info["localisation"]); ?></h2>
                        <p class="logement_prix"><?php echo ($info["prix_ttc"]); ?> €, par nuit</p>
                        
                        <a class="consulterLogement" href="logement.php?id=<?php echo $info["id_logement"] ?>"><em>Consulter le logement</em></a>
                      </div>

                    </div>

                  </div>

                  <div class="compteBtnListeLogement">
                    <a href="modifLogement.php?id=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/modification.svg" alt=""></a>


                      <a onclick="openModal3()"><img src="asset/icons/bleu/trash.svg" alt=""></a>

                      <div class="confirmation-modal" id="myModal3">
                          <div class="modal-content">
                              <span class="close" onclick="closeModal3()">&times;</span>
                              <p>Êtes-vous sûr de vouloir supprimer ?</p>
                                <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">

                                <a  href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>" class="confirm-button">Confirmer</a>

                          </div>
                      </div>
                    <a href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/troisPoints.svg" alt=""></a>

                  </div>


                </div>

                <div class="compteSeparateur1">a</div>

            <?php
              }
            } catch (PDOException $e) {
              print "Erreur !: " . $e->getMessage() . "<br/>";
              die();
            }

        }else {

          try {
            $id = $_SESSION['userId'];
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $query = "SELECT COUNT(*) FROM test.reservation WHERE id_compte = $id;";
                $stmt = $dbh->prepare($query);
                $stmt->execute();
                $nbLogements = $stmt->fetch();

                if ($nbLogements['count'] == 0) {
                    ?>
                    <p id="AucuneReservCompte">Vous n'avez aucunes réservations pour le moment</p>
                    <?php
                }

                foreach($dbh->query("SELECT * FROM test.reservation 
                INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation
                INNER JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
                WHERE test.reservation.id_compte = $id;", PDO::FETCH_ASSOC) as $row) {
            
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

                  <div class="compteListeUnLogement">
                    <div class="toutLogement">
                      <div id=imajedelespagna>
                        <img src="asset/img/logements/<?php echo ($photo["min"]); ?>.<?php echo $extention["extension_image"] ?>" width="100%" height="100%" alt="" class="imgListeLogementProprio">
                      </div>
                      <div class="unLogement">
                        <div class="log_info_liste">
                          <h2><?php echo ($info["nature_logement"]); ?> <?php echo ($info["type_logement"]); ?>, <?php echo ($info["localisation"]); ?></h2>
                          <p><?php echo ($info["prix_devis"]); ?> €, par nuit</p>
                          <div class="noteAvis">
                            <p>

                                  <?php
                                  $datedeb =$info["debut_reservation"];
                                  $datefin =$info["fin_reservation"];

                                  // Convertir la chaîne en objet de date
                                  $dateObjdeb = new DateTime($datedeb);
                                  $dateObjfin = new DateTime($datefin);

                                  // Formater la date selon le format souhaité
                                  $result1 = $dateObjdeb->format('d M');
                                  $result2 = $dateObjfin->format('d M');

                                  // Afficher le résultat
                                  echo "$result1 -> $result2";
                                  ?>

                            </p>
                          </div>
                          <a class="consulterLogement" href="logement.php?id=<?php echo $info["id_logement"] ?>"><em>Consulter le logement</em></a>
                        </div>
                      </div>
                    </div>
                    <div class="compteBtnListeLogement">
                    <a href="modifLogement.php?id=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/modification.svg" alt=""></a>

                    <a onclick="openModal2()"><img src="asset/icons/bleu/trash.svg" alt=""></a>




                      <div class="confirmation-modal" id="myModal2">
                          <div class="modal-content">
                              <span class="close" onclick="closeModal2()">&times;</span>
                              <p>Êtes-vous sûr de vouloir supprimer ?</p>
                                <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">

                                <a  href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>" class="confirm-button">Confirmer</a>

                          </div>
                      </div>


                  </div>
                  </div>

                <div class="compteSeparateur1">a</div>

                

                <?php
                }
                
          } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
          }

        }
            ?>

        </div>
        </div>
      </div>

        <div id="compteReservations">
          <!-- Réservations -->
          <?php
          $devisCount = 0;

          if ($_SESSION['userType'] == 'client') {
            

            $id_client = $_SESSION['userId'];
            foreach($dbh->query("SELECT * FROM test.reservation 
                    INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation 
                    WHERE id_compte = $id_client", PDO::FETCH_ASSOC) as $row) {
                            $devisExist = true;
                            $id_logement = $row["id_logement"];
                            $id_reservation = $row["id_reservation"];


                            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                            
                            $proprio_id = $dbh->query("SELECT * from test.logement WHERE id_logement =$id_logement", PDO::FETCH_ASSOC)->fetch()["id_compte"];

                            $query = "SELECT * FROM test.compte NATURAL JOIN test.proprietaire WHERE id_compte = :id_compte";
                            
                            $stmt = $dbh->prepare($query);
                            $stmt->bindParam('id_compte', $proprio_id, PDO::PARAM_STR);
                            $stmt->execute();
                            $proprio = $stmt->fetch();

                            if ($images = opendir('asset/img/profils/')) {
                                while (false !== ($fichier = readdir($images))) {
                                    $imgInfos = pathinfo($fichier);
                                    if ($imgInfos['filename'] == $proprio_id) {
                                        $pathName = 'asset/img/profils/' . $fichier;
                                        break;
                                    }
            
                                }
                                if ($pathName == '') {
                                    $pathName = 'asset/img/profils/default.jpg';
                                }
                                closedir($images);
                            }

                            
                          if (!empty($row["prix_devis"])){
                            $devisCount++;
                            
                            ?>

                            <div class="page_devis">
                            
                            <div class="liste_devis">
                                <form class="devis" method="POST" action="demandeDevis.php">
                                  <input type="hidden" name="qui" value="client">
                                  <input type="hidden" name="reservation" value="<?=$row["id_reservation"]?>">
                                  <input type="hidden" name="id" value="<?=$row["id_logement"]?>">
                                  <img src="<?=$pathName?>" alt="" class="logo">
                                  <div class="infos-devis">
                                    <div class="infos-header">
                                    <h3><?=$proprio["nom_affichage"]?></h3>
                                    <p class="date"><?=explode(" ",$row["date_devis"])[0]?></p>
                                    </div>
                                    <div class="infos-header">
                                    <p>Vous a envoyé un devis.</p>
                                    <button type="submit" class="voir-devis">Voir</button>
                                    </div>
                                  </div>
                                </form>
                                <div class="separateur1">a</div>
                              </div>

                            </div>
                           
                      <?php 
                        
                    }
                    
            }
          } else {
            $id_proprio = $_SESSION['userId'];
            foreach($dbh->query("SELECT * FROM test.reservation 
                            INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation 
                            INNER JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
                            WHERE test.logement.id_compte = $id_proprio", PDO::FETCH_ASSOC) as $row) {
                            $id_logement = $row["id_logement"];

                            $id_reservation = $row["id_reservation"];

                            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                            
                            $client_id = $dbh->query("SELECT * FROM test.reservation WHERE id_reservation = $id_reservation")->fetch()["id_compte"];

                            $query = "SELECT * FROM test.compte NATURAL JOIN test.client WHERE id_compte = :id_compte";
                            
                            $stmt = $dbh->prepare($query);
                            $stmt->bindParam('id_compte', $client_id, PDO::PARAM_STR);
                            $stmt->execute();
                            $client = $stmt->fetch();

                            if ($images = opendir('asset/img/profils/')) {
                                while (false !== ($fichier = readdir($images))) {
                                    $imgInfos = pathinfo($fichier);
                                    if ($imgInfos['filename'] == $client_id) {
                                        $pathName = 'asset/img/profils/' . $fichier;
                                        break;
                                    }
            
                                }
                                if ($pathName == '') {
                                    $pathName = 'asset/img/profils/default.jpg';
                                }
                                closedir($images);
                            }
                            $devisCount++;

                            ?>

                            <div class="page_devis">
                            
                            <div class="liste_devis">
                                <form class="devis" method="POST" action="demandeDevis.php">
                                  <input type="hidden" name="qui" value="proprietaire">
                                  <input type="hidden" name="reservation" value="<?=$row["id_reservation"]?>">
                                  <input type="hidden" name="id" value="<?=$row["id_logement"]?>">
                                  <img src="<?=$pathName?>" alt="" class="logo">
                                  <div class="infos-devis">
                                    <div class="infos-header">
                                    <h3><?=$client["nom_affichage"]?></h3>
                                    <p class="date"><?=explode(" ",$row["date_devis"])[0]?></p>
                                    </div>
                                    <div class="infos-header">
                                    <p>Vous a fait une demande de devis.</p>
                                    <button type="submit" class="voir-devis">Créer</button>
                                    </div>
                                  </div>
                                </form>
                                <div class="separateur1">a</div>
                              </div>

                            </div>
          <?php }
          }
          // Afficher le message s'il n'y a pas de devis
          if ($devisCount === 0) {
            ?>
            <p id="AucunDevisCompte">Vous n'avez aucuns devis pour le moment</p>
            <?php
          }
            ?>

        </div>

         
        <div id="compteMessagerie">
          <!-- paiement -->
          <div class="liste_carte">
          <?php 
          $id_client = $_SESSION['userId'];
          $cartes = $dbh->query("SELECT * FROM test.cb 
                                 INNER JOIN test.compte ON test.compte.id_compte = test.cb.id_compte 
                                 WHERE test.compte.id_compte = $id_client", PDO::FETCH_ASSOC)->fetchAll();
          if (count($cartes) > 0){
            foreach($cartes as $row){ ?>
              <form class="carte" method="POST" action="deleteCarte.php">
                <input type="hidden" name="nb_cb" value="<?=$row["numero_carte"]?>">
                <img src="./asset/img/mastercard.png" alt="logo mastercard" class="carte-logo">
                <div class="texte">
                  <h3>Mr. <?= $row["nom"].' '.$row["prenom"]?></h3>
                  <?php 
                    $numeroCarteFormate = preg_replace('/(\d{4})\d{8}(\d{3})/', '$1 **** **** $2', $row["numero_carte"]);                ?>
                  <p><?= $numeroCarteFormate?></p>
                </div>
                <input type="submit" value="Supprimer">
              </form>
              <div class="separateur3"></div>
             <?php }
          } else {?>
                <p id="AucuneCarte">Vous n'avez aucune carte enregistrée</p>
          <?php }?>
          </div>
        </div>

        <div id="comptePaiement">
          <!-- payement -->
        </div>
      </div>

        <div id="menu">
          <div id="choix">
            <a href="compteAccueil.php" class="bouton">Mon compte</a>

            <a href="" class="bouton">Mes réservation</a>

            <a href="" class="bouton">Messagerie</a>

            <div id="separe"></div>

            <p>Changer la langue</p>
            <div id="langues">
              <a href="">Français</a>
              <div id="separe2"></div>
              <a href="">Anglais</a>
            </div>
          </div>
        </div>


        <form method="post" id="popUpDeco">
        <div class="popUpDecoChoix">
          <h2>Êtes-vous sûr de vouloir <br>vous déconnecter ?</h2>
          <div class="button-container">
            <input class="cancel-button" id="cancelDisconnect" name="cancelDisconnect" type="button" value="Annuler" />
            <input type="hidden" name="hidden" value="disconnect">
            <input class="confirm-button" id="confirmDisconnect" type="submit" value="Se déconnecter" />
          </div>
        </div>
      </form>

        <script src="asset/js/header.js"></script>
        <script src="asset/js/modifInfosCompte.js"></script>
        <script src="asset/js/account.js"></script>
        <script src="asset/js/boutonSupprimer.js"></script>
        <?php if ($_GET["res"]=="res"){?>
            <script>liens_compte(3)</script>
          <?php } ?>
</body>

</html>

