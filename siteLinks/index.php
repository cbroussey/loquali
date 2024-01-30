<?php
session_start();
error_reporting(0);

if ($_SESSION["userType"] == "proprietaire") {
?>
  <script type="text/javascript">
    window.location.href = "compte.php";
  </script>


<?php
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
  <link rel="stylesheet" href="asset/css/headerAndFooter.css">
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="asset/css/index.css">
  <title>LoQuali - Accueil</title>
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


  <!-- Petit carrousel avec animation -->

  <div class="slider-container slider1">
    <div class="barreRecherche">
      <div class="bar">
        <div class="gauche-bar">
          <svg width="29" height="39" viewBox="0 0 29 39" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.8497 37.4203C2.01173 21.7084 0 20.0959 0 14.3216C0 6.41195 6.41195 0 14.3216 0C22.2312 0 28.6431 6.41195 28.6431 14.3216C28.6431 20.0959 26.6314 21.7084 15.7934 37.4203C15.0822 38.4477 13.5609 38.4476 12.8497 37.4203ZM14.3216 20.2889C17.6172 20.2889 20.2889 17.6172 20.2889 14.3216C20.2889 11.0259 17.6172 8.35424 14.3216 8.35424C11.0259 8.35424 8.35424 11.0259 8.35424 14.3216C8.35424 17.6172 11.0259 20.2889 14.3216 20.2889Z" fill="#1D4C77" />
          </svg>
          <input id="searchbar" type="text" placeholder="Rechercher une destination ...">
        </div>
        <a class="search" href="#">Rechercher</a>
      </div>
    </div>
    <div class="point">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
    <div class="slider" id="slider">
      <img src="asset/img/indexSlider1.jpg" alt="d">
      <img src="asset/img/indexSlider2.jpg" alt="">
      <img src="asset/img/indexSlider3.jpg" alt="">
      <img src="asset/img/indexSlider4.jpg" alt="tets">
      <img src="asset/img/indexSlider1.jpg" alt="d">
    </div>
  </div>

  <div class="filter-container">


    <div id="slogan">
      <div id="ps">
        <p>Des logements pour tout les goûts </p>
      </div>
    </div>

    <div id="gauche">


      <!-- Début des filtres (sélection) -->

      <div class="filtrer"> <!-- Bouton visible -->
        <a class="button" id="Btn_Filtre">
          <svg id="fleche_Filtre" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
            <path d="M23.827 0H1.17324C0.132319 0 -0.392925 1.26299 0.344624 2.00054L9.375 11.0323V21.0938C9.375 21.4761 9.56157 21.8345 9.87485 22.0538L13.7811 24.7872C14.5518 25.3267 15.625 24.7799 15.625 23.8271V11.0323L24.6556 2.00054C25.3916 1.26445 24.87 0 23.827 0Z" fill="#F5F5F5" />
          </svg>
          <p>Filtres</p>
        </a>

        <div id="filtreContainer">
          <div id="Liste_Filtre"> <!-- Partie visible uniquement lors d'un click sur le buton filtres -->
            <form id="triFiltres" action="index.php" method="post">

              <!-- Ceci est un formulaire permettant de récupérer les donées avec lequel l'utilisateur veux ranger les logements (garde en mémoire les valeurs insérer) -->


              <div class="menutrie">
                <ul class="menu-demo2">
                  <li> <p>Trier 
                    <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M6.29399 8.67047C6.68448 9.10984 7.31864 9.10984 7.70914 8.67047L13.7071 1.92179C14.0976 1.48242 14.0976 0.768892 13.7071 0.329525C13.3166 -0.109843 12.6825 -0.109843 12.292 0.329525L7 6.28383L1.70802 0.333039C1.31753 -0.106328 0.683365 -0.106328 0.292871 0.333039C-0.0976236 0.772407 -0.0976236 1.48594 0.292871 1.92531L6.29086 8.67399L6.29399 8.67047Z" fill="#F5F5F5" />
                    </svg></p>
                    <ul>
                      <li>
                        <input type="radio" name="tri" id="rad_tri_1" value="Prix : Ordre Croissant" <?php if ($_POST["tri"] == "Prix : Ordre Croissant") {
                                                                                                        echo ("checked");
                                                                                                      } ?> />
                        <label for="rad_tri_1" class="btnChoix3">Prix : Ordre Croissant</label>
                      </li>
                      <li>
                        <input type="radio" name="tri" id="rad_tri_2" value="Prix : Ordre Décroissant" <?php if ($_POST["tri"] == "Prix : Ordre Décroissant") {
                                                                                                          echo ("checked");
                                                                                                        } ?> />
                        <label for="rad_tri_2" class="btnChoix3">Prix : Ordre Décroissant</label>
                      </li>
                      <li>
                        <input type="radio" name="tri" id="rad_tri_3" value="Récent" <?php if ($_POST["tri"] == "Récent") {
                                                                                        echo ("checked");
                                                                                      } ?> />
                        <label for="rad_tri_3" class="btnChoix3">Récent</label>
                      </li>
                      <li>
                        <input type="radio" name="tri" id="rad_tri_4" value="Ancien" <?php if ($_POST["tri"] == "Ancien") {
                                                                                        echo ("checked");
                                                                                      } ?> />
                        <label for="rad_tri_4" class="btnChoix3">Ancien</label>
                      </li>
                      <li>
                        <input type="radio" name="tri" id="rad_tri_5" value="Avis" <?php if ($_POST["tri"] == "Avis") {
                                                                                      echo ("checked");
                                                                                    } ?> />
                        <label for="rad_tri_5" class="btnChoix3">Avis</label>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="baaaaaaaarrrre"></div>

              <div class="jailesbarres">
                <div>
                  <h3>Types de logements </h3>
                </div>
              </div>

              <div id="newLogementInput" class="barreBtnChoix">
                <div class="maison_or_appart">
                  <input type="checkbox" id="test1" name="type" value="Maison" <?php if ($_POST["type"] == "Maison") {
                                                                                  echo ("checked");
                                                                                } ?> />
                  <label for="test1" class="btnChoix">Maison</label>
                </div>
                <div class="maison_or_appart">
                  <input type="checkbox" id="test2" name="type" value="Appartement" <?php if ($_POST["type"] == "Appartement") {
                                                                                      echo ("checked");
                                                                                    } ?> />
                  <label for="test2" class="btnChoix">Appartement</label>
                </div>
              </div>

              <div class="baaaaaaaarrrre"></div>

              <div class="jailesbarres">
                <h3>Prix</h3>
              </div>

              <div id="leprixla">
                <div class="b4r3">
                  <p>Prix min</p>
                  <input class="quantity" id="PrixMin" name="PrixMin" type="number" pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMin"] != "") { ?> value="<?php echo ($_POST["PrixMin"]) ?>" <?php   } ?>>
                </div>

                <p id="petitebarredeseparation">-</p>

                <div class="b4r3" id="adroiteuuu">
                  <p>Prix max</p>
                  <input class="quantity" id="PrixMax" name="PrixMax" type="number" pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMax"] != "") { ?> value="<?php echo ($_POST["PrixMax"]) ?>" <?php   } ?>>
                </div>
              </div>

              <div class="baaaaaaaarrrre"></div>

              <div class="jailesbarres">
                <div class="jailabarre"></div>
                <h3>Capacité d’accueil</h3>
                <div class="jailabarre"></div>
              </div>

              <div class="b4r3" id="lesgensla">
                <p>Nombre de personnes maximum</p>
                <input class="quantity" id="Personneeeeeeeeeeeee" name="Personne" type="number" pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["Personne"] != "") { ?> value="<?php echo ($_POST["Personne"]) ?>" <?php   } ?>>
              </div>

              <div class="baaaaaaaarrrre"></div>

              <div class="jailesbarres">
                <div class="jailabarre"></div>
                <h3>Aménagements</h3>
                <div class="jailabarre"></div>
              </div>

              <div class="aaaaamenagement">
                <ul class="menu-demo2">
                  <li> <p>aménagements</p> 
                    <ul>
                      <li>
                        <input type="checkbox" id="amena1" name="amena[]" value="jardin" <?php foreach ($_POST["amena"] as $key => $val) {
                                                                                        if ($val == "jardin") {
                                                                                          echo ("checked");
                                                                                        }
                                                                                      }  ?> />
                        <label for="amena1" class="btnChoix2">jardin</label>
                      </li>
                      <li>
                        <input type="checkbox" id="amena2" name="amena[]" value="balcon" <?php foreach ($_POST["amena"] as $key => $val) {
                                                                                            if ($val == "balcon") {
                                                                                              echo ("checked");
                                                                                            }
                                                                                          }  ?> />
                        <label for="amena2" class="btnChoix2">balcon</label>
                      </li>
                      <li>
                        <input type="checkbox" id="amena3" name="amena[]" value="terrasse" <?php foreach ($_POST["amena"] as $key => $val) {
                                                                                              if ($val == "terrasse") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="amena3" class="btnChoix2">terrasse</label>
                      </li>
                      <li>
                        <input type="checkbox" id="amena4" name="amena[]" value="parking" <?php foreach ($_POST["amena"] as $key => $val) {
                                                                                            if ($val == "parking") {
                                                                                              echo ("checked");
                                                                                            }
                                                                                          }  ?> />
                        <label for="amena4" class="btnChoix2">parking</label>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="jailesbarres">
                <div class="jailabarre"></div>
                <h3>Installation</h3>
                <div class="jailabarre"></div>
              </div>

              <div class="inssssstaaaal">
                <ul class="menu-demo2">
                  <li> <p>aménagements</p> 
                    <ul>
                      <li>
                        <input type="checkbox" id="instal1" name="instal[]" value="climatisation" <?php foreach ($_POST["instal"] as $key => $val) {
                                                                                                    if ($val == "climatisation") {
                                                                                                      echo ("checked");
                                                                                                    }
                                                                                                  }  ?> />
                        <label for="instal1" class="btnChoix2">climatisation</label>
                      </li>
                      <li>
                        <input type="checkbox" id="instal2" name="instal[]" value="piscine" <?php foreach ($_POST["instal"] as $key => $val) {
                                                                                              if ($val == "piscine") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="instal2" class="btnChoix2">piscine</label>
                      </li>
                      <li>
                        <input type="checkbox" id="instal3" name="instal[]" value="jacuzzi" <?php foreach ($_POST["instal"] as $key => $val) {
                                                                                              if ($val == "jacuzzi") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="instal3" class="btnChoix2">jacuzzi</label>
                      </li>
                      <li>
                        <input type="checkbox" id="instal4" name="instal[]" value="hammam" <?php foreach ($_POST["instal"] as $key => $val) {
                                                                                              if ($val == "hammam") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="instal4" class="btnChoix2">hammam</label>
                      </li>
                      <li>
                        <input type="checkbox" id="instal5" name="instal[]" value="sauna" <?php foreach ($_POST["instal"] as $key => $val) {
                                                                                            if ($val == "sauna") {
                                                                                              echo ("checked");
                                                                                            }
                                                                                          }  ?> />
                        <label for="instal5" class="btnChoix2">sauna</label>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="jailesbarres">
                <div class="jailabarre"></div>
                <h3>Service</h3>
                <div class="jailabarre"></div>
              </div>

              <div class="serrrrrvice">
                <ul class="menu-demo2">
                  <li> <p>aménagements</p> 
                    <ul>
                      <li>
                        <input type="checkbox" id="service1" name="service[]" value="linge" <?php foreach ($_POST["service"] as $key => $val) {
                                                                                              if ($val == "linge") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="service1" class="btnChoix2">linge</label>
                      </li>
                      <li>
                        <input type="checkbox" id="service2" name="service[]" value="ménage" <?php foreach ($_POST["service"] as $key => $val) {
                                                                                                if ($val == "ménage") {
                                                                                                  echo ("checked");
                                                                                                }
                                                                                              }  ?> />
                        <label for="service2" class="btnChoix2">ménage</label>
                      </li>
                      <li>
                        <input type="checkbox" id="service3" name="service[]" value="taxi" <?php foreach ($_POST["service"] as $key => $val) {
                                                                                              if ($val == "taxi") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="service3" class="btnChoix2">taxi</label>
                      </li>
                      <li>
                        <input type="checkbox" id="service4" name="service[]" value="repas" <?php foreach ($_POST["service"] as $key => $val) {
                                                                                              if ($val == "repas") {
                                                                                                echo ("checked");
                                                                                              }
                                                                                            }  ?> />
                        <label for="service4" class="btnChoix2">repas</label>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>


              <br>

              <div id="boutonsEnBaaaaas">
                <div class="reeeetour">
                  <a href="index.php">Annulé</a>
                </div>
                <div class="valiiiide">
                  <input type="submit" name="test" value="Valider" id="validerouuuu">
                </div>
              </div>
          </div>
        </div>
      </div>

    </div>


  </div>

  <div class="box">
    <?php   /* Début intérraction avec la bdd */

    include('connect_params.php');
    try {
      $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

      /* Gestion des options de tri en attribuant à une variable la donnée dans la bdd sur laquel on veut trier les logements via un ORDER BY */

      if (isset($_POST["tri"])) {
        switch ($_POST["tri"]) {


          case "Prix : Ordre Croissant":  // CAS POUR LE TRIX POUR LE PRIX

            $tri = "prix_ttc";
            $sens = "crois";


            break; // FIN DE CAS POUR TRI DU PRIX

          case "Prix : Ordre Décroissant":  // CAS POUR LE TRIX POUR LE PRIX

            $tri = "prix_ttc";
            $sens = "desc";



            break; // FIN DE CAS POUR TRI DU PRIX




          case "Récent": // CAS POUR LE TRI EN FONCTION DES LOGEMENT LES PLUS RECENT

            $tri = "id_logement";
            $sens = "desc";
            break; // FIN DU CAS EN FONCTION DES PLUS RECENT

          case "Ancien": // CAS POUR LE TRI EN FONCTION DES LOGEMENT LES PLUS RECENT

            $tri = "id_logement";
            $sens = "crois";
            break; // FIN DU CAS EN FONCTION DES PLUS RECENT


          case "Avis": // CAS EN FONCTION DE LA NOTE DU LOGEMENT

            $sens = "desc";
            $tri = "note_logement";
            break; // FIN DU CAS EN FONCTION DE LA NOTE DU LOGEMENT


          default: // CAS ERREUR
            echo "Erreur";
        }
      } else {
        $tri = "id_logement";
        $sens = "crois";
      }

      if (isset($_POST["test"])) { /* Gestion des filtres */

        /* Ici, on défini 2 variables, 1 qui va se remlire de "AND <donnée de la bdd>=<donnée rechercher par l'utilisateur> " afin d'être ajouter à la requête pour obtenir de multiple where. La deuxième s'occupe de savoir quoi joindre pour que la requête fonctionne (par exemple pout les installations et autres). */

        $filtre = "";
        $join = "";
        foreach ($_POST as $ind => $val) {


          if ($ind == "Personne" && $val != "") {
            $filtre .= "AND nb_pers_max=$val ";
          }

          if ($ind == "type") {
            $filtre .= "AND nature_logement='$val' ";
          }

          if ($ind == "amena") {
            $join .= " NATURAL JOIN test.amenagement ";
            foreach ($val as $amena) {
              $filtre .= "AND nom_amenagement='$amena' ";
            }
          }

          if ($ind == "service") {
            $join .= " NATURAL JOIN test.service ";
            foreach ($val as $service) {
              $filtre .= "AND nom_service='$service' ";
            }
          }

          if ($ind == "instal") {
            $join .= " NATURAL JOIN test.installation ";
            foreach ($val as $instal) {
              $filtre .= "AND nom_installation='$instal' ";
            }
          }

          if ($ind == "PrixMin" && $val != "") {
            $filtre .= "AND prix_ttc>=$val ";
          }

          if ($ind == "PrixMax" && $val != "") {
            $filtre .= "AND prix_ttc<=$val ";
          }
        }
      }

      /* Requete pour récupérer tout les logements correspondant au critère des filtres. Séparation en 2 cas : tri croissant ou décroissant. puis chaque cas se sépare en 2 : filtré ou pas filtré. Si c'est filtré, applique la requete pour retrouver les logements correspondant au filtres, sinon effectue une requête normal. Pour différention les tri croissant et décroissant, ajout de DESC arpès le ORDER BY. Le seuls cas ou c'est vraiment différent c'est quand les filtres sont sélectionner. On recherche d'abbord l'id de tout les logements correspondant à la recherche, puis après, on fait une 2ème requète pour obtenir toutes les informations des logements pout les afficher. S'en suit après, et dans tout les cas, l'affichages des logements  */

      if ($sens == "crois") {
        $nb_log_rech = 0;

        if ($filtre != "") {
          foreach ($dbh->query("SELECT DISTINCT id_logement, prix_ttc, note_logement
        FROM (
            SELECT *
            FROM test.logement
            $join
            WHERE en_ligne = true
            $filtre
        ) AS subquery ORDER BY $tri;", PDO::FETCH_ASSOC) as $row) {



            $id_log_req = $row['id_logement'];

            foreach ($dbh->query("SELECT * from test.logement WHERE id_logement=$id_log_req", PDO::FETCH_ASSOC) as $row) {
              $i = 0;
              $id = $row["id_logement"];
              $info = $row;
    ?>

              <a href="logement.php?id=<?php echo ($id); ?>" class="maison">
                <div id="triangle"></div>
                <div class="etoile">
                  <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z" fill="white" />
                  </svg>
                  <p></p>

                </div>
                <?php
                foreach ($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {

                  $photo[$i] = $row;
                  $i++;
                }
                ?>

                <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">

                <p class="ville"><?php echo ($info["libelle_logement"]);  ?>, <?php echo ($info["localisation"]); ?></p>
                <p class="prix"><strong><?php echo ($info["prix_ttc"] . "€");  ?></strong> par nuit</p>
              </a>

            <?php
              $info = [];
              $photo = [];
              $nb_log_rech++;
            }
          }

          if ($nb_log_rech == 0) {
            echo "pas de logement disponible pour cette recherche";
          }
        } else {
          foreach ($dbh->query("SELECT DISTINCT * from test.logement WHERE en_ligne=true ORDER BY $tri", PDO::FETCH_ASSOC) as $row) {
            $i = 0;
            $id = $row["id_logement"];
            $info = $row;
            ?>

            <a href="logement.php?id=<?php echo ($id); ?>" class="maison">
              <div id="triangle"></div>
              <div class="etoile">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z" fill="white" />
                </svg>
                <p></p>

              </div>
              <?php
              foreach ($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {

                $photo[$i] = $row;
                $i++;
              }
              ?>

              <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">

              <p class="ville"><?php echo ($info["libelle_logement"]);  ?>, <?php echo ($info["localisation"]); ?></p>
              <p class="prix"><strong><?php echo ($info["prix_ttc"] . "€");  ?></strong> par nuit</p>
            </a>

            <?php
            $info = [];
            $photo = [];
          }
          if ($i == 0) {
            echo ("pas de logement correspondant à votre recherche");
          }
        }

        $dbh = null;
      } else {

        if ($filtre != "") {
          $results = $dbh->query("SELECT DISTINCT id_logement, prix_ttc, note_logement
        FROM (
            SELECT *
            FROM test.logement
            $join
            WHERE en_ligne = true
            $filtre
        ) AS subquery ORDER BY $tri DESC;", PDO::FETCH_ASSOC);

          $nb_log_rech = 0;


          foreach ($results as $row) {

            $nb_log_rech++;

            $id_log_req = $row['id_logement'];

            foreach ($dbh->query("SELECT * from test.logement WHERE id_logement=$id_log_req ORDER BY $tri", PDO::FETCH_ASSOC) as $row) {
              $i = 0;
              $id = $row["id_logement"];
              $info = $row;
            ?>

              <a href="logement.php?id=<?php echo ($id); ?>" class="maison">
                <div id="triangle"></div>
                <div class="etoile">
                  <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z" fill="white" />
                  </svg>
                  <p></p>

                </div>
                <?php
                foreach ($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {

                  $photo[$i] = $row;
                  $i++;
                }
                ?>

                <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">

                <p class="ville"><?php echo ($info["libelle_logement"]);  ?>, <?php echo ($info["localisation"]); ?></p>
                <p class="prix"><strong><?php echo ($info["prix_ttc"] . "€");  ?></strong> par nuit</p>
              </a>

            <?php
              $info = [];
              $photo = [];
            }
          }

          if ($nb_log_rech == 0) {
            echo ("pas de logement correspondant à votre recherche");
          }
        } else {

          foreach ($dbh->query("SELECT DISTINCT * from test.logement WHERE en_ligne=true ORDER BY $tri DESC", PDO::FETCH_ASSOC) as $row) {
            $i = 0;
            $id = $row["id_logement"];
            $info = $row;
            ?>

            <a href="logement.php?id=<?php echo ($id); ?>" class="maison">
              <div id="triangle"></div>
              <div class="etoile">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z" fill="white" />
                </svg>
                <p></p>

              </div>
              <?php
              foreach ($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {

                $photo[$i] = $row;
                $i++;
              }
              ?>

              <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">

              <p class="ville"><?php echo ($info["libelle_logement"]);  ?>, <?php echo ($info["localisation"]); ?></p>
              <p class="prix"><strong><?php echo ($info["prix_ttc"] . "€");  ?></strong> par nuit</p>
            </a>

    <?php
            $info = [];
            $photo = [];
          }
          $dbh = null;
        }
      }
    } catch (PDOException $e) {
      print "Erreur !: " . $e->getMessage() . "<br/>";
      die();
    }

    /* Fin de la partie requète avec la bdd */

    ?>




  </div>

  <div id="menu">
    <div id="choix">
      <a href="compte.php" class="bouton">Mon compte</a>

      <a href="compte.php?res=res" class="bouton"><?php if ($_SESSION["userType"] == "proprietaire") {
                                                    echo ("Mes logements");
                                                  } else {
                                                    echo ("Mes réservations");
                                                  } ?></a>
    </div>
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

  <script src="asset/js/header.js"></script>
  <script src="asset/js/index.js"></script>

</body>

</html>