<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSSreset.css">
    <link rel="stylesheet" href="paiement.css">
    <title>Devis</title>
</head>
<body>
    <pre><?php print_r($_POST) ?></pre>
    <form method="post" action="paiement.php" class="flexTop">
        <button class="backButton" onclick="history.back()"><img src="images/arrow-down.svg"></button>
        <input name="devis" placeholder="Numéro de devis">
        <button type="submit">Payer</button>
    </form>
</body>
</html>