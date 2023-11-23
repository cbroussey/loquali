<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/style.css">
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
          <path d="M23.7497 10.7258C22.7264 4.4244 20.3126 0 17.5035 0C14.6945 0 12.2807 4.4244 11.2573 10.7258H23.7497ZM10.728 17.5C10.728 19.0665 10.8127 20.5696 10.9609 22.0161H24.0391C24.1873 20.5696 24.272 19.0665 24.272 17.5C24.272 15.9335 24.1873 14.4304 24.0391 12.9839H10.9609C10.8127 14.4304 10.728 15.9335 10.728 17.5ZM33.6449 10.7258C31.6263 5.93448 27.5398 2.22984 22.4934 0.733871C24.2156 3.11895 25.4013 6.71069 26.0224 10.7258H33.6449ZM12.5066 0.733871C7.46723 2.22984 3.37366 5.93448 1.36217 10.7258H8.98467C9.59871 6.71069 10.7844 3.11895 12.5066 0.733871ZM34.4001 12.9839H26.3047C26.4529 14.4657 26.5376 15.9829 26.5376 17.5C26.5376 19.0171 26.4529 20.5343 26.3047 22.0161H34.393C34.7812 20.5696 35 19.0665 35 17.5C35 15.9335 34.7812 14.4304 34.4001 12.9839ZM8.46945 17.5C8.46945 15.9829 8.55414 14.4657 8.70236 12.9839H0.606977C0.225852 14.4304 0 15.9335 0 17.5C0 19.0665 0.225852 20.5696 0.606977 22.0161H8.6953C8.55414 20.5343 8.46945 19.0171 8.46945 17.5ZM11.2573 24.2742C12.2807 30.5756 14.6945 35 17.5035 35C20.3126 35 22.7264 30.5756 23.7497 24.2742H11.2573ZM22.5005 34.2661C27.5398 32.7702 31.6334 29.0655 33.6519 24.2742H26.0294C25.4083 28.2893 24.2226 31.881 22.5005 34.2661ZM1.36217 24.2742C3.38072 29.0655 7.46723 32.7702 12.5136 34.2661C10.7915 31.881 9.60577 28.2893 8.98467 24.2742H1.36217Z" fill="#F5F5F5"/>
        </svg>
        <svg id="headerArrowLang" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.99141 13.4874C9.54926 14.1709 10.4552 14.1709 11.0131 13.4874L19.5816 2.98945C20.1395 2.30599 20.1395 1.19605 19.5816 0.512594C19.0238 -0.170866 18.1178 -0.170866 17.56 0.512594L10 9.77485L2.44003 0.518062C1.88218 -0.165399 0.976236 -0.165399 0.418387 0.518062C-0.139462 1.20152 -0.139462 2.31146 0.418387 2.99492L8.98695 13.4929L8.99141 13.4874Z" fill="#F5F5F5"/>
        </svg>
      </div>
      <h4><a href="">Messagerie</a></h4>
      <h4><a href="">Mes réservations</a></h4>
      <h4><a href=<?php echo $linkAccount ?>>Mon compte</a></h4>
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
    
    <a href="index.php">
        <img src="asset/img/svgPagePerso/toBack.svg" alt="" id="svgBack">
    </a>
    <div id="ensemble">
        <div class="infosProprio">
            <div id="infosTous">
                <div>
                    <figure>
                        <img src="asset/img/svgPagePerso/photoProfil.svg" alt="" id="photoProfil">
                    </figure>
                </div>
                <div class = "infos">
                    <h2>MOMOA Jason</h2>
                    <figure class="star">
                        <img src="asset/img/svgPagePerso/star.svg" alt="">
                        <figcaption>4.9</figcaption>
                    </figure>
                    <figure class="tel">
                        <img src="asset/img/svgPagePerso/tel.svg" alt="">
                        <figcaption>06 06 06 06 06</figcaption>
                    </figure>
                    <figure class="mail">
                        <img src="asset/img/svgPagePerso/mail.svg" alt="">
                        <figcaption>pop.sauce@popsauce.sauce</figcaption>
                    </figure>
                </div>

            </div>

                <div class="separateur">
                </div>

                <div id="aProposDeMoi">
                    <div>
                        <h2>À propos de moi</h2>
                        <figure>
                            <img src="asset/img/svgPagePerso/modification.svg" alt="modifier">
                        </figure>
                    </div>
                    
                    <p id="textProposDeMoi">J’utilise la plateforme depuis longtemps et j’ai proposé plusieurs logement de qualité aux fils des années. Avec des logements toujours au bord de la mer je propose des baignades matinales pour bien vous rafraichir.</p>

                </div>
        
        
        </div>
        <div id="logementPropo">
            <h2 id="titreLogement">Logements proposés</h2>
            <div id="listeLogements">
                <div class="listeUnLogement">
                    <div>
                        <img src="asset/img/svgPagePerso/photo1.svg" alt="">
                    </div>
                    
                    <div class="unLogement">
                        <h2>Appartement T2, Perros-Guirec</h2>
                        <p>22700, <U>Côte-d’Armor</U></p>
                        <div class="noteAvis">
                            <img src="asset/img/svgPagePerso/star.svg" alt="">
                            <p>5, 24 avis</p>
                        </div>
                        <p class="consulterLogement"><em>Consulter le logement</em></p>
                    </div>
                    
                </div>

                <div class="separateur1">
                </div>

                <div class="listeUnLogement">
                    <div>
                        <img src="asset/img/svgPagePerso/photo1.svg" alt="">
                    </div>
                    
                    <div class="unLogement">
                        <h2>Appartement T2, Perros-Guirec</h2>
                        <p>22700, <U>Côte-d’Armor</U></p>
                        <div class="noteAvis">
                            <img src="asset/img/svgPagePerso/star.svg" alt="">
                            <p>5, 24 avis</p>
                        </div>
                        <p class="consulterLogement"><em>Consulter le logement</em></p>
                    </div>
                    
                </div>

                <div class="separateur1">                    
                </div>
                
                <div class="listeUnLogement">
                    <div>
                        <img src="asset/img/svgPagePerso/photo1.svg" alt="">
                    </div>
                    
                    <div class="unLogement">
                        <h2>Appartement T2, Perros-Guirec</h2>
                        <p>22700, <U>Côte-d’Armor</U></p>
                        <div class="noteAvis">
                            <img src="asset/img/svgPagePerso/star.svg" alt="">
                            <p>5, 24 avis</p>
                        </div>
                        <p class="consulterLogement"><em>Consulter le logement</em></p>
                    </div>
                    
                </div>

                <div class="separateur1">                    
                </div>

                <div class="listeUnLogement">
                    <div>
                        <img src="asset/img/svgPagePerso/photo1.svg" alt="">
                    </div>
                    
                    <div class="unLogement">
                        <h2>Appartement T2, Perros-Guirec</h2>
                        <p>22700, <U>Côte-d’Armor</U></p>
                        <div class="noteAvis">
                            <img src="asset/img/svgPagePerso/star.svg" alt="">
                            <p>5, 24 avis</p>
                        </div>
                        <p class="consulterLogement"><em>Consulter le logement</em></p>
                    </div>
                    
                </div>

        

            </div>
        </div>
    </div>    
    <footer>
    <div>
      <div id="footerCercleLogo">
        <img src="asset/img/logo.png" alt="logo">
      </div>
      <div id="footerText">
        <div>
          <h4>Nous contacter</h4>
          <address>1, Rue édouard Branly, 22300 Lannion</address><br>
          <a href="tel:+33296469300">02 96 46 93 00</a><br>
          <a href="mailto:iut-lannion.univ-rennes.fr">iut-lannion.univ-rennes.fr</a>
        </div>
        <div>
          <h4>Informations légales</h4>
          <a href="">Plan du site</a><br>
          <a href="">Mention légales</a><br>
          <a href="">Condition générales de ventes</a><br>
          <a href="">Données personnelles</a><br>
          <a href="">Gestion de cookies</a><br>
        </div>
      </div>
    </div>
    <div>
      <p>texte random</p>
      <p>Copyright @ 2023 LoQuali.com</p>
      <p>Suivez-nous !</p>
    </div>
  </footer>
</body>
</html>