<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSSreset.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="paiement.css">
    <title>Paiement de la réservation</title>
</head>
<body>
    <?php
    //print_r(pdo_drivers());
    //print_r($_POST);
    if (isset($_POST["devis"]) && is_numeric($_POST["devis"])) {
        include("../data/dbImport.php");
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
                <button class="backButton" onclick="history.back()"><img src="images/arrow-down.svg"></button>
                <div id="paiement">
                    <div id="infosVoyage">
                        <h2>Votre voyage</h2>
                        <p><a class="h3">Dates</a><a>19 - 24 oct.</a></p>
                        <p><a class="h3">Voyageurs</a><a><?php echo $res["nb_personne"] ?> voyageur<?php echo ($res["nb_personne"] > 1) ? "s" : " " ?></a></p>
                    </div>
                    <div id="infosPaiement">
                        <h2>Validez et payez</h2>
                        <p>Payez avec</p>
                        <div id="paymentType"><img src="images/mastercard.png"><a>MasterCard</a><img src="images/arrow-down.svg"></div>
                        <input id="cardNumber" placeholder="Numéro de carte" pattern="^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$" required> <!-- Pattern actuel uniquement pour mastercard -->
                        <div>
                            <input id="expiry" placeholder="Expiration" minlength=5 maxlength=5 pattern="(0[1-9]|1[0-9]|3[01])/(0[1-9]|1[0-2])" required>
                            <input id="crypto" placeholder="Cryptogramme" minlength=3 maxlength=3 pattern="\d{3}" required>
                        </div>
                        <div>
                            <input id="postalCode" placeholder="Code postal" minlength=2 maxlength=11 required>
                            <div id="country"><a>Pays/région</a><img src="images/arrow-down.svg"></div>
                        </div>
                    </div>
                </div>
                <div id="devis">
                    <figure>
                        <img src="images/appart.png">
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
</body>
</html>
