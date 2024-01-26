<?php
    session_start();
    $mailInconnu = false;
    $inconnu = false;

    if (isset($_POST['email'])) {
        include('connect_params.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $query = "SELECT * FROM test.compte WHERE test.compte.adresse_mail = :email";
            
            $stmt = $dbh->prepare($query);
            $stmt->bindParam('email', $_POST["email"], PDO::PARAM_STR);
            $stmt->execute();
            $post = $stmt->fetch();

            if ($post == null) {
                $inconnu = true;
            } else { 
                if (password_verify($_POST['passwordInput'], $post['mdp'])) {
                    
                    $_SESSION['userId'] = $post['id_compte']; 
                    $_SESSION['displayName'] = $post['nom_affichage'];
                    $_SESSION['name'] = $post['nom'];
                    $_SESSION['surname'] = $post['prenom'];
                    $_SESSION['email'] = $post['adresse_mail'];

                    //récupération du type d'utilisateur (client, proprietaire)
                    $query = "SELECT id_compte FROM test.proprietaire WHERE test.proprietaire.id_compte = :idCompte";
            
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam('idCompte', $post['id_compte'], PDO::PARAM_STR);
                    $stmt->execute();
                    $post = $stmt->fetch();

                    if ($post == null) {
                        $_SESSION['userType'] = 'client';
                    } else {
                        $_SESSION['userType'] = 'proprietaire';
                    }

                    header("Location: index.php");
                    exit;
                } else {
                    $inconnu = true;
                }
            }

            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" type="text/css" href="asset/css/style.css">
    <title>Connexion</title>
</head>
<body>
    <main id="connexion">
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
                if ($inconnu) {
                    ?>
                    <input type="email" id="email" name="email" placeholder="Adresse-mail" value=<?php echo $_POST['email'] ?>>
                    <div>
                        <input type="password" id="passwordInput" placeholder="Mot de passe" name="passwordInput" autocomplete="current-password" required/>
                        <img id="eyesPasswordVisible" src="asset/icons/bleu/eye-open.svg" alt="eye">                
                    </div>
                    <p class="invalidInput">Adresse mail  ou mot de passe incorrect !</p>
                    <?php
                } else {
                    ?>
                    <input type="email" id="email" name="email" placeholder="Adresse-mail"/>
                    <div>
                        <input type="password" id="passwordInput" placeholder="Mot de passe" name="passwordInput" required/>
                        <img id="eyesPasswordVisible" src="asset/icons/bleu/eye-open.svg" alt="eye">              
                    </div>
                    <?php
                }
                ?>
                <a id="forbiddenPassword" href=""><h3>Mot de passe oublié ?</h3></a>
                <input type="submit" value="Se connecter"/>
            </form>
            <p>En créant ou en vous connectant à un compte, vous acceptez nos <a href="cgu.php">conditions générales d'utilisation</a> et notre <a href="">charte de confidentialité</a>.</p>
        </section>

        <section>
            <h1>Bonjour ! Demat !</h1>
            <h2>Inscrivez-vous et réservez votre logement de rêve</h2>
            <button><a href="createAccount.php"><h2>Créer un compte</h2></a></button>
        </section>
    </main>

    <script src="asset/js/connexion.js"></script>
</body>
</html>