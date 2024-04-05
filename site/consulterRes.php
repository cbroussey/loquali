<?php session_start();
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/devis.css">
    <link rel="stylesheet" href="asset/datepicker/style.css">
    <title>Document</title>
</head>

<body>


    <?php


    include('connect_params.php');
    try {

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $id = $_POST["id"];
        $qui = $_POST["qui"];
        $chargeSelectioner = $_POST["charge"];
        $idReservation = $_POST["reservation"];


        $query1 = "SELECT * FROM test.planning WHERE id_logement = (:id_logement);";
        $stmt = $dbh->prepare($query1);
        $stmt->bindParam(':id_logement', $id, PDO::PARAM_INT);
        $stmt->execute();
        $planning = $stmt->fetch(PDO::FETCH_ASSOC);







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

        if (isset($idReservation)) {
            $query = "SELECT * FROM test.reservation  WHERE id_reservation = :id_reservation";
            $stmtLogement = $dbh->prepare($query);
            $stmtLogement->bindParam(':id_reservation', $idReservation, PDO::PARAM_INT);
            $stmtLogement->execute();
            $reservation = $stmtLogement->fetch(PDO::FETCH_ASSOC);
            $query3 = "SELECT * FROM test.devis  WHERE id_reservation = :id_reservation";
            $stmtdevis = $dbh->prepare($query3);
            $stmtdevis->bindParam(':id_reservation', $idReservation, PDO::PARAM_INT);
            $stmtdevis->execute();
            $devis = $stmtdevis->fetch(PDO::FETCH_ASSOC);
        }

        // Calcul prix 

    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    } ?>
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
                <h4><a href="compte.php?res=res">
                        <?php if ($_SESSION["userType"] == "proprietaire") {
                            echo ("Mes logements");
                        } else {
                            echo ("Mes réservations");
                        } ?>
                    </a></h4>
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
    <main class="main-devis">
        <form action="<?php echo ($qui == "client") ? "paiement.php" : (($qui == "proprietaire") ? "validationDevisProprio.php" : "inserDevis.php"); ?>" method="POST">
            <div class="demande">
                <div class="retour">
                    <a class="boutonRetour" href="compte.php?ind=3"><img src="asset/icons/blanc/retour.svg"></a>
                    <a id="idlog" hidden>
                        <?php echo $id; ?>
                    </a>
                    <a id='id_reservation'hidden>
                         <?php echo $_POST["reservation"]; ?>
                    </a>
                    <h1 class="h1-mobile">
                        <?php echo ($qui == "proprietaire" || $qui == "client") ? "Détails de la réservation" : "Demande de devis"; ?>
                    </h1>
                </div>

                <div class="voyage">
                    <h2>Votre voyage</h2>
                    <div class="voyage_container" <?php if ($qui == "proprietaire" || $qui == "client") {
                                                        echo 'id="block_date"';
                                                    } ?>>
                        <div class="date">

                            <div class="plage_date">

                                <a id="dates2" <?php echo ($qui != "proprietaire" && $qui != "client") ? "onclick=\"toggleDP('DP2', this)\"" : "" ?> style="margin: 1em;"> <?php echo ($qui == "proprietaire" || $qui == "client") ? '<img src="asset/icons/blanc/date.svg" alt=""> ' : '<img src="asset/icons/bleu/date.svg" alt="">';  ?>
                                    <p><?php echo $qui == "proprietaire" || $qui == "client" ? (DateTime::createFromFormat('Y-m-d', $reservation["debut_reservation"]))->format("d/m/Y") . " - " . (DateTime::createFromFormat('Y-m-d', $reservation["fin_reservation"]))->format("d/m/Y") : "" ?></p>
                                </a>
                                <?php if ($qui != "proprietaire" && $qui != "client") {
                                ?><div id="DP2" class="datePicker"></div>
                                <?php } ?>


                            </div>

                        </div>

                        <div class="personne">
                            <img src="asset/icons/<?php echo ($qui == "proprietaire" || $qui == "client") ? "blanc" : "bleu"; ?>/personne.svg" alt="">
                            <div class=<?php echo ($qui == "proprietaire" || $qui == "client") ? "ligne_champ_nombre_ajlog1" : "ligne_champ_nombre_ajlog"; ?>>

                                <div class=<?php echo ($qui == "proprietaire" || $qui == "client") ? "number-input1" : "number-input"; ?>>

                                    <button type="button" onclick="decrement('Personne')" class="minus" style=<?php echo ($qui == "proprietaire" || $qui == "client") ? "display:none" : ""; ?>>-</button>

                                    <input class="quantity" id="Personne" name="Personne" value="<?php echo ($qui == "proprietaire" || $qui == "client") ? htmlspecialchars($reservation["nb_personne"]) : "1"; ?>" readonly type="text">

                                    <button type="button" onclick="increment('Personne')" class="plus" style=<?php echo ($qui == "proprietaire" || $qui == "client") ? "display:none" : ""; ?>>+</button>
                                </div>
                                <label for="Personne">Personnes</label>
                            </div>
                            <img src="asset/icons/bleu/arrow-down.svg" style="<?php echo ($qui == "proprietaire" || $qui == "client") ? "display:none;" : "display:none"; ?>" alt="">

                        </div>
                    </div>
                </div>
                <div class="iconsInfo">
                    <div class="typeLogement">
                        <h2>Type de Logement</h2>
                        <div class="icons">
                            <div>
                                <img src="asset/icons/bleu/appart_bleu.svg" alt="">
                                <p>
                                    <?php echo ($info["nature_logement"]); ?>
                                </p>
                            </div>
                            <div>
                                <img src="asset/icons/bleu/salon.svg" alt="">
                                <p>Salon</p>
                            </div>
                            <div>
                                <img src="asset/icons/bleu/chambre.svg" alt="">
                                <p>
                                    <?php echo ($info["nb_chambre"]); ?> chambres
                                </p>
                            </div>
                            <div>
                                <img src="asset/icons/bleu/salle_bain.svg" alt="">
                                <p>
                                    <?php echo ($info["nb_salle_de_bain"]); ?> Salle de bain
                                </p>
                            </div>
                            <div>
                                <img src="asset/icons/bleu/cusine.svg" alt="">
                                <p>Cuisine</p>
                            </div>
                            <div>
                                <img src="asset/icons/bleu/wifi.svg" alt="">

                                <p>Wi-fi</p>
                            </div>
                        </div>
                    </div>
                    <div class="amanagements">
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
                                        <p>
                                            <?php echo ($value["nom_amenagement"]); ?>
                                        </p>
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
                                        <p>
                                            <?php echo ($value["nom_installation"]); ?>
                                        </p>
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
                                        <p>
                                            <?php echo ($value["nom_service"]); ?>
                                        </p>
                                    </div>
                            <?php }
                            } ?>

                        </div>
                    </div>
                </div>

                <!--  <div id="check_box_info">
                    <h2 class="h2-mobile">Options supplémentaires</h2>


                    <?php
                    foreach ($charge as $elemnt => $value) {
                    ?>
                        <div id="input_check_box_info">
                            <label>
                                <input type="checkbox" name="charge" value="<?php echo ($value["nom_charge"]); ?>" <?php echo ($qui == "client") ? ' disabled="disabled"' : ""; ?><?php echo (isset($_POST["charge"]) && $_POST["charge"] == $value["nom_charge"]) ? 'checked' : ""; ?>>
                                <?php echo ($value["nom_charge"]); ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                    <p>En cas de détérioration du logement, toutes charges supplémentaires <br> seront vues directement avec
                        le propriétaire</p>


                </div>-->

            </div>
            <div class="recap">
                <div class="sticky_recap">
                    <div class="recap-info">
                        <div class="info_logoment">
                            <img src="asset/img/logements/<?php echo ($photo[0]["id_image"]); ?>.<?php echo ($current["extension_image"]); ?>" width="183px" height="124px">
                            <div>
                                <span>
                                    <p class="info">Logement :
                                        <?php echo ($info["nature_logement"]) ?>
                                    </p>
                                </span>
                                <span>
                                    <p class="more_info">
                                        <?php echo ($info["nature_logement"]) ?>
                                        <?php echo ($info["type_logement"]); ?>,
                                        <?php echo ($info["localisation"]); ?>
                                    </p>
                                </span>
                            </div>



                        </div>
                        <hr>
                        <div class="info_prix">
                            <?php

                            ?>
                            <div class="row">
                                <?php $dateDebut = new DateTime($reservation['debut_reservation']);
                                $dateFin = new DateTime($reservation['fin_reservation']);
                                $interval = $dateFin->diff($dateDebut);
                                $nbJours = $interval->days;

                                ?>
                                <div class="label">
                                    <?php
                                    if ($qui == "client") {
                                        echo $nbJours . " nuits";
                                    } elseif ($qui == "proprietaire") {
                                        echo $nbJours . " nuits";
                                    } elseif ($qui ==null){
                                        echo "nuits";
                                    }
                                    ?>
                                </div>

                                <?php if ($qui != "client" && $qui != "proprietaire") : ?>


                                    <a><input <?php echo ($qui != "proprietaire") ? 'readonly' : ''; ?> type="number" class="value" id="prixNuit" name="prixNuit" step="0.01"> €</a>
                                <?php else : ?>

                                    <div>
                                        <?php echo number_format($devis["prix_devis"], 2);?> €
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            $somme = 0;
                            if (!empty($chargeSelectioner)) {
                                foreach ($charge as $elemnt => $value) { ?>
                                    <div class="row">
                                        <div class="label">
                                            <?php echo $chargeSelectioner; ?>
                                        </div>
                                        <div class="value">
                                            <?php echo ($value["prix_charge"]); ?>€
                                        </div>
                                    </div>

                            <?php $somme = $somme + $value["prix_charge"];
                                }
                            }
                            $nom_Charge = $value["nom_charge"];
                            ?>

                            <div class="row">
                                <div class="label">TVA</div>
                                <?php if ($qui != "client" && $qui != "proprietaire") : ?>
                                    <div class="value"></div>
                                <?php else : ?>
                                    <div>
                                        <?php echo number_format($devis["prix_devis"] * 0.10, 2); ?> €
                                    </div>
                                <?php endif; ?>
                            </div>



                            <hr>


                            <div class="row">
                                <div class="label_t">Total</div>
                                <?php if ($qui != "client" && $qui != "proprietaire") : ?>
                                    <a><input readonly type="number" class="value" id="total" name="total" step="0.01">
                                        €</a>
                                <?php else : ?>
                                    <div>
                                        <?php echo number_format($devis["prix_devis"] * 0.10 + $devis["prix_devis"], 2) ; ?>
                                        €
                                    </div>
                                <?php endif; ?>


                            </div>
                        </div>



                    </div>
                    <?php if ($qui == null) {
                    ?>

                        <input name="nbJours" value="" id="nbJours" hidden readonly>
                        <input name="prix" value="" id="prix" hidden readonly>
                        <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                        <input type="text" name="start-date" id="start-date" value="" hidden readonly>
                        <input type="text" name="end-date" id="end-date" value="" hidden readonly>
                        <input name="qui" value="proprietaire" hidden readonly>
                        <button type="submit" class="devisButton">Envoyer une demande de devis</button>



                    <?php } ?>
                    <?php if ($qui == "proprietaire") {

                    ?>

                        <input name="id" value="<?php echo ($id); ?>" hidden readonly>
                        <input name="prixTTC" id="prixTTC" value="  <?php echo number_format($devis["prix_devis"] * 0.10, 2) + $devis["prix_devis"]; ?>" hidden readonly>
                        <input name="charge" value="<?php echo ($_POST["charge"]); ?>" hidden readonly>
                        <button type="submit" class="devisButton">Envoyer le devis</button>
                        <input name="reservation" value="<?php echo $idReservation; ?>" hidden readonly>

                    <?php } ?>
                    <?php
                    if ($qui == "client") { ?>




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
        </form>
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

    <script src="asset/js/popUpDevis.js"></script>
    <script src="asset/js/calculPrix.js"></script>
    <script src="asset/js/devisNbPersonnne.js"></script>
    <script src="asset/js/modiffPrixFinal.js"> </script>
    <script src="asset/js/blockdate.js"> </script>
    <script src="asset/datepicker/modules/datePicker.js"></script>


</body>

</html>