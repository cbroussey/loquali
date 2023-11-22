<?php
    session_start();
    $linkAccount = 'connexion.php';
    if (isset($_SESSION['username'])) {
        $linkAccount = 'account.php';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/CSSreset.css">
    <link rel="stylesheet" href="asset/css/paiement.css">
    <title>Paiement de la réservation</title>
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
        <div>

        </div>
    </header>
    <?php
    //print_r(pdo_drivers());
    //print_r($_POST);
    if (isset($_POST["devis"]) && is_numeric($_POST["devis"])) {
        //include("../data/dbImport.php");
        $db = new PDO(`$driver:host=$server; dbname=$dbname', '$user', '$pass`);
        $res = $db->prepare(
            'SELECT * FROM test.devis
            JOIN test.reservation ON test.devis.id_reservation = test.reservation.id_reservation
            JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
            WHERE id_devis = :devis'
        );
        $res->bindParam('devis', $_POST['devis'], PDO::PARAM_INT);
        $res->execute();
        $res = $res->fetchAll();
        print_r($res);
        /*
            SELECT test.charges_selectionnees.nom_charge, test.prix_charge.prix_charge FROM test.reservation
            INNER JOIN test.prix_charge ON test.reservation.id_logement = test.prix_charge.id_logement
            INNER JOIN test.charges_selectionnees ON test.charges_selectionnees.nom_charge = test.prix_charge.nom_charge
            WHERE test.charges_selectionnees.id_reservation = 1 AND test.prix_charge.id_logement = 1
        */
        if ($res && $res[0]["acceptation"]) {
            $res = $res[0];
            $charges = $db->prepare(
                'SELECT test.charges_selectionnees.nom_charge, test.prix_charge.prix_charge FROM test.reservation
                INNER JOIN test.prix_charge ON test.reservation.id_logement = test.prix_charge.id_logement
                INNER JOIN test.charges_selectionnees ON test.charges_selectionnees.nom_charge = test.prix_charge.nom_charge
                WHERE test.charges_selectionnees.id_reservation = :idres AND test.prix_charge.id_logement = :idlog'
            );
            $charges->bindParam('idres', $res["id_reservation"], PDO::PARAM_INT);
            $charges->bindParam('idlog', $res["id_logement"], PDO::PARAM_INT);
            $charges->execute();
            $charges = $charges->fetchAll();
            
            ?>
            <form method="post" action="paiement.php" class="flexTop">
                <button class="backButton" onclick="history.back()"><img src="asset/img/arrow-down.svg"></button>
                <div id="paiement">
                    <div id="infosVoyage">
                        <h2>Votre voyage</h2>
                        <p><a class="h3">Dates</a><a>19 - 24 oct.</a></p>
                        <p><a class="h3">Voyageurs</a><a><?php echo $res["nb_personne"] ?> voyageur<?php echo ($res["nb_personne"] > 1) ? "s" : " " ?></a></p>
                    </div>
                    <div id="infosPaiement">
                        <h2>Validez et payez</h2>
                        <p>Payez avec</p>
                        <!--<div id="paymentType" name="paymentType" href="#" onclick="toggleCM('CM', this)">
                            <input onclick="toggleCM('CM', this.parentElement)" readonly>
                        </div>-->
                        <div id="paymentType" name="paymentType" href="#" onclick="toggleCM('CM', this)">
                            <input class="inputImg" onclick="toggleCM('CM', this.parentElement)" style="background-image: url('asset/img/mastercard.png');" readonly>
                        </div>
                        <div id="CM" class="contextMenu">
                            <input class="inputImg" onclick="toggleCM('CM', this.parentElement)" style="background-image: url('asset/img/mastercard.png');" value="MasterCard" readonly><img src="asset/img/arrow-down.svg">|
                            <input class="inputImg" onclick="toggleCM('CM', this.parentElement)" style="background-image: url('asset/img/paypal.png');" value="PayPal" readonly><img src="asset/img/arrow-down.svg">
                        </div>
                        <!-- <div id="paymentType"><img src="asset/img/mastercard.png"><a>MasterCard</a><img src="asset/img/arrow-down.svg"></div> -->
                        <input id="cardNumber" placeholder="Numéro de carte" pattern="^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$" required> <!-- Pattern actuel uniquement pour mastercard -->
                        <div>
                            <input id="expiry" placeholder="Expiration" minlength=5 maxlength=5 pattern="(0[1-9]|1[0-9]|3[01])/(0[1-9]|1[0-2])" required>
                            <input id="crypto" placeholder="Cryptogramme" minlength=3 maxlength=3 pattern="\d{3}" required>
                        </div>
                        <div>
                            <input id="postalCode" placeholder="Code postal" minlength=2 maxlength=11 required>
                            <div id="country"><a>Pays/région</a><img src="asset/img/arrow-down.svg"></div>
                        </div>
                    </div>
                </div>
                <div id="devis">
                    <figure>
                        <img src="asset/img/appart.png">
                        <figcaption><?php echo $res["descriptif"] ?><!--Appartement avec vue imprenable sur la mer--></figcaption>
                    </figure>
                    <div>
                        <p><a>320€ x 3 nuits</a><a>960€</a></p>
                        
                        <?php
                            foreach($charges as $charge) { ?>
                                <p><a><?php echo $charge["nom_charge"] ?></a><a><?php echo $charge["prix_charge"] ?>€</a></p>
                            <?php }
                        ?>
                        <p><a>Taxes</a><a>20€</a></p>
                    </div>
                    <div><p><a class="h3">Total</a><a>EUR</a><a class="h3"><?php echo $res["prix_devis"] ?>€</a></p></div>
                    <button type="submit">Payer</button>
                </div>
            </form>
        <?php } else {
            ?><h1 class="HTTPstatus">403 - Forbidden</h1><?php
        }
    } else {
        ?><h1 class="HTTPstatus">400 - Bad Request</h1><?php
    } ?>
    <script src="asset/js/contextMenu.js"></script>
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