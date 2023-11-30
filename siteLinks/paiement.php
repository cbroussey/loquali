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
    <link rel="stylesheet" href="asset/css/CSSreset.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/paiement.css">
    <title>Paiement de la réservation</title>
</head>

<body>
    <?php include "header.php" ?>
    <?php
    //print_r(pdo_drivers());
    //print_r($_POST);
    if (isset($_POST["devis"]) && is_numeric($_POST["devis"])) {
        //include("../data/dbImport.php");
        require_once("connect_params.php");
        $db = new PDO("$driver:host=$server;dbname=$dbname", "$user", "$pass");
        $res = $db->prepare(
            'SELECT *, DATE_PART(\'day\', reservation.fin_reservation::timestamp - reservation.debut_reservation::timestamp) AS nbJours FROM test.devis
            JOIN test.reservation ON test.devis.id_reservation = test.reservation.id_reservation
            JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
            JOIN test.image ON test.logement.id_image_couv = test.image.id_image
            WHERE id_devis = :devis'
        );
        $res->bindParam('devis', $_POST['devis'], PDO::PARAM_INT);
        $res->execute();
        $res = $res->fetchAll();
        /*?><pre style="padding-left: 1em;"><?php print_r($res) ?></pre><?php*/
        if (isset($_SESSION["userId"]) && $_SESSION["userId"] == $res[0]["id_compte"] && $res && $res[0]["acceptation"]) {
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
            $pay = $db->prepare(
                'SELECT * FROM test.cb WHERE id_compte = :compte'
            );
            $pay->bindParam("compte", $_SESSION["userId"], PDO::PARAM_INT);
            ?>
            <form method="post" action="validPay.php" class="flexTop">
                <button class="backButton" onclick="history.back()"><img src="asset/img/arrow-down.svg"></button>
                <div id="paiement">
                    <div id="infosVoyage">
                        <h2>Votre voyage</h2>
                        <!-- <p><a class="h3">Dates</a><a><?php /*echo explode("-", $res["debut_reservation"])[2] . ($res["debut_reservation"][1] != $res["fin_reservation"][1] ? substr(strftime("%B"), 0, 3) . "." : "")?> - <?php echo explode("-", $res["debut_reservation"])[2] . ' ' . substr(strftime("%B"), 0, 3) . "."*/ ?> </a></p> -->
                        <p><a class="h3">Dates</a><a><?php echo explode("-", $res["debut_reservation"])[2] . "/" . explode("-", $res["debut_reservation"])[1]?> - <?php echo explode("-", $res["fin_reservation"])[2] . '/' . explode("-", $res["fin_reservation"])[1] ?> </a></p> <!-- PB avec juin et juillet : Jui et Jui -->
                        <p><a class="h3">Voyageurs</a><a><?php echo $res["nb_personne"] ?> voyageur<?php echo ($res["nb_personne"] > 1) ? "s" : " " ?></a></p>
                    </div>
                    <div id="infosPaiement">
                        <h2>Validez et payez</h2>
                        <p>Payez avec</p>
                        <div id="CM" class="contextMenu">
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/mastercard.png');" value="MasterCard" readonly><img class="cmHideElem mastercard" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">|
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/paypal.png');" value="PayPal" readonly><img class="cmHideElem paypal" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">
                        </div>
                        <div id="paymentType" href="#" onclick="toggleCM('CM', this)">
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/mastercard.png');" value="MasterCard" readonly><img class="cmHideElem" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">
                        </div>
                        <input id="cardNumber" name="cardNumber" placeholder="Numéro de carte" class="mastercard" pattern="^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$" required> <!-- Pattern actuel uniquement pour mastercard -->
                        <div>
                            <input id="expiry" name="expiry" placeholder="Expiration" minlength=5 maxlength=5 class="mastercard" pattern="(0[1-9]|1[0-2])/\d{2}" required>
                            <input id="crypto" name="crypto" placeholder="Cryptogramme" minlength=3 maxlength=3 class="mastercard" pattern="\d{3}" required>
                        </div>
                        <div style="display: none;">
                            <input id="postalCode" name="postalCode" placeholder="Code postal" minlength=2 maxlength=11 hidden>
                            <div id="country" style="display: none;"><input name="country" placeholder="Pays/région" onclick="toggleCM('CM2', this)"><img src="asset/img/arrow-down.svg"></div>
                            <div id="CM2" class="contextMenu">France|Amérique|Asie|Afrique|JSP</div>
                        </div>
                        <input id="paypalMail" name="paypalMail" class="paypal" placeholder="Adresse email PayPal" hidden>
                        <div id="input_check_box_info">
                            <input type="checkbox" name="savePay" id="savePay">
                            <label for="savePay">
                                Enregistrer le moyen de paiement
                            </label>
                        </div>
                    </div>
                </div>
                <div id="devis">
                    <figure>
                        <img src="asset/img/logements/<?php echo $res["id_image_couv"] ?>.<?php echo $res["extension_image"] ?>">
                        <figcaption><?php echo $res["descriptif"] ?><!--Appartement avec vue imprenable sur la mer--></figcaption>
                    </figure>
                    <div>
                        <p><a><?php echo $res["prix_base_ht"] ?>€ x <?php echo $res["nbjours"] ?> nuits</a><a><?php $prixFin = $res["prix_base_ht"] * $res["nbjours"]; echo $prixFin ?>€</a></p> <!-- prix incorrect, extraire le prix réel plus tard avec les plages -->
                        
                        <?php
                            foreach($charges as $charge) { ?>
                                <p><a><?php echo $charge["nom_charge"] ?></a><a><?php echo $charge["prix_charge"] ?>€</a></p>
                            <?php }
                        ?>
                        <p><a>Taxes</a><a><?php $tva = $prixFin*1/100; echo $tva ?>€</a></p>
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
    <?php include "footer.php" ?>
</body>
</html>