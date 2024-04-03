<?php
error_reporting(0);
    if (isset($_POST['type'])) {
        if ($_POST['type'] == 'client') {
            if(isset($_GET['log'])) {
                $idredir = $_GET['log'];
                header("Location: logement.php?id=$idredir#AvisSection");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            session_start();
            try {
                include('connect_params.php');
                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
                //préparation des requêtes d'insertion d'un compte propriétaire
                $insert = "INSERT INTO test.proprietaire (id_compte) VALUES (:idCompte)";
                $stmt = $dbh->prepare($insert);
                $stmt->bindParam(':idCompte', $_SESSION['userId'], PDO::PARAM_STR);
                //exécution de la requête d'insertion
                $stmt->execute();
                $dbh = null;
                
            } catch (PDOException $e) {
                print "Erreur : " . $e->getMessage() . "<br/>";
                die();
            }
            header("Location: createOwner.php");
            exit; 
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" type="text/css" href="asset/css/style.css">
    <link rel="stylesheet" type="text/css" href="asset/css/createAndConnect.css">
    <title>Créer votre compte</title>
</head>
<body>
    <main id="creation">
        <section>
            <h1>Bonjour ! Demat !</h1>
            <h2>Connectez-vous et réservez votre logement de rêve</h2>
            <a href="connexion.php"><button><h2>Se connecter</h2></button></a>
        </section>

        <section>
        <a href="<?php if(isset($_GET['log'])) {
                    ?>logement.php?id=<?php
                    echo($_GET['log']);
                    ?>#AvisSection<?php 
                } else {
                    ?>createAccount.php<?php
                    }?>">
                <button type="button">
                    <svg width="42" height="43" viewBox="0 0 42 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.2386 21.5L32.3459 14.3928C33.218 13.5206 33.218 12.1065 32.3459 11.2337L30.7663 9.65412C29.8942 8.78196 28.4801 8.78196 27.6072 9.65412L20.5 16.7614L13.3928 9.65412C12.5206 8.78196 11.1065 8.78196 10.2337 9.65412L8.65412 11.2337C7.78196 12.1058 7.78196 13.5199 8.65412 14.3928L15.7614 21.5L8.65412 28.6072C7.78196 29.4794 7.78196 30.8935 8.65412 31.7663L10.2337 33.3459C11.1058 34.218 12.5206 34.218 13.3928 33.3459L20.5 26.2386L27.6072 33.3459C28.4794 34.218 29.8942 34.218 30.7663 33.3459L32.3459 31.7663C33.218 30.8942 33.218 29.4801 32.3459 28.6072L25.2386 21.5Z" fill="#F5F5F5"/>
                    </svg>
                </button>
            </a>
            <img src="asset/img/logo.png" alt="logo Loquali">
            <form id="formIsOwner" method="post">
                <div>
                    <div>
                        <input type="radio" id="owner" name="type" value="owner" />
                        <label for="owner"><h2>Créer un compte propriétaire</h2></label>
                    </div>
                    <div>
                        <input type="radio" id="client" name="type" value="client" />
                        <label for="client"><h2>Créer un compte client</h2></label>
                    </div>
                </div>
                <input id="confirmIsOwner" type="submit" value="Suivant"/>
            </form>
            <p>En créant ou en vous connectant à un compte, vous acceptez nos <a href="cgu.php">conditions générales d'utilisation</a> et notre <a href="">charte de confidentialité</a>.</p>
        </section>
    </main>
</body>
</html>