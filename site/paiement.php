<?php
    session_start();
    error_reporting(0);
    $linkAccount = 'connexion.php';
    if (isset($_SESSION['username'])) {
        $linkAccount = 'account.php';
    }
?>

<!DOCTYPE html>
<html lang="fr">
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
    if (isset($_POST["devis"]) && is_numeric($_POST["devis"])) { // Check si un numéro de devis a correctement été reçu
        //include("../data/dbImport.php");
        require_once("connect_params.php");
        $db = new PDO("$driver:host=$server;dbname=$dbname", "$user", "$pass");
        $res = $db->prepare(
            'SELECT *, DATE_PART(\'day\', reservation.fin_reservation::timestamp - reservation.debut_reservation::timestamp) AS nbJours, test.reservation.id_compte AS resa_id_compte
            FROM test.devis
            JOIN test.reservation ON test.devis.id_reservation = test.reservation.id_reservation
            JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
            JOIN test.image ON test.logement.id_image_couv = test.image.id_image
            WHERE id_devis = :devis'
        ); // Récupération des informations sur la réservation, le devis, le logement et l'image de couverture
        // nbJours, calculé dans la requête SELECT, correspond à la durée de la réservation
        $res->bindParam('devis', $_POST['devis'], PDO::PARAM_INT);
        $res->execute();
        $res = $res->fetchAll();
        /*?><pre style="padding-left: 1em;"><?php print_r($res) ?></pre><?php*/
        // Vérification d'accès au paiement (si l'utilisateur connecté est le bon et le devis a été accepté)
        if (isset($_SESSION["userId"]) && $_SESSION["userId"] == $res[0]["resa_id_compte"] && $res && !$res[0]["acceptation"]) {
            $res = $res[0];
            $charges = $db->prepare( // Fait après la vérification d'identitée pour éviter une requête potentiellement inutile
                'SELECT test.charges_selectionnees.nom_charge, test.prix_charge.prix_charge FROM test.reservation
                INNER JOIN test.prix_charge ON test.reservation.id_logement = test.prix_charge.id_logement
                INNER JOIN test.charges_selectionnees ON test.reservation.id_reservation = test.charges_selectionnees.id_reservation
                WHERE test.charges_selectionnees.id_reservation = :idres AND test.prix_charge.id_logement = :idlog'
            ); // Récupération des charges additionnelles sélectionnées pour la réservation
            $charges->bindParam('idres', $res["id_reservation"], PDO::PARAM_INT);
            $charges->bindParam('idlog', $res["id_logement"], PDO::PARAM_INT);
            $charges->execute();
            $charges = $charges->fetchAll();
            $pay = $db->prepare(
                'SELECT * FROM test.cb WHERE id_compte = :compte'
            ); // Récupération des cartes bancaires enregistrées sur le compte
            $pay->bindParam("compte", $_SESSION["userId"], PDO::PARAM_INT);
            $pay->execute();
            $pay = $pay->fetchAll();
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
                        <div id="CM3" class="contextMenu">
                            <?php
                                if (count($pay)) { // Si le compte possède des cartes bancaires enregistrées
                                    for ($i = 0; $i < count($pay); $i++) {
                                        // On ajoute à la liste chaque élément nécessaire (numéro de carte, cryptogramme, date de validité) + la flèche pour la décoration
                                        ?><input name="paymentSaved" class="inputImg" onclick="toggleCM('CM3', document.querySelector('#paymentSaved'))" style="background-image: url('asset/img/<?php echo strtolower($pay[$i]["type_cb"]) ?>.png');" value="<?php echo $pay[$i]["numero_carte"] ?>" readonly>
                                        <img class="cmHideElem <?php echo strtolower($pay[$i]["type_cb"]) ?>" src="asset/img/arrow-down.svg" onclick="toggleCM('CM3', document.querySelector('#paymentSaved'))">
                                        <input type="hidden" class="cryptenr" value="<?php echo $pay[$i]["cryptogramme"] ?>">
                                        <input type="hidden" class="validenr" value="<?php echo $pay[$i]["date_validite"] ?>"><?php
                                        echo ($i < count($pay) - 1 ? "|" : ""); // Séparation de chaque carte bancaire enregistrée par un |
                                    }
                                } else {
                                    ?><p class="disabled">Aucun moyen de paiement enregistré</p><?php
                                }
                            ?>
                        </div>
                        <div id="paymentSaved" href="#" onclick="toggleCM('CM3', this)">
                            <input name="paymentSaved" class="inputImg" onclick="toggleCM('CM3', document.querySelector('#paymentSaved'))" value="Nouveau mode de paiement" readonly><img class="cmHideElem" src="asset/img/arrow-down.svg" onclick="toggleCM('CM3', document.querySelector('#paymentSaved'))">
                        </div>
                        <!--
                        <div id="CM" class="contextMenu">
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/mastercard.png');" value="MasterCard" readonly><img class="cmHideElem mastercard" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">|
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/paypal.png');" value="PayPal" readonly><img class="cmHideElem paypal" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">
                        </div>
                        <div id="paymentType" href="#" onclick="toggleCM('CM', this)">
                            <input name="paymentType" class="inputImg" onclick="toggleCM('CM', document.querySelector('#paymentType'))" style="background-image: url('asset/img/mastercard.png');" value="MasterCard" readonly><img class="cmHideElem" src="asset/img/arrow-down.svg" onclick="toggleCM('CM', document.querySelector('#paymentType'))">
                        </div>
                        -->
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
                        <p><a><?php echo $res["nbjours"] ?> nuits</a><a><?php $prixFin = $res["prix_base_ht"] * $res["nbjours"]; echo $res["prix_devis"] ?>€</a></p> <!-- prix incorrect, extraire le prix réel plus tard avec les plages -->
                        
                        <?php
                            /*
                            // Affichage des charges additionnelles sélectionnées
                            foreach($charges as $charge) { ?>
                                <p><a><?php echo $charge["nom_charge"] ?></a><a><?php echo $charge["prix_charge"] ?>€</a></p>
                            <?php }
                            */
                        ?>
                        <p><a>Taxes</a><a><?php $tva = round($res["prix_devis"]*10/100, 2); echo $tva ?>€</a></p>
                    </div>
                    <div><p><a class="h3">Total</a><a>EUR</a><a class="h3"><?php echo $res["prix_devis"] + $tva ?>€</a></p></div>
                    <button type="submit">Payer</button>
                </div>
                <input type='hidden' name='devis' value='<?php echo $_POST["devis"] ?>'>
            </form>
        <?php } else {
            // Si la vérification d'identitée/de validation n'a pas réussi
            ?><h1 class="HTTPstatus">403 - Forbidden</h1><?php
        }
    } else {
        // Si aucun numéro de devis ou un numéro de devis invalide a été fourni
        ?><h1 class="HTTPstatus">400 - Bad Request</h1><?php
    } ?>
    <script src="asset/js/contextMenu.js"></script>
    <script src="asset/js/paiement.js"></script>
    <?php include "footer.php" ?>
</body>
</html>