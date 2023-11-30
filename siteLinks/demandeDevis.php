<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
  <link rel="stylesheet" href="asset/css/headerAndFooter.css">
  <link rel="stylesheet" href="asset/css/devis.css">
  <title>Quoicoubeh</title>
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
        <h4><a href="">Messagerie</a></h4>
        <h4><a href="">Mes réservations</a></h4>
        <h4><a href="compteAccueil.php">Mon compte</a></h4>
      <?php } else {
      ?>
        <h4><a href="connexion.php">Se connecter</a></h4>
      <?php
      }
      ?>
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

    <main class="main-devis ">
        <?php

        include('connect_params.php');
        try {

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $id = $_POST["id"];
            $qui = $_POST["qui"];




            $query1 = "SELECT * FROM test.plage WHERE id_logement = (:id_logement);";
            $stmt = $dbh->prepare($query1);
            $stmt->bindParam(':id_logement', $id, PDO::PARAM_INT);
            $stmt->execute();
            $plage = $stmt->fetch(PDO::FETCH_ASSOC);





            $queryLogement = "SELECT * FROM test.logement WHERE id_logement = :id_logement";
            $stmtLogement = $dbh->prepare($queryLogement);
            $stmtLogement->bindParam(':id_logement', $id, PDO::PARAM_INT);
            $stmtLogement->execute();
            $info = $stmtLogement->fetch(PDO::FETCH_ASSOC);


            $i = 0;
            foreach ($dbh->query("SELECT * from test.photo_logement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

                $photo[$i] = $row;
                $i++;
            }
            $i = 0;
            foreach ($dbh->query("SELECT * from test.amenagement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {


                $amena[$i] = $row;
                $i++;
            }
            $i = 0;
            foreach ($dbh->query("SELECT * from test.installation WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {


                $instal[$i] = $row;
                $i++;
            }
            $i = 0;
            foreach ($dbh->query("SELECT * from test.service WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {
                $service[$i] = $row;
                $i++;
            }
            $i = 0;
            foreach ($dbh->query("SELECT * from test.prix_charge WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {
                $charge[$i] = $row;
                $i++;
            }

            $query = "SELECT * FROM test.image WHERE id_image = :id_image";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':id_image', $photo[0]["id_image"], PDO::PARAM_INT);
            $stmt->execute();
            $current = $stmt->fetch();
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }

        ?>

        <div class="demande">
            <div class="retour">
                <button class="boutonRetour" onclick="history.back()"><img src="asset/icons/blanc/retour.svg"></button>

                <h1 class="h1-mobile"><?php echo ($qui == "proprietaire" || $qui == "client") ? "Demande de réservation" : "Demande de devis"; ?></h1>
            </div>
            <div class="voyage">
                <h2 class="h2-mobile">Votre Voyage</h2>
                <div class="voyage_container" <?php if ($qui == "proprietaire" || $qui == "client") {
                                                    echo 'id="block_date"';
                                                } ?>>
                    <div class="date">
                        <?php
                        $dateDebut = $plage["date_debut"];
                        $dateDebutFormat = strtotime($dateDebut);
                        $dateArrive = date("l d-m-Y", $dateDebutFormat);

                        $dateFin = $plage["date_fin"];
                        $dateFinFormat = strtotime($dateFin);
                        $dateDepart = date("l d-m-Y", $dateFinFormat);

                        function convertirJourEnFrancais($jourEnAnglais)
                        {
                            switch ($jourEnAnglais) {
                                case 'Monday':
                                    return 'Lundi';
                                case 'Tuesday':
                                    return 'Mardi';
                                case 'Wednesday':
                                    return 'Mercredi';
                                case 'Thursday':
                                    return 'Jeudi';
                                case 'Friday':
                                    return 'Vendredi';
                                case 'Saturday':
                                    return 'Samedi';
                                case 'Sunday':
                                    return 'Dimanche';
                                default:
                                    return $jourEnAnglais;
                            }
                        }


                        $jourArrivee = convertirJourEnFrancais(date('l', $dateDebutFormat));
                        $jourDepart = convertirJourEnFrancais(date('l', $dateFinFormat));
                        ?>

                        <div class="date_arrivee">
                            <img src="asset/icons/<?php echo ($qui == "proprietaire" || $qui == "client") ? "blanc" : "bleu"; ?>/date.svg" alt="">
                            <p><?php echo ($jourArrivee . date(" d-m-Y", $dateDebutFormat)); ?></p>
                        </div>
                        <div class="date_depart">
                            <img src="asset/icons/<?php echo ($qui == "proprietaire" || $qui == "client") ? "blanc" : "bleu"; ?>/date.svg" alt="">
                            <p><?php echo ($jourDepart . date(" d-m-Y", $dateFinFormat)); ?></p>
                        </div>
                    </div>

                    <div class="personne">
                        <img src="asset/icons/<?php echo ($qui == "proprietaire" || $qui == "client") ? "blanc" : "bleu"; ?>/personne.svg" alt="">
                        <div class="ligne_champ_nombre_ajlog">

                            <div class="number-input">
                                <?php if ($qui != "client") { ?> <form id="test" action="demandeDevis.php" class="" method="POST"> <?php } ?>
                                    <button type="button" onclick="decrement('Personne')" class="minus">-</button>

                                    <input class="quantity" id="Personne" name="Personne" value="1" type="text">
                                    <button type="button" onclick="increment('Personne')" class="plus">+</button>
                            </div>
                            <label for="Personne">Personnes</label>
                        </div>
                        <img src="asset/icons/bleu/arrow-down.svg" style="<?php echo ($qui == "proprietaire" || $qui == "client") ? "display:none;" : "display:none"; ?>" alt="">

                    </div>
                </div>
            </div>

            <div class="icons_info">


                <div class="type_logement">
                    <h2 class="h2-mobile">Type de logement</h2>
                    <div class="icons">
                        <div>
                            <img src="asset/icons/bleu/appart_bleu.svg" alt="">
                            <p><?php echo ($info["nature_logement"]); ?> </p>
                        </div>
                        <div>
                            <img src="asset/icons/bleu/salon.svg" alt="">
                            <p>Salon</p>
                        </div>
                        <div>
                            <img src="asset/icons/bleu/chambre.svg" alt="">
                            <p><?php echo ($info["nb_chambre"]); ?> chambres</p>
                        </div>
                        <div>
                            <img src="asset/icons/bleu/salle_bain.svg" alt="">
                            <p><?php echo ($info["nb_salle_de_bain"]); ?> Salle de bain</p>
                        </div>
                        <div>
                            <img src="asset/icons/bleu/cusine.svg" alt="">
                            <p>Cusine</p>
                        </div>
                        <div>
                            <img src="asset/icons/bleu/wifi.svg" alt="">

                            <p>Wi-fi</p>
                        </div>
                    </div>
                </div>
                <div class="Amenagements">
                    <h2 class="h2-mobile">Aménagements proposés</h2>
                    <div class="icons">
                        <?php
                        if (empty($amena)) {
                        ?>
                            <div class="messageAucun">
                                <p>Aucun aménagements.</p>
                            </div>
                            <?php
                        } else {


                            foreach ($amena as $elemnt => $value) {

                            ?>
                                <div>
                                    <img src="asset/icons/bleu/<?php echo (strtolower($value["nom_amenagement"])); ?>.svg" alt="">
                                    <p><?php echo ($value["nom_amenagement"]); ?></p>
                                </div>
                        <?php }
                        } ?>
                    </div>

                </div>
                <div class="Instalation">
                    <h2 class="h2-mobile">Installations</h2>
                    <div class="icons">
                        <?php
                        if (empty($instal)) {
                        ?>
                            <div class="messageAucun">
                                <p>Aucun Instalation.</p>
                            </div>
                            <?php
                        } else {
                            foreach ($instal as $elemnt => $value) {
                            ?>
                                <div>
                                    <img src="asset/icons/bleu/<?php echo (strtolower($value["nom_installation"])); ?>.svg" alt="">
                                    <p><?php echo ($value["nom_installation"]); ?></p>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="services">
                    <h2 class="h2-mobile">Services compris</h2>
                    <div class="icons">
                        <?php
                        if (empty($service)) {
                        ?>
                            <div class="messageAucun">
                                <p>Aucun services.</p>
                            </div>
                            <?php
                        } else {
                            foreach ($service as $elemnt => $value) {


                            ?>
                                <div>
                                    <img src="asset/icons/bleu/<?php echo ($value["nom_service"]); ?>.svg" alt="">
                                    <p><?php echo ($value["nom_service"]); ?></p>
                                </div>
                        <?php }
                        } ?>

                    </div>

                </div>




            </div>
            <div id="check_box_info">
                <h2 class="h2-mobile">Options supplémentaires</h2>


                <?php

                if ($value["nom_service"] != "ménage") {
                ?>
                    <div id="input_check_box_info">
                        <label>
                            <input type="checkbox" name="menage" value="1" <?php echo ($qui == "proprietaire" || $qui == "client") ? ' disabled="disabled"' : ""; ?><?php echo ($_POST["menage"] == "1") ? 'checked' : ""; ?>>
                            Ménage
                        </label>
                    </div>
                <?php
                }
                ?>

                <div id="input_check_box_info">
                    <label>
                        <input type="checkbox" name="animaux" value="1" <?php echo ($qui == "proprietaire" || $qui == "client") ? ' disabled="disabled"' : ""; ?><?php echo ($_POST["menage"] == "1") ? 'checked' : ""; ?>>

                        Animaux de compagnie
                    </label>
                </div>
                <p>En cas de détérioration du logement, toutes charges supplémentaires <br> seront vues directement avec
                    le propriétaire</p>


            </div>
        </div>

        <div class="recap">
            <div class="sticky_recap">
                <div class="recap-info">
                    <div class="info_logoment">
                        <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($current["extension_image"]); ?>" width="183px" height="124px">
                        <div>
                            <span>
                                <p class="info">Logement : <?php echo ($info["nature_logement"]) ?></p>
                            </span>
                            <span>
                                <p class="more_info"> <?php echo ($info["nature_logement"]) ?> <?php echo ($info["type_logement"]); ?>, <?php echo ($info["localisation"]); ?></p>
                            </span>
                        </div>



                    </div>
                    <hr>
                    <div class="info_prix">

                        <div class="row">
                            <div class="label"><?php echo ($info["prix_base_ht"]); ?>€ x 14 nuits</div>
                            <div class="value"><?php echo ($info["prix_base_ht"]) * 14; ?>€</div>
                        </div>
                        <?php
                        $somme = 0;
                        if (empty($charge) == false) {

                            foreach ($charge as $elemnt => $value) { ?>
                                <div class="row">
                                    <div class="label"><?php echo ($value["nom_charge"]); ?></div>
                                    <div class="value"><?php echo ($value["prix_charge"]); ?>€</div>
                                </div>
                        <?php $somme = $somme + $value["prix_charge"];
                            }
                        } ?>
                        <div class="row">
                            <div class="label">Taxe de séjour</div>
                            <div class="value">29.96€</div>
                        </div>
                        <div class="row">
                            <div class="label">TVA</div>
                            <div class="value"><?php echo ($info["prix_base_ht"]) * 14 * 1.10 - ($info["prix_base_ht"]) * 14; ?>€</div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="label_t">Total</div>
                            <div class="value">EUR <?php echo ($info["prix_base_ht"]) * 14 * 1.10 + $somme + 29.96 ?>€</div>
                        </div>
                    </div>



                </div>
                <?php if ($qui == null) {
                ?>


                    <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                    <input name="qui" value="proprietaire" hidden readonly>
                    <button type="submit" class="devisButton">Envoyer une demande de devis</button>
                    </form>


                <?php } ?>
                <?php


                if ($qui == "proprietaire") {
                    echo ("proprietaire");

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $deb = $plage["date_debut"];
                        $fin = $plage["date_fin"];
                        $id_compte = $_SESSION['userId'];;
                        $nb_personne = intval($_POST["Personne"]);

                        try {

                            $stmt = $dbh->prepare("
                                INSERT INTO test.reservation (
                                debut_reservation,
                                fin_reservation,
                                nb_personne,
                                id_compte,
                                id_logement
                                ) VALUES (
                                :debut_reservation,
                                :fin_reservation,
                                :nb_personne    ,
                                :id_compte,
                                :id_logement
                                )
                                returning id_reservation");


                            $stmt->bindParam(':debut_reservation', $deb);
                            $stmt->bindParam(':fin_reservation', $fin);
                            $stmt->bindParam(':nb_personne', $nb_personne);
                            $stmt->bindParam(':id_compte', $id_compte);
                            $stmt->bindParam(':id_logement', $id);


                            $stmt->execute();
                            $Idres = $stmt->fetchAll()[0];
                        } catch (PDOException $e) {
                            echo "Erreur lors de l'insertion du devis : " . $e->getMessage();
                        }
                    }


                ?>

                    <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                    <input name="qui" value="client" hidden readonly>
                    <input name="reservation" value="<?php echo ($Idres["id_reservation"]); ?>" hidden readonly>
                    <button type="submit" class="devisButton">Envoyer le devis</button>
                    </form>

                    <div class="button_refuser">
                        <a href="#" onclick="afficherPopup()">Refuser le devis</a>
                    </div>

                    <div id="overlay"></div>
                    <div id="popup">
                        <p>Etes-vous sûr de vouloir refuser le devis ?</p>
                        <div class="button_confirmation">

                            <a href="#" id="annuler" onclick="cacherPopup()">Annuler</a>
                            <a href="logement.php?id=<?php echo ($id) ?>" id="confirmer" onclick="confirmerRefus()">Confirmer</a>
                        </div>
                    </div>

                <?php } ?>
                <?php
                if ($qui == "client") { ?>
                    <?php
                    echo ("clientFinal");


                    $id_reserv = $_POST["reservation"];
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $prix_devis = $info["prix_base_ht"];
                        $delai_acceptation = date("Y-m-d");
                        $acceptation = true;
                        $date_devis = date("Y-m-d H:i:s");


                        try {

                            $stmt = $dbh->prepare("
                                INSERT INTO test.devis (
                                id_reservation,
                                prix_devis,
                                delai_acceptation,
                                acceptation,
                                date_devis
                                ) VALUES (
                                :id_reservation,
                                :prix_devis,
                                :delai_acceptation,
                                :acceptation,
                                :date_devis
                                )
                                ");


                            $stmt->bindParam(':id_reservation', $id_reserv, PDO::PARAM_INT);
                            $stmt->bindParam(':prix_devis', $prix_devis);
                            $stmt->bindParam(':delai_acceptation', $delai_acceptation);
                            $stmt->bindParam(':acceptation', $acceptation, PDO::PARAM_BOOL);
                            $stmt->bindParam(':date_devis', $date_devis);


                            $stmt->execute();
                        } catch (PDOException $e) {
                            echo "Erreur lors de l'insertion du devis : " . $e->getMessage();
                        }
                    }

                    $queryDevis = "SELECT * FROM test.devis WHERE id_reservation = :id_reservation";
                    $stmtDevis = $dbh->prepare($queryDevis);
                    $stmtDevis->bindParam(':id_reservation', $id_reserv, PDO::PARAM_INT);
                    $stmtDevis->execute();
                    $devis = $stmtDevis->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <form id="payer" action="paiement.php" class="button_valider" method="POST">
                        <input name="devis" value="<?php echo ($devis["id_devis"]); ?>" hidden readonly>
                        <button type="submit" class="devisButton">Payer le devis</button>
                    </form>

                    <div class="button_refuser">
                        <a href="#" onclick="afficherPopup()">Refuser le devis</a>
                    </div>


                    <div id="overlay"></div>
                    <div id="popup">
                        <p>Etes-vous sûr de vouloir refuser le devis ?</p>
                        <div class="button_confirmation">

                            <a href="#" id="annuler" onclick="cacherPopup()">Annuler</a>
                            <a href="logement.php?id=<?php echo ($id) ?>" id="confirmer" onclick="confirmerRefus()">Confirmer</a>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>



    </main>
    <div class="conditions_anulations">
        <div class="conditions_plus">
            <h2>Conditions d'annulation</h2>
            <p>Annulation gratuite 14 jours avant la date de début. Si vous annuler avant le 9 juin, le remboursement
                sera partiel sans aucun remboursement des frais de services. Si vous annulez le jour de votre arrivée,
                aucun remboursement ne sera pris en compte</p>
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
    <script src="asset/js/popUpDevis.js"></script>
    <script src="asset/js/devisNbPersonnne.js"></script>
</body>

</html>
