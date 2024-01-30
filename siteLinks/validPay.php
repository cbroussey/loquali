<?php
    session_start();
    error_reporting(0);
    //echo date("m-y") ."\n";
    if (
        (/*isset($_POST["paymentType"]) && $_POST["paymentType"] == "MasterCard" && */preg_match('/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/', $_POST["cardNumber"]))
        && intval(explode("/", $_POST["expiry"])[1]) > intval(date("y")) || (intval(explode("/", $_POST["expiry"])[1]) == intval(date("y")) && intval(explode("/", $_POST["expiry"])[0]) > intval(date("m")))
        && strlen($_POST["crypto"]) == 3
        ) {
            //echo "aoihaefaehfoaifaàera,";
            require_once("connect_params.php");
            $db = new PDO("$driver:host=$server;dbname=$dbname", "$user", "$pass");
            $res = $db->prepare(
                'SELECT * FROM test.devis JOIN test.reservation ON test.devis.id_reservation = test.reservation.id_reservation WHERE id_devis = :num;'
            );
            $res->bindParam('num', $_POST['devis'], PDO::PARAM_INT);
            $res->execute();
            $res = $res->fetchAll();
            if (isset($_SESSION["userId"]) && $_SESSION["userId"] == $res[0]["id_compte"] && $res && !$res[0]["acceptation"]) {
                $res = $db->prepare(
                    'UPDATE test.devis SET acceptation = TRUE WHERE id_devis = :num;'
                );
                $res->bindParam('num', $_POST['devis'], PDO::PARAM_INT);
                $res->execute();
                $res = $db->prepare(
                    'SELECT * FROM test.cb WHERE id_compte = :compte AND numero_carte = :num;'
                );
                $res->bindParam('num', $_POST['cardNumber'], PDO::PARAM_STR);
                $res->bindParam('compte', $_SESSION['userId'], PDO::PARAM_INT);
                $res->execute();
                $res = $res->fetchAll();
                if (count($res) == 0 && isset($_POST["savePay"])) {
                    $res = $db->prepare(
                        'INSERT INTO test.cb VALUES (:typecb, :num, DATE(:validite), :crypto, :compte);'
                    );
                    $validiteFDP = "01/" . $_POST['expiry'];
                    $res->bindParam('typecb', "MasterCard", PDO::PARAM_STR);
                    $res->bindParam('num', $_POST['cardNumber'], PDO::PARAM_STR);
                    $res->bindParam('validite', $validiteFDP, PDO::PARAM_STR);
                    $res->bindParam('crypto', $_POST['crypto'], PDO::PARAM_STR);
                    $res->bindParam('compte', $_SESSION['userId'], PDO::PARAM_INT);
                    $res->execute();
                } else if (count($res) > 0 && (!isset($_POST["savePay"]) || $_POST["savePay"] === "off")) {
                    $res = $db->prepare(
                        'DELETE FROM test.cb WHERE numero_carte = :num AND id_compte = :compte;'
                    );
                    $res->bindParam('num', $_POST['cardNumber'], PDO::PARAM_STR);
                    $res->bindParam('compte', $_SESSION['userId'], PDO::PARAM_INT);
                    $res->execute();
                }
                header("Location: index.php");
            } else {
                die('403 -Forbidden');
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/CSSreset.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/paiement.css">
    <title>Devis</title>
</head>
<body>
    <?php
        
    ?>
    <pre><?php print_r($_POST) ?></pre>
    <form method="post" action="paiement.php" class="flexTop">
        <button class="backButton" onclick="history.back()"><img src="asset/img/arrow-down.svg"></button>
        <input name="devis" placeholder="Numéro de devis">
        <button type="submit">Payer</button>
    </form>
</body>
</html>