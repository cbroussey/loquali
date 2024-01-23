<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/droit.css">
    <title>Document</title>
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
        <h4><a href="">Messagerie</a></h4>
        <h4><a href="">Mes réservations</a></h4>
        <h4><a href="compte.php">Mon compte</a></h4>
      <?php } else {
      ?>
        <h4><a href="connexion.php">Se connecter</a></h4>
      <?php
      }
      ?>
    </nav>
    <div >
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

    <div id="cgu">
      <h2>Conditions Générales d'Utilisation</h2>
      <p class="ptittitre">1) Disposition générale</p>
      <p>
        Bienvenue sur le site internet www.site-sae-loquali.bigpapoo.com, fourni par LoQuali S.A.S. Les termes "nous", "notre", “LoQuali” désignent LoQuali S.A.S., une société par actions simplifiée enregistrée sous le numéro 123 456 789 au Registre du Commerce de Bretagne et dont le siège est situé Rue Édouard Branly, 22300 Lannion, France. <br>
        L'accès à notre Site Internet, à toute application mobile ou tablette de LoQuali, ou à toute autre fonctionnalité ou autre plateforme de LoQuali vous est offert sous réserve de votre acceptation sans modification de tous les termes, conditions et avis énoncés ci-dessous. <br>
        En utilisant notre site, vous reconnaissez avoir pris connaissance, compris et accepté les termes et conditions énoncés dans ce document. Si vous ne consentez pas à l'une de ces conditions, nous vous prions de ne pas utiliser notre site. <br>
        Sur LoQuali, découvrez, réservez et partagez des logements exceptionnels pour vos escapades. Ces conditions visent à promouvoir une communauté positive et respectueuse, tout en garantissant le respect des lois applicables. Notez que l'inscription peut être requise pour accéder à certaines fonctionnalités, et en vous inscrivant, vous adhérez également à ces conditions. 
      </p>
      <p class="ptittitre">2) Accès et utilisation de la plate-forme</p>
      <p>
        En explorant les fonctionnalités offertes par notre Plateforme, vous affirmez avoir pris connaissance, assimilé et consenti aux termes et conditions énoncés dans le présent document. Dans le cadre de notre engagement envers la transparence et la responsabilité, veuillez noter que certaines fonctionnalités peuvent exiger une inscription préalable. En vous inscrivant sur notre site, vous manifestez non seulement votre intérêt pour ces fonctionnalités, mais vous adhérez également de manière explicite et volontaire aux conditions définies ici.<br> 
        De plus la Plateforme est actuellement mise gratuitement à votre disposition pour votre usage personnel et non commercial. Nous ne garantissons pas que l'accès à la Plateforme, ou à tout contenu sur celle-ci, sera toujours disponible ou ininterrompu. Nous nous réservons le droit de suspendre, retirer, interrompre ou modifier tout ou partie de la Plateforme sans préavis.<br>
        Vous ne devez pas abuser de la Plateforme en y introduisant sciemment des éléments malveillants ou technologiquement dangereux. L'accès non autorisé à la Plateforme, toute tentative d'obtenir un tel accès ou toute attaque contre la Plateforme sont strictement interdits.<br>
        Cette démarche reflète votre engagement envers une utilisation responsable et respectueuse de notre plateforme, ainsi que votre volonté de participer activement à la création d'une communauté LoQuali positive et enrichissante.
      </p>
      <p class="ptittitre">3) Utilisation de la plate-forme.</p>
      <p>
        Découvrez l'étendue captivante de notre plateforme qui offre bien plus qu'une simple interface en ligne. Plongez dans une sélection méticuleusement élaborée de logements uniques, où chaque option, des appartements urbains chics aux retraites rurales tranquilles, a été soigneusement choisie pour créer des expériences mémorables.<br>
        Exprimez votre individualité en tant qu'hôte en partageant votre espace unique avec le monde. Que votre logement soit empreint de charme, d'histoire ou d'innovation, notre plateforme offre un espace propice à la narration, vous permettant de partager votre histoire personnelle et de créer des souvenirs inoubliables pour vos invités.<br>
        La réservation sur LoQuali devient une expérience fluide et intuitive grâce à notre interface conviviale. Nous avons simplifié le processus pour que vous puissiez vous concentrer pleinement sur l'anticipation de votre prochaine aventure. Parcourez les options, choisissez votre logement idéal, et effectuez votre réservation en quelques clics.<br>
        Explorez au-delà des logements en découvrant des expériences locales uniques. Que ce soit une leçon de cuisine locale, une visite guidée immersive ou une excursion hors des sentiers battus, notre plateforme vous ouvre les portes vers des aventures authentiques. Chez LoQuali, nous ne proposons pas simplement des hébergements ; nous créons des opportunités de vivre, de partager et d'explorer, enrichissant ainsi chaque aspect de votre voyage.
      </p>
      <p class="ptittitre">4) Obligations de l'Utilisateur et Activités Interdites </p>
      <p>
        En tant qu'utilisateur de la Plateforme, vous êtes responsable de tout Contenu Utilisateur que vous publiez sur LoQuali. Vous devez vous assurer que vous disposez des droits nécessaires sur tout contenu que vous publiez et qu'aucun Contenu Utilisateur ne viole les droits des tiers.<br>
        En utilisant notre Plateforme, vous déclarez :<br>
        - Avoir au moins 18 ans ;<br>
        - Être légalement capable de créer des obligations juridiques contraignantes ;<br>
        - Utiliser la Plateforme conformément aux présentes Conditions ;<br>
        - Utiliser la Plateforme uniquement à des fins privées de recherche d'offres légitimes ;<br>
        - Informer toute personne au nom de laquelle vous utilisez la Plateforme des Conditions qui s'appliquent ;<br>
        - Fournir des informations véridiques, exactes, à jour et complètes ;<br>
        - Protéger les informations de votre compte et être responsable de toute utilisation non autorisée.<br>
        Vous acceptez de ne pas créer et publier de Contenu Utilisateur qui :<br>
        - Est de la publicité déguisée en commentaire ;<br>
        - N'a pas de contenu spécifique pour un élément commenté ;<br>
        - N’est pas objectif ou intentionnellement inexact ;<br>
        - Est immoral, pornographique ou offensant de toute autre manière ;<br>
        - Viole les droits de tiers, en particulier les droits d'auteur ;<br>
        - Enfreint les lois applicables ou constitue une infraction pénale ;<br>
        - Contient des virus ou d'autres programmes informatiques malveillants ;<br>
        - Est une enquête ou une chaîne d'e-mails ;<br>
        - A pour but de collecter ou d'utiliser les données personnelles d'autres utilisateurs, notamment à des fins commerciales.<br>
        Vous acceptez également de ne pas :<br>
        - Utiliser notre Plateforme à des fins commerciales ;<br>
        - Faire des réservations spéculatives, fausses ou frauduleuses ;<br>
        - Accéder, contrôler ou copier tout contenu ou toute information de notre Plateforme sans autorisation écrite ;<br>
        - Violer les restrictions des fichiers d'exclusion de robots sur notre Plateforme ;<br>
        - Prendre toute mesure qui pourrait imposer une charge déraisonnable à notre infrastructure;<br>
        - Créer des liens hypertexte vers toute partie de notre Plateforme sans autorisation écrite.<br>
        En cas d'activité frauduleuse, LoQuali se réserve le droit de refuser ses services et de prendre des mesures légales appropriées.
      </p>
      <p class="ptittitre">5) Droit de propriété intellectuelle </p>
      <p>
        Dans le respect du droit de propriété intellectuelle, il est essentiel de souligner que l'intégralité du contenu présent sur notre site, qu'il s'agisse des descriptions, des images ou des logos, demeure la propriété exclusive de LoQuali. Cette propriété est rigoureusement protégée par les lois sur la propriété intellectuelle en vigueur.<br>
        Nous vous encourageons à apprécier le caractère unique de chaque élément de contenu que vous rencontrez sur notre plateforme, reconnaissant ainsi la créativité et l'effort investis pour vous offrir une expérience exceptionnelle. L'utilisation, la reproduction ou la distribution de ce contenu sans autorisation explicite de LoQuali est strictement interdite.<br>
        Votre accès à notre site et son contenu ne vous confère aucun droit de propriété sur les éléments que vous y trouvez. Nous vous prions de faire preuve de responsabilité dans l'utilisation de ce contenu et de respecter scrupuleusement les droits de propriété intellectuelle qui lui sont associés. Nous tenons à souligner que toute utilisation non autorisée du contenu de LoQuali pourrait entraîner des mesures légales afin de protéger nos droits et l'intégrité de notre plateforme.<br>
        Si vous avez des idées créatives ou des propositions de collaboration, nous vous encourageons à nous contacter. Nous sommes ouverts à des partenariats mutuellement bénéfiques tout en respectant la propriété intellectuelle. Si vous repérez une utilisation non autorisée de notre contenu, n'hésitez pas à nous le signaler. Nous prenons ces questions au sérieux et prendrons les mesures nécessaires pour protéger nos droits et l'intégrité de notre communauté. Chez LoQuali, nous croyons en la force de la créativité et de la collaboration, et nous vous remercions de contribuer à maintenir l'intégrité de notre plateforme.<br>
        En somme, nous sommes propriétaires de tous les droits de propriété intellectuelle sur notre Plateforme et son Contenu. Vous ne pouvez pas modifier, copier, distribuer, transmettre, afficher, reproduire, publier, concéder sous licence, créer des œuvres dérivées, transférer, vendre ou revendre toute information, logiciel, produit ou service obtenu par le biais de la Plateforme.<br>
        Vous acceptez de ne pas générer d'impressions de pages ou de contenus automatisés sur la Plateforme.
      </p>
      <p class="ptittitre">6) Confidentialité et cookies </p>
      <p>
        Votre confiance est notre priorité absolue. Les données personnelles que vous choisissez de partager avec nous sont collectées et utilisées de manière responsable, dans le seul but d'enrichir votre expérience sur LoQuali. Que ce soit lors de la création de votre compte, de la réservation d'un logement, ou de l'interaction avec d'autres membres de la communauté, notre <a href="">Politique de Confidentialité</a> garantit que chaque étape de collecte est clairement expliquée.<br>
        Nous mettons en œuvre des mesures de sécurité rigoureuses pour assurer la protection sans faille de vos informations personnelles contre tout accès non autorisé, utilisation abusive ou divulgation. Votre tranquillité d'esprit est au cœur de notre engagement envers vous, et nous travaillons sans relâche pour garantir que vos données restent confidentielles et sécurisées.<br>
        Chez LoQuali, nous croyons en l'importance de vous donner un contrôle total sur vos données. Notre <a href="">Politique de Confidentialité</a> vous informe sur la manière dont vous pouvez accéder, rectifier ou supprimer vos informations personnelles. Nous souhaitons que votre expérience sur notre plateforme soit non seulement enrichissante, mais également conforme à vos attentes en matière de confidentialité.<br>
        N'hésitez pas à consulter notre <a href="">Politique de Confidentialité</a> pour obtenir des détails spécifiques sur la manière dont nous gérons vos données personnelles. Si vous avez des questions ou des préoccupations, notre équipe est à votre disposition pour vous fournir les informations nécessaires et vous assurer une compréhension claire de nos pratiques en matière de confidentialité. Nous vous remercions de votre confiance continue et nous sommes là pour veiller à ce que votre expérience sur LoQuali reste sécurisée, transparente et conforme à vos attentes.
      </p>
    </div>

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
        <li><a href="">Mentions légales</a></li>
        <li><a href="">Conditions générales de ventes</a></li>
        <li><a href="">Données personnelles</a></li>
        <li><a href="">Gestions des cookies</a></li>
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
</body>
</html>