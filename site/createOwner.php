<?php
error_reporting(0);

function emailIsInDB($numero) {
    include('connect_params.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $query = "SELECT * FROM test.telephone WHERE test.telephone.numero = :numero";
        
        $stmt = $dbh->prepare($query);
        $stmt->bindParam('numero', $numero, PDO::PARAM_STR);
        $stmt->execute();
        $post = $stmt->fetch();

        if ($post == null) {
            return false;
        } else {
            return true; 
        }
    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
} 
    if (isset($_POST['pays']) && !emailIsInDB($_POST['telephone'])) {
        try {
            session_start();
            include('connect_params.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            //requêtes d'insertion d'un compte propriétaire
            $numtel = str_replace(' ', '', $_POST['telephone']);
            $insert = "INSERT INTO test.telephone (numero, id_compte) VALUES (:num, :idCompte)";
            $stmt = $dbh->prepare($insert);
            $stmt->bindParam(':num', $numtel, PDO::PARAM_STR);
            $stmt->bindParam(':idCompte', $_SESSION['userId'], PDO::PARAM_STR);
            $stmt->execute();

            $insert = "UPDATE test.compte SET ville = :ville, adresse = :adresse, code_postal = :codePostal WHERE id_compte = :userId;";
            $stmt = $dbh->prepare($insert);
            $stmt->bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
            $stmt->bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            $stmt->bindParam(':codePostal', $_POST['codePostal'], PDO::PARAM_STR);
            $stmt->bindParam(':userId', $_SESSION['userId']);
            $stmt->execute();

            $delete = "DELETE FROM test.client WHERE id_compte = :idCompte";
            $idC = $_SESSION['userId'];
            $stmt = $dbh->prepare($delete);
            $stmt->bindParam(':idCompte', $idC, PDO::PARAM_STR);
            $stmt->execute();

            $dbh = null;

            //enregistrement de l'image de la carte d'identité
            $imageName = $_SESSION['userId'] . "." . explode('/', $_FILES["fichier"]['type'])[1];
            move_uploaded_file($_FILES["fichier"]["tmp_name"], "asset/img/profils/$imageName");

            //changement du userType dans les variables de session
            $_SESSION['userType'] = 'proprietaire';

            //redirection vers la page d'accueil
            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
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
                } else {
                    ?>index.php<?php
                    }?>">
                <button type="button">
                    <svg width="42" height="43" viewBox="0 0 42 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.2386 21.5L32.3459 14.3928C33.218 13.5206 33.218 12.1065 32.3459 11.2337L30.7663 9.65412C29.8942 8.78196 28.4801 8.78196 27.6072 9.65412L20.5 16.7614L13.3928 9.65412C12.5206 8.78196 11.1065 8.78196 10.2337 9.65412L8.65412 11.2337C7.78196 12.1058 7.78196 13.5199 8.65412 14.3928L15.7614 21.5L8.65412 28.6072C7.78196 29.4794 7.78196 30.8935 8.65412 31.7663L10.2337 33.3459C11.1058 34.218 12.5206 34.218 13.3928 33.3459L20.5 26.2386L27.6072 33.3459C28.4794 34.218 29.8942 34.218 30.7663 33.3459L32.3459 31.7663C33.218 30.8942 33.218 29.4801 32.3459 28.6072L25.2386 21.5Z" fill="#F5F5F5"/>
                    </svg>
                </button>
            </a>
            <img src="asset/img/logo.png" alt="logo Loquali">
            <form method="post" enctype="multipart/form-data">
                <input type="text" id="pays" name="pays" placeholder="Pays" value="<?php echo htmlentities($_POST['pays']) ?>" required />
                <input type="text" id="ville" name="ville" placeholder="Ville" value="<?php echo htmlentities($_POST['ville']) ?>" required />
                <input type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?php echo htmlentities($_POST['adresse']) ?>" required />
                <!-- <input type="text" id="codePostal" name="codePostal" placeholder="Code Postal" required /> -->
                <input type="tel" id="telephone" name="telephone" placeholder="Numéro de tel." value="<?php echo htmlentities($_POST['telephone']) ?>" required />
                <?php
                    if (emailIsInDB($_POST['telephone'])) {
                    ?>
                    <p class="invalidInput">Un compte existe déjà avec ce numéro de téléphone.</p>
                    <?php
                }
                ?>
                <input type="file" id="fichier" name="fichier" accept="image/*" required/>
                <input type="submit" value="Créer votre compte"/>
            </form>
            <p>En créant ou en vous connectant à un compte, vous acceptez nos <a href="cgu.php">conditions générales d'utilisation</a> et notre <a href="">charte de confidentialité</a>.</p>
        </section>
    </main>
    <script src="asset/js/createOwner.js"></script>
    <script src="asset/js/rectifIfoCreateOwner.js"></script>

</body>
</html>