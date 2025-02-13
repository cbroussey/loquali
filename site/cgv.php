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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/droit.css">
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


    <div id="cgv">
        <h2>Conditions Générales de Vente</h2>
        <h4>Nous ne vendons pas de Produits Hôteliers ni d’autres Produits de voyage</h4>
        <p>LoQuali agit en tant que moteur de recherche pour la comparaison des offres de logements et d'expériences de voyage. Nous tenons à préciser que LoQuali n'est ni le fournisseur, ni le propriétaire, ni le contrôleur des logements ou des services de voyage répertoriés sur notre plateforme (les "Produits Hôteliers" et les "Produits de voyage"). Ces produits appartiennent, sont contrôlés ou mis à disposition par des tiers, que nous appelons "Prestataires". Les Prestataires sont soit directement des fournisseurs (par exemple, un hôtel), soit des facilitateurs (par exemple, une agence de voyage en ligne).</p>
        <p>La réservation d'un Produit Hôtelier ou de voyage se fait exclusivement auprès du Prestataire concerné. Vous devez accepter et comprendre les conditions, politiques de confidentialité et tout autre terme spécifique du Prestataire. Si les conditions de l'hébergement ou des services diffèrent de celles du Prestataire, celles du Prestataire prévalent, et vous devez également les accepter et les comprendre.</p>
        <p>Les informations, y compris les prix, les images et autres données, sont fournies par les Prestataires et d'autres sources. LoQuali n'est pas responsable de l'exactitude, de la qualité, de la fiabilité, de l'exhaustivité, de la mise à jour en temps réel ou de l'honnêteté de ces informations (le "Contenu"). Nous n'avons aucune influence sur le Contenu et ne garantissons pas que les informations, notamment les prix, sont toujours à jour ou disponibles en temps réel.</p>
        <p>Les notations et évaluations affichées sur notre plateforme sont des indications générales et ne sont pas garanties quant à leur exactitude. Nous ne vérifions pas ces informations et ne pouvons pas garantir la validité du Contenu d'Utilisateur.</p>
        <p>Votre interaction avec les Prestataires est à vos propres risques, et LoQuali n'assume aucune responsabilité en cas de problème lors de votre réservation. Nous vous encourageons à lire nos FAQ pour comprendre le fonctionnement de LoQuali, y compris les modalités de référencement, de déréférencement et de classement des offres en ligne, disponibles sur notre site.</p>
        <p>LoQuali se réserve le droit de modifier ces conditions à tout moment, et les utilisateurs sont invités à consulter régulièrement cette page pour rester informés des éventuelles mises à jour. </p>
    </div>
    
    <footer>

    <footer>

<div id="infosFooter">
  <div id="footerCercleLogo">
    <img src="asset/img/logoRond.svg" alt="logo rond Loquali">
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
      <a href=""><img src="asset/icons/blanc/facebook.svg" alt="Logo Facebook"></a>
            <a href=""><img src="asset/icons/blanc/instagram.svg" alt="Logo Instagram"></a>
            <a href=""><img src="asset/icons/blanc/steam.svg" alt="Logo de la graisse capilaire Swag (vive faute orthodraphe)"></a>
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
</body>
</html>