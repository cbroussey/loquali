<?php
    function emailIsInDB($email) {
        include('connect_params.php');

        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $query = "SELECT * FROM test.compte WHERE test.compte.adresse_mail = :email";
            
            $stmt = $dbh->prepare($query);
            $stmt->bindParam('email', $email, PDO::PARAM_STR);
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
    if (isset($_POST['nom']) && ($_POST['passwordInput'] == $_POST['passwordInput2']) && !emailIsInDB($_POST['email'])) {

        session_start();
        include('connect_params.php');

        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
            //préparation de la requête d'insertion d'un nouveau compte dans la base de donnée
            $insert = "INSERT INTO test.compte (mdp, nom_affichage, date_creation, nom, prenom, adresse_mail) VALUES (:mdp, :nom_affichage, :date_creation, :nom, :prenom, :adresse_mail)";
            $stmt = $dbh->prepare($insert);

            //hashage du mot de passe avant l'insertion dans la base de donnée
            $hash = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
            print_r("$hash");

            $nomAffichage = $_POST['prenom'] . " " . $_POST['nom'];

            //binding des données dans la requête
            $stmt->bindParam(':mdp', $hash, PDO::PARAM_STR);
            $stmt->bindParam(':nom_affichage', $nomAffichage, PDO::PARAM_STR);
            $stmt->bindParam(':date_creation', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $stmt->bindParam(':adresse_mail', $_POST['email'], PDO::PARAM_STR);

            //exécution de la requête d'insertion
            $stmt->execute();

            //initialise les variables de session
            $query = "SELECT id_compte FROM test.compte WHERE test.compte.adresse_mail = :email";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':email', $_POST["email"], PDO::PARAM_STR);
            $stmt->execute();
            $post = $stmt->fetch();

            $_SESSION['userId'] = $post['id_compte']; 
            $_SESSION['displayName'] = $nomAffichage;
            $_SESSION['name'] = $_POST['nom'];
            $_SESSION['surname'] = $_POST['prenom'];
            $_SESSION['email'] = $_POST['adresse_mail'];
            $_SESSION['userType'] = 'client';

            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }

        header("Location: isOwner.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" type="text/css" href="asset/css/style.css">
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
            <a href="index.php">
                <button type="button">
                    <svg width="42" height="43" viewBox="0 0 42 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.2386 21.5L32.3459 14.3928C33.218 13.5206 33.218 12.1065 32.3459 11.2337L30.7663 9.65412C29.8942 8.78196 28.4801 8.78196 27.6072 9.65412L20.5 16.7614L13.3928 9.65412C12.5206 8.78196 11.1065 8.78196 10.2337 9.65412L8.65412 11.2337C7.78196 12.1058 7.78196 13.5199 8.65412 14.3928L15.7614 21.5L8.65412 28.6072C7.78196 29.4794 7.78196 30.8935 8.65412 31.7663L10.2337 33.3459C11.1058 34.218 12.5206 34.218 13.3928 33.3459L20.5 26.2386L27.6072 33.3459C28.4794 34.218 29.8942 34.218 30.7663 33.3459L32.3459 31.7663C33.218 30.8942 33.218 29.4801 32.3459 28.6072L25.2386 21.5Z" fill="#F5F5F5"/>
                    </svg>
                </button>
            </a>
            <img src="asset/img/logo.png" alt="logo">
            <form method="post">
                <?php
                if (($_POST['passwordInput'] != $_POST['passwordInput2']) || (emailIsInDB($_POST['email']))) {
                    ?>
                    <input type="text" id="nom" name="nom" placeholder="Nom" value=<?php echo htmlentities($_POST['nom']) ?> required />
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom" value=<?php echo htmlentities($_POST['prenom']) ?> required />
                    <input type="email" id="email" name="email" placeholder="Adresse-mail" value=<?php echo htmlentities($_POST['email']) ?> required/>    
                    <?php
                    if (emailIsInDB($_POST['email'])) {
                        ?>
                        <p class="invalidInput">Un compte existe déjà avec cette adresse mail.</p>
                        <?php
                    }
                    ?>
                    <div class="surroundCasePassWord">
                        <input type="password" id="passwordInput" placeholder="Mot de passe" name="passwordInput" autocomplete="current-password" required/>
                        <img id="eyesPasswordVisible" src="asset/icons/bleu/eye-open.svg" alt="eye">              
                    </div>
                    <div class="surroundCasePassWord">
                        <input type="password" id="passwordInput2" placeholder="Confirmez le mot de passe" name="passwordInput2" autocomplete="current-password" required/>
                        <img id="eyesPasswordVisible2" src="asset/icons/bleu/eye-open.svg" alt="eye">              
                    </div>
                    <?php
                    if ($_POST['passwordInput'] != $_POST['passwordInput2']) {
                        ?>
                        <p class="invalidInput">Les mots de passe ne sont pas identiques !</p>
                        <?php
                    }

                } else {
                    ?>
                    <input type="text" id="nom" name="nom" placeholder="Nom" required />
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom" required />
                    <input type="email" id="email" name="email" placeholder="Adresse-mail" required/>    
                    <div class="surroundCasePassWord">
                        <input type="password" id="passwordInput" placeholder="Mot de passe" name="passwordInput" autocomplete="current-password" required/>
                        <img id="eyesPasswordVisible" src="asset/icons/bleu/eye-open.svg" alt="eye">              
                    </div>
                    <div class="surroundCasePassWord">
                        <input type="password" id="passwordInput2" placeholder="Confirmez le mot de passe" name="passwordInput2" autocomplete="current-password" required/>
                        <img id="eyesPasswordVisible2" src="asset/icons/bleu/eye-open.svg" alt="eye">              
                    </div>
                    <?php
                }
                ?>
                <input type="submit" value="Suivant"/>
            </form>
            <p>En créant ou en vous connectant à un compte, vous acceptez nos <a href="">conditions générales</a> et notre <a href="">charte de confidentialité</a>.</p>
        </section>
    </main>
    <script src="asset/js/createAccount.js"></script>
</body>
</html>