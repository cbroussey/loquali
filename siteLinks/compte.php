<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_destroy();
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <title>Comptes - Infos personnelles</title>
</head>
<body>
  <header>
    <a href="">
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
      <svg id="headerHamburger" width="28" height="31" viewBox="0 0 28 31" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect y="0.738281" width="28" height="3.52174" rx="1" fill="#F5F5F5"/>
          <rect y="13.6523" width="28" height="3.52174" rx="1" fill="#F5F5F5"/>
          <rect y="26.5645" width="28" height="3.52174" rx="1" fill="#F5F5F5"/>
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

    include('connect_params.php');
    try {
        $id=5; // à revoir une fois que les comptes sont fait
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.proprietaire NATURAL JOIN test.compte WHERE id_compte = :id_compte";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
        $stmt->execute();
        $infos = $stmt->fetch();
    }   catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    try {
      $id=2; // à revoir une fois que les comptes sont fait

        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.telephone WHERE id_compte = :id_compte";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_compte', $id, PDO::PARAM_STR);
        $stmt->execute();
        $telephone = $stmt->fetch();
    }   catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
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

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/favoriteBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/favorite.svg" alt="Infos Persos" class="img-front">
          </div> 
          <figcaption>Favoris</figcaption>
        </figure>
      </div>

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
              <img src="asset/icons/bleu/logementBlue.svg" alt="Infos Persos" class="img-back">
              <img src="asset/icons/blanc/logement.svg" alt="Infos Persos" class="img-front">
          </div> 
          <figcaption>Mes logements</figcaption>
        </figure>
      </div>

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/reservationsBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/reservations.svg" alt="Infos Persos" class="img-front">
          </div> 
          <figcaption>Mes réservations</figcaption>
        </figure>
      </div>

      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/messageBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/message.svg" alt="Infos Persos" class="img-front">
          </div> 
          <figcaption>Messagerie</figcaption>
        </figure>
      </div>  
      
      <div class="nav-item" data-color="account">
        <figure>
          <div class="img-area">
            <img src="asset/icons/bleu/paiementBlue.svg" alt="Infos Persos" class="img-back">
            <img src="asset/icons/blanc/paiement.svg" alt="Infos Persos" class="img-front">
          </div> 
          <figcaption>Paiement</figcaption>
        </figure>
      </div>


    </div>
<!--  INFORMATION  -->
    <div id="compteInfosPerso">
      <div class="lignes">
        <p>Nom légal</p>
        <p class="displayInfos" id="nom"><?php echo($infos['nom']) ?> <?php echo($infos['prenom']) ?></p>
        <button class="modifications" onclick="modifierInfos(this, 'nom')">Modifier</button>
      </div>


      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Adresse e-mail</p>
        <p class="displayInfos" id="mail"><?php echo($infos['adresse_mail']) ?></p>
        <button class="modifications" onclick="modifierInfos(this, 'mail')">Modifier</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Numéros de téléphone</p>
        <p class="displayInfos" id="tel"><?php echo($telephone["numero"]); ?></p>
        <button class="modifications" onclick="modifierInfos(this, 'tel')">Modifier</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Adresse</p>
        <p class="displayInfos" id="adresse"><?php echo($infos['adresse_postale']) ?></p>
        <button class="modifications" onclick="modifierInfos(this, 'adresse')">Modifier</button>
      </div>

    </div>
<!--  CONNEXION  -->
    <div id="compteConnection">
      <div class="lignes">
        <p>Mot de passe</p>
        <p class="displayInfos">Mis à jour le 9 Septembre 2023</p>
        <button class="modifications">Mettre à jour</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Historique de l’appareil</p>
        <p class="displayInfos">Session en cours</p>
        <button class="modifications">Se déconnecter</button>
      </div>

      <div class="separateurgenre"></div>

      <div class="lignes">
        <p>Compte</p>
        <p class="displayInfos">Désativez votre compte</p>
        <button class="modifications"><span id="quit">Désactiver</span></button>
      </div>
    </div>

    <div id="compteFavoris">
      <!-- Favoris -->
    </div>

    <div id="compteLogements">
      <!-- Logements -->
    </div>

    <div id="compteReservations">
      <!-- Réservations -->
    </div>

    <div id="compteMessagerie">
      <!-- Messagerie -->
    </div>

    <div id="comptePaiement">
      <!-- payement -->
    </div>

  </div> 

  <form method="post" id="popUpDeco">
        <div class="popUpDecoChoix">
            <h2>Êtes-vous sûr de vouloir <br>vous déconnecter ?</h2>
            <div class="button-container">
                <input class="cancel-button" id="cancelDisconnect" type="button" value="Annuler"/>
                <input class="confirm-button" id="confirmDisconnect" type="submit" value="Se déconnecter"/>
            </div>
        </div>
  </form>

  <footer>

  <div id="infosFooter">
    <div id="footerCercleLogo" class="portableDroite">
        <img src="asset/img/logo.png" alt="logo">
    </div>
    <div id="textefooter">
        <div class="gauche" class="portableGauche" id="infosLegal">
            <h2>Informations légales</h2>
            <a href="">Plan du site</a>
            <a href="">Mentions légales</a>
            <a href="">Conditions générales de ventes</a>
            <a href="">Données personnelles</a>
            <a href="">Gestions des cookies</a>
        </div>
        <div class="centrer" class="portableDroite" id="support">
            <h2>Support client</h2>
            <a href="">Contacter le support</a>
        </div>
        <div class="centrer" class="portableDroite" id="reseaux">
            <h2>Suivez nous</h2>
            <div id="logoReseaux">
                <a href=""><img src="asset/icons/blanc/facebook.svg" alt=""></a>
                <a href=""><img src="asset/icons/blanc/instagram.svg" alt=""></a>
                <a href=""><img src="asset/icons/blanc/steam.svg" alt=""></a>
            </div>
        </div>
        <div class="droite" class="portableGauche" id="contact">
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
  
  <script src="asset/js/header.js"></script>
  <script src="asset/js/account.js"></script>
  <script src="asset/js/modifInfosCompte.js"></script>
  <script src="asset/js/couleurNavCompte.js"></script>

</body>
</html>