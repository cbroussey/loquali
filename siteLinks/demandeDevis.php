<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap">
    <link rel="stylesheet" href="asset/css/style.css">

</head>

<body><!--
<header>
 <a href="">
 <img src="../asset/img/logo.png" alt="logo">
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
 <div>

 </div>
 </header>-->

    <main>
        <?php
        include('connexionbdd.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $id = $_GET["id"];
            $qui = $_GET["qui"];

            foreach ($dbh->query("SELECT * from test.devis WHERE id_reservation =$id", PDO::FETCH_ASSOC) as $row) {
                $devis = $row;
            }
            foreach ($dbh->query("SELECT * from test.reservation WHERE id_reservation =$id", PDO::FETCH_ASSOC) as $row) {
                $reserv = $row;
            }
            foreach ($dbh->query("SELECT * from test.logement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {
                $info = $row;
            }
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
            foreach ($dbh->query("SELECT * from test.charges_selectionnees WHERE id_reservation =$id", PDO::FETCH_ASSOC) as $row) {
                $charge[$i] = $row;
                $i++;
            }
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }

        ?>
        <div class="demande">
            <div class="retour">
                <button class="boutonRetour" onclick="history.back()"><img src="icons/arrow_retour.svg"></button>

                <h1 class="h1-mobile">Demande de réservation</h1>
            </div>
            <div class="voyage">
                <h2 class="h2-mobile">Votre Voyage</h2>
                <div class="voyage_container">
                    <div class="date">
                        <div class="date_arrivee">

                            <svg xmlns="http://www.w3.org/2000/svg" width="75" height="60" viewBox="0 0 75 60" fill="none">
                                <path d="M36.8839 31.125H33.4018C32.8272 31.125 32.3571 30.5977 32.3571 29.9531V26.0469C32.3571 25.4023 32.8272 24.875 33.4018 24.875H36.8839C37.4585 24.875 37.9286 25.4023 37.9286 26.0469V29.9531C37.9286 30.5977 37.4585 31.125 36.8839 31.125ZM46.2857 29.9531V26.0469C46.2857 25.4023 45.8156 24.875 45.2411 24.875H41.7589C41.1844 24.875 40.7143 25.4023 40.7143 26.0469V29.9531C40.7143 30.5977 41.1844 31.125 41.7589 31.125H45.2411C45.8156 31.125 46.2857 30.5977 46.2857 29.9531ZM54.6429 29.9531V26.0469C54.6429 25.4023 54.1728 24.875 53.5982 24.875H50.1161C49.5415 24.875 49.0714 25.4023 49.0714 26.0469V29.9531C49.0714 30.5977 49.5415 31.125 50.1161 31.125H53.5982C54.1728 31.125 54.6429 30.5977 54.6429 29.9531ZM46.2857 39.3281V35.4219C46.2857 34.7773 45.8156 34.25 45.2411 34.25H41.7589C41.1844 34.25 40.7143 34.7773 40.7143 35.4219V39.3281C40.7143 39.9727 41.1844 40.5 41.7589 40.5H45.2411C45.8156 40.5 46.2857 39.9727 46.2857 39.3281ZM37.9286 39.3281V35.4219C37.9286 34.7773 37.4585 34.25 36.8839 34.25H33.4018C32.8272 34.25 32.3571 34.7773 32.3571 35.4219V39.3281C32.3571 39.9727 32.8272 40.5 33.4018 40.5H36.8839C37.4585 40.5 37.9286 39.9727 37.9286 39.3281ZM54.6429 39.3281V35.4219C54.6429 34.7773 54.1728 34.25 53.5982 34.25H50.1161C49.5415 34.25 49.0714 34.7773 49.0714 35.4219V39.3281C49.0714 39.9727 49.5415 40.5 50.1161 40.5H53.5982C54.1728 40.5 54.6429 39.9727 54.6429 39.3281ZM63 13.9375V48.3125C63 50.9004 61.1283 53 58.8214 53H28.1786C25.8717 53 24 50.9004 24 48.3125V13.9375C24 11.3496 25.8717 9.25 28.1786 9.25H32.3571V4.17188C32.3571 3.52734 32.8272 3 33.4018 3H36.8839C37.4585 3 37.9286 3.52734 37.9286 4.17188V9.25H49.0714V4.17188C49.0714 3.52734 49.5415 3 50.1161 3H53.5982C54.1728 3 54.6429 3.52734 54.6429 4.17188V9.25H58.8214C61.1283 9.25 63 11.3496 63 13.9375ZM58.8214 47.7266V18.625H28.1786V47.7266C28.1786 48.0488 28.4136 48.3125 28.7009 48.3125H58.2991C58.5864 48.3125 58.8214 48.0488 58.8214 47.7266Z" fill="#1D4C77" />
                            </svg>
                            <p><?php echo ($reserv["debut_reservation"]);  ?></p>
                        </div>
                        <div class="date_depart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="75" height="60" viewBox="0 0 75 60" fill="none">
                                <path d="M36.8839 31.125H33.4018C32.8272 31.125 32.3571 30.5977 32.3571 29.9531V26.0469C32.3571 25.4023 32.8272 24.875 33.4018 24.875H36.8839C37.4585 24.875 37.9286 25.4023 37.9286 26.0469V29.9531C37.9286 30.5977 37.4585 31.125 36.8839 31.125ZM46.2857 29.9531V26.0469C46.2857 25.4023 45.8156 24.875 45.2411 24.875H41.7589C41.1844 24.875 40.7143 25.4023 40.7143 26.0469V29.9531C40.7143 30.5977 41.1844 31.125 41.7589 31.125H45.2411C45.8156 31.125 46.2857 30.5977 46.2857 29.9531ZM54.6429 29.9531V26.0469C54.6429 25.4023 54.1728 24.875 53.5982 24.875H50.1161C49.5415 24.875 49.0714 25.4023 49.0714 26.0469V29.9531C49.0714 30.5977 49.5415 31.125 50.1161 31.125H53.5982C54.1728 31.125 54.6429 30.5977 54.6429 29.9531ZM46.2857 39.3281V35.4219C46.2857 34.7773 45.8156 34.25 45.2411 34.25H41.7589C41.1844 34.25 40.7143 34.7773 40.7143 35.4219V39.3281C40.7143 39.9727 41.1844 40.5 41.7589 40.5H45.2411C45.8156 40.5 46.2857 39.9727 46.2857 39.3281ZM37.9286 39.3281V35.4219C37.9286 34.7773 37.4585 34.25 36.8839 34.25H33.4018C32.8272 34.25 32.3571 34.7773 32.3571 35.4219V39.3281C32.3571 39.9727 32.8272 40.5 33.4018 40.5H36.8839C37.4585 40.5 37.9286 39.9727 37.9286 39.3281ZM54.6429 39.3281V35.4219C54.6429 34.7773 54.1728 34.25 53.5982 34.25H50.1161C49.5415 34.25 49.0714 34.7773 49.0714 35.4219V39.3281C49.0714 39.9727 49.5415 40.5 50.1161 40.5H53.5982C54.1728 40.5 54.6429 39.9727 54.6429 39.3281ZM63 13.9375V48.3125C63 50.9004 61.1283 53 58.8214 53H28.1786C25.8717 53 24 50.9004 24 48.3125V13.9375C24 11.3496 25.8717 9.25 28.1786 9.25H32.3571V4.17188C32.3571 3.52734 32.8272 3 33.4018 3H36.8839C37.4585 3 37.9286 3.52734 37.9286 4.17188V9.25H49.0714V4.17188C49.0714 3.52734 49.5415 3 50.1161 3H53.5982C54.1728 3 54.6429 3.52734 54.6429 4.17188V9.25H58.8214C61.1283 9.25 63 11.3496 63 13.9375ZM58.8214 47.7266V18.625H28.1786V47.7266C28.1786 48.0488 28.4136 48.3125 28.7009 48.3125H58.2991C58.5864 48.3125 58.8214 48.0488 58.8214 47.7266Z" fill="#1D4C77" />
                            </svg>
                            <p><?php echo ($reserv["fin_reservation"]);  ?></p>
                        </div>
                    </div>

                    <div class="personne">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" viewBox="0 0 30 40" fill="none">
                            <path d="M15 20C19.7344 20 23.5714 15.5234 23.5714 10C23.5714 4.47656 19.7344 0 15 0C10.2656 0 6.42857 4.47656 6.42857 10C6.42857 15.5234 10.2656 20 15 20ZM21 22.5H19.8817C18.3951 23.2969 16.7411 23.75 15 23.75C13.2589 23.75 11.6116 23.2969 10.1183 22.5H9C4.03125 22.5 0 27.2031 0 33V36.25C0 38.3203 1.43973 40 3.21429 40H26.7857C28.5603 40 30 38.3203 30 36.25V33C30 27.2031 25.9688 22.5 21 22.5Z" fill="#1D4C77" />
                        </svg>
                        <p><?php echo ($reserv["nb_personne"]);  ?> pers. (2 adultes, 0 enfants)</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="33" viewBox="0 0 38 33" fill="none">
                            <path d="M17.2393 24.4508C17.9366 25.1831 19.069 25.1831 19.7663 24.4508L30.477 13.203C31.1743 12.4707 31.1743 11.2815 30.477 10.5492C29.7797 9.81693 28.6473 9.81693 27.95 10.5492L18.5 20.4731L9.05004 10.5551C8.35273 9.82279 7.22029 9.82279 6.52298 10.5551C5.82567 11.2873 5.82567 12.4766 6.52298 13.2088L17.2337 24.4566L17.2393 24.4508Z" fill="#1D4C77" />
                        </svg>
                    </div>
                </div>



            </div>
            <div class="icons_info">


                <div class="type_logement">
                    <h2 class="h2-mobile">Type de logement</h2>
                    <div class="icons">
                        <div>
                            <img src="icons/appart_bleu.svg" alt="">
                            <p><?php echo ($info["nature_logement"]);  ?> </p>
                        </div>
                        <div>
                            <img src="icons/salon_bleu.svg" alt="">
                            <p>Salon</p>
                        </div>
                        <div>
                            <img src="icons/chambre_bleu.svg" alt="">
                            <p><?php echo ($info["nb_chambre"]);  ?> chambres</p>
                        </div>
                        <div>
                            <img src="icons/salle_bain_bleu.svg" alt="">
                            <p>Salle de bain</p>
                        </div>
                        <div>
                            <img src="icons/cusine_bleu.svg" alt="">
                            <p>Cusine</p>
                        </div>
                        <div>
                            <img src="icons/wifi_bleu.svg" alt="">

                            <p>Wi-fi comprise</p>
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
                                    <img src="icons/<?php echo ($value["nom_amenagement"]);  ?>.svg" alt="">
                                    <p><?php echo ($value["nom_amenagement"]);  ?></p>
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
                                    <img src="icons/<?php echo ($value["nom_installation"]);  ?>.svg" alt="">
                                    <p><?php echo ($value["nom_installation"]);  ?></p>
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
                                    <img src="icons/<?php echo ($value["nom_service"]);  ?>.svg" alt="">
                                    <p><?php echo ($value["nom_service"]);  ?></p>
                                </div>
                        <?php }
                        } ?>

                    </div>

                </div>




            </div>
            <div id="check_box_info">
                <h2 class="h2-mobile">Options supplémentaires</h2>
                <div id="input_check_box_info">
                    <label>
                        <input type="checkbox" name="menage" id="menage">
                        Ménage
                    </label>

                </div>
                <div id="input_check_box_info">
                    <label>
                        <input type="checkbox" name="animaux" id="animaux">
                        Animaux de compagnie
                    </label>
                </div>
                <p>En cas de détérioration du logement, toutes charges supplémentaires <br> seront vues directement avec
                    le
                    propriétaire</p>

            </div>

        </div>

        <div class="recap">
            <div class="sticky_recap">
                <div class="recap-info">
                    <div class="info_logoment">
                        <img src="../asset/test_img/<?php echo ($photo["photo"]["name"][0]); ?>" width="183px" height="124px">
                        <div>
                            <span>
                                <p class="info">Logement : <?php echo ($info["nature_logement"]) ?></p>
                            </span>
                            <span>
                                <p class="more_info"> <?php echo ($info["nature_logement"]) ?> <?php echo ($info["type_logement"]); ?>, <?php echo ($info["localisation"]);  ?></p>
                            </span>
                        </div>



                    </div>
                    <hr>
                    <div class="info_prix">

                        <div class="row">
                            <div class="label"><?php echo ($info["prix_base_ht"]);  ?>€ x 14 nuits</div>
                            <div class="value"><?php echo ($info["prix_base_ht"]) * 14;  ?>€</div>
                        </div>
                        <?php
                        if (empty($charge) == false) {

                            foreach ($charge as $elemnt => $value) { ?>
                                <div class="row">
                                    <div class="label"><?php echo ($value["nom_charge"]); ?></div>
                                    <div class="value">80€</div>
                                </div>
                        <?php }
                        } ?>
                        <div class="row">
                            <div class="label">Taxe de séjour</div>
                            <div class="value">29.96€</div>
                        </div>
                        <div class="row">
                            <div class="label">TVA</div>
                            <div class="value"><?php echo ($info["prix_base_ht"]) * 14 * 1.10 - ($info["prix_base_ht"]) * 14;  ?>€</div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="label_t">Total</div>
                            <div class="value">EUR <?php echo ($info["prix_base_ht"]) * 14 * 1.10 + 80 + 29.96 ?>€</div>
                        </div>
                    </div>



                </div><?php
                        if ($qui == null) {
                        ?>
                    <div class="button_valider">
                        <a href="http://localhost:8888/devis/demande.php?qui=proprietaire&id=1">Envoyer une demande de devis</a>
                    </div>
                <?php } ?>

                <?php
                if ($qui == "proprietaire") {
                ?>
                    <div class="button_valider">
                        <a href="http://localhost:8888/devis/demande.php?qui=client&id=1">Envoyer le de devis</a>
                    </div>
                    <div class="button_refuser">
                        <a href="#" onclick="afficherPopup()">Refuser le devis</a>
                    </div>


                    <div id="overlay"></div>
                    <div id="popup">
                        <p>Etes-vous sûr de vouloir refuser le devis ?</p>
                        <div class="button_confirmation">

                            <a href="#" id="annuler" onclick="cacherPopup()">Annuler</a>
                            <a href="#" id="confirmer" onclick="confirmerRefus()">Confirmer</a>
                        </div>
                    </div>
                <?php } ?>
                <?php
                if ($qui == "client") { ?>
                    <div class="button_valider">
                        <a href="https://www.youtube.com/watch?v=utcaWkuy7Ko">Payer le devis</a>
                    </div>
                    <div class="button_refuser">
                        <a href="#" onclick="afficherPopup()">Refuser le devis</a>
                    </div>


                    <div id="overlay"></div>
                    <div id="popup">
                        <p>Etes-vous sûr de vouloir refuser le devis ?</p>
                        <div class="button_confirmation">

                            <a href="#" id="annuler" onclick="cacherPopup()">Annuler</a>
                            <a href="#" id="confirmer" onclick="confirmerRefus()">Confirmer</a>
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


    </div><!--
    <footer>
 <div>
 <div id="footerCercleLogo">
 <img src="../asset/img/logo.png" alt="logo">
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
 </footer>-->
</body>

</html>