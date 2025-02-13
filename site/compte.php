<?php
session_start();
error_reporting(0);
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
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/headerAndFooter.css">
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="asset/css/compte.css">
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
      <p class="personneheader"><span class="nompersonne">
          <?php echo ($infos['nom']) ?>
          <?php echo ($infos['prenom']) ?> &#160;&#160;&#160;&#160; ●
        </span><span class="personne">propriétaire</span>
      </p>

      <style>
        body {
          background-color: #C6D1DA;
        }

        .displayInfos,
        .displayInfos2,
        .displayInfos3,
        .displayInfos4,
        .displayInfos5 {
          color: white;
        }

  
        #bonjour,
        #textchange,
        #compteInfosPerso,
        #compteConnection {
          color: #1D4C77;
        }

        header>div:first-of-type {
          width: 25%;
        }
      </style>

      <?php
    } else if ($_SESSION['userType'] === 'client') {
      ?>
        <p class="personneheader"><span class="nompersonne">
          <?php echo ($infos['nom']) ?>
          <?php echo ($infos['prenom']) ?> &#160;&#160;&#160;&#160; ●
          </span><span class="personne">voyageur</span></p>

        <style>
          header>div:first-of-type {
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
          <path
            d="M23.7497 10.7258C22.7264 4.4244 20.3126 0 17.5035 0C14.6945 0 12.2807 4.4244 11.2573 10.7258H23.7497ZM10.728 17.5C10.728 19.0665 10.8127 20.5696 10.9609 22.0161H24.0391C24.1873 20.5696 24.272 19.0665 24.272 17.5C24.272 15.9335 24.1873 14.4304 24.0391 12.9839H10.9609C10.8127 14.4304 10.728 15.9335 10.728 17.5ZM33.6449 10.7258C31.6263 5.93448 27.5398 2.22984 22.4934 0.733871C24.2156 3.11895 25.4013 6.71069 26.0224 10.7258H33.6449ZM12.5066 0.733871C7.46723 2.22984 3.37366 5.93448 1.36217 10.7258H8.98467C9.59871 6.71069 10.7844 3.11895 12.5066 0.733871ZM34.4001 12.9839H26.3047C26.4529 14.4657 26.5376 15.9829 26.5376 17.5C26.5376 19.0171 26.4529 20.5343 26.3047 22.0161H34.393C34.7812 20.5696 35 19.0665 35 17.5C35 15.9335 34.7812 14.4304 34.4001 12.9839ZM8.46945 17.5C8.46945 15.9829 8.55414 14.4657 8.70236 12.9839H0.606977C0.225852 14.4304 0 15.9335 0 17.5C0 19.0665 0.225852 20.5696 0.606977 22.0161H8.6953C8.55414 20.5343 8.46945 19.0171 8.46945 17.5ZM11.2573 24.2742C12.2807 30.5756 14.6945 35 17.5035 35C20.3126 35 22.7264 30.5756 23.7497 24.2742H11.2573ZM22.5005 34.2661C27.5398 32.7702 31.6334 29.0655 33.6519 24.2742H26.0294C25.4083 28.2893 24.2226 31.881 22.5005 34.2661ZM1.36217 24.2742C3.38072 29.0655 7.46723 32.7702 12.5136 34.2661C10.7915 31.881 9.60577 28.2893 8.98467 24.2742H1.36217Z"
            fill="#F5F5F5" />
        </svg>
        <svg id="headerArrowLang" width="20" height="14" viewBox="0 0 20 14" fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M8.99141 13.4874C9.54926 14.1709 10.4552 14.1709 11.0131 13.4874L19.5816 2.98945C20.1395 2.30599 20.1395 1.19605 19.5816 0.512594C19.0238 -0.170866 18.1178 -0.170866 17.56 0.512594L10 9.77485L2.44003 0.518062C1.88218 -0.165399 0.976236 -0.165399 0.418387 0.518062C-0.139462 1.20152 -0.139462 2.31146 0.418387 2.99492L8.98695 13.4929L8.99141 13.4874Z"
            fill="#F5F5F5" />
        </svg>
      </div>
      <svg id="headerHamburger" width="28" height="31" viewBox="0 0 28 31" fill="none"
        xmlns="http://www.w3.org/2000/svg">
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

    if (isset($_FILES["profilImage"]["tmp_name"])) {

      print_r($_FILES);

      $img_dir = "asset/img/profils";
      $tmpName = $_FILES["profilImage"]["tmp_name"];

      $nom_photo = $_FILES["profilImage"]["name"];
      $extension = pathinfo($nom_photo, PATHINFO_EXTENSION); 

      $chemin = $img_dir . "/" . $_SESSION['userId'] . "." . $extension;
      if ($images = opendir('asset/img/profils/')) {
        while (false !== ($fichier = readdir($images))) {
            $imgInfos = pathinfo($fichier);
            if ($imgInfos['filename'] == $_SESSION['userId']) {
                $cheminFichier = 'asset/img/profils/' . $fichier;
                unlink($cheminFichier);
                echo("| Suppression |");
            }
        }
        closedir($images);
    }
    

      if (move_uploaded_file($tmpName, $chemin)) {
        echo "File is valid, and was successfully uploaded.";
      } else {
        echo "Possible file upload attack!";
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
      } else if ($_SESSION['userType'] === 'client') {
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
      } else {  //cas d'erreur
        echo "Erreur : Type d'utilisateur incorrect ou absent.";
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
      } else if ($_SESSION['userType'] === 'proprietaire') {
        ?>

          <div class="nav-item" data-color="account">
            <figure>
              <div class="img-area">
                <img src="asset/icons/bleu/logementBlue.svg" alt="Infos Persos" class="img-back">
                <img src="asset/icons/blanc/logement.svg" alt="Infos Persos" class="img-front">
              </div>
              <figcaption>API</figcaption>
            </figure>
          </div>

        <?php
      } else {  //cas d'erreur
        echo "Erreur : Type d'utilisateur incorrect ou absent.";
      }
      ?>
    </div>

    <?php
    if (count($_POST)!=0 || count($_FILES)!=0){

      if (isset($_POST["nom"]) || isset($_POST["prenom"]) || isset($_POST["adresse_mail"]) || isset($_POST["numero"])|| isset($_POST["adressePersonne"])) {
        $index = 1;
      } else if (count($_FILES)!=0) {
        $index=0;
      } else {
        $index=3;
      }

      ?>
        <script type="text/javascript">
            window.location.href = "compte.php?ind=<?php echo($index) ?>";
        </script>
      <?php
    }
  ?>

    <?php
    // ACCUEIL
    include "./account/homePage.php";


    // INFORMATION
    include "./account/personalInformations.php";

    // CONNEXION
    include "./account/connexion.php";

    // LOGEMENTS / RESERVATIONS
    if ($_SESSION['userType'] == "client") {  //si l'utilisateur est client, on affiche ses réservations
      include "./account/reservations.php";
    } else if ($_SESSION['userType'] == "proprietaire") { //si l'utilisateur est propriétaire, on affiche ses logemements
      include "./account/logements.php";
    } else {  //cas d'erreur
      echo "Erreur : Type d'utilisateur incorrect ou absent.";
    }

    // DEVIS
    include "./account/devis.php";

    // PAIEMENT / API
    if ($_SESSION['userType'] == "client") { //si l'utilisateur est client, on affiche ses modes de paiement
      include "./account/paiement.php";
    } else if ($_SESSION['userType'] == "proprietaire") { //si l'utilisateur est propriétaire, on affiche l'API synchronisator
      include "./account/api.php";
    } else {  //cas d'erreur
      echo "Erreur : Type d'utilisateur incorrect ou absent.";
    }
    ?>

  </div>

  <div id="menu">
    <div id="choix">

      <a href="connexion.php" class="bouton">Se connecter</a>

      <div class="separateur"></div>

      <a href="compte.php" class="bouton">Mon compte</a>

      <a href="compte.php?res=res" class="bouton"><?php if ($_SESSION["userType"] == "proprietaire") {
                                                    echo ("Mes logements");
                                                  } else {
                                                    echo ("Mes réservations");
                                                  } ?></a>
      <div class="separateur"></div>
      <select id="sort">
        <option value="nom">Langues</option>
        <option value="type">Francais</option>
        <option value="niveau">Anglais</option>
        <option value="date">Espagnol</option>
        <option value="date">Espagnol</option>
      </select>
    </div>
    <a href="compte.php" id="retour">Retour</a>
  </div>


  <form method="post" id="popUpDeco">
    <div class="popUpDecoChoix">
      <h2>Êtes-vous sûr de vouloir <br>vous déconnecter ?</h2>
      <div class="button-container">
        <input class="cancel-button" id="cancelDisconnect" name="cancelDisconnect" type="button" value="Annuler" />
        <input type="hidden" name="hidden" value="disconnect">
        <input id="confirmChange" type="submit" value="Se déconnecter" />
      </div>
    </div>
  </form>



  

  <script src="asset/js/header.js"></script>
  <script src="asset/js/account.js"></script>
  <script src="asset/js/accountAccueil.js"></script>
  <script src="asset/js/boutonSupprimer.js"></script>
  <script src="asset/js/modifInfosCompte.js"></script>
  <script src="asset/js/rectifIfo.js"></script>

  <?php /* if ($_GET["res"] == "res") { ?>
<script>
 liens_compte(3)
</script>
<?php } */?>
</body>
<style>
  input[type="checkbox"] {
    display: inline-block;
  }
</style>

</html>