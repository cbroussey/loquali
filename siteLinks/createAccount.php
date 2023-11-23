<?php
    if (isset($_POST['nom'])) {

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
            <a href="index.html">
                <button type="button">
                    <svg width="42" height="43" viewBox="0 0 42 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.2386 21.5L32.3459 14.3928C33.218 13.5206 33.218 12.1065 32.3459 11.2337L30.7663 9.65412C29.8942 8.78196 28.4801 8.78196 27.6072 9.65412L20.5 16.7614L13.3928 9.65412C12.5206 8.78196 11.1065 8.78196 10.2337 9.65412L8.65412 11.2337C7.78196 12.1058 7.78196 13.5199 8.65412 14.3928L15.7614 21.5L8.65412 28.6072C7.78196 29.4794 7.78196 30.8935 8.65412 31.7663L10.2337 33.3459C11.1058 34.218 12.5206 34.218 13.3928 33.3459L20.5 26.2386L27.6072 33.3459C28.4794 34.218 29.8942 34.218 30.7663 33.3459L32.3459 31.7663C33.218 30.8942 33.218 29.4801 32.3459 28.6072L25.2386 21.5Z" fill="#F5F5F5"/>
                    </svg>
                </button>
            </a>
            <img src="asset/img/logo.png" alt="logo">
            <form method="post">
                <input type="text" id="nom" name="nom" placeholder="Nom" required />
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required />
                <input type="email" id="email" name="email" placeholder="Adresse-mail" required/>
                <div class="surroundCasePassWord">
                    <input type="password" id="motdepasse" placeholder="Mot de passe" name="motdepasse" required/>
                    <svg width="57" height="43" viewBox="0 0 57 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.6838 3.96289C13.1875 3.96289 4 20.9629 4 20.9629C4 20.9629 13.1875 37.9629 28.6838 37.9629C43.8125 37.9629 53 20.9629 53 20.9629C53 20.9629 43.8125 3.96289 28.6838 3.96289ZM28.5 9.62956C35.2987 9.62956 40.75 14.7296 40.75 20.9629C40.75 27.2529 35.2987 32.2962 28.5 32.2962C21.7625 32.2962 16.25 27.2529 16.25 20.9629C16.25 14.7296 21.7625 9.62956 28.5 9.62956ZM28.5 15.2962C25.1313 15.2962 22.375 17.8462 22.375 20.9629C22.375 24.0796 25.1313 26.6296 28.5 26.6296C31.8687 26.6296 34.625 24.0796 34.625 20.9629C34.625 20.3962 34.38 19.8862 34.2575 19.3762C33.7675 20.2829 32.7875 20.9629 31.5625 20.9629C29.8475 20.9629 28.5 19.7162 28.5 18.1296C28.5 16.9962 29.235 16.0896 30.215 15.6362C29.6638 15.4662 29.1125 15.2962 28.5 15.2962Z" fill="#1D4C77"/>
                    </svg>                        
                </div>
                <div class="surroundCasePassWord">
                    <input type="password" id="confirmMotDePasse" placeholder="Confirmer le mot de passe" name="confirmMotDePasse" required/>
                    <svg width="57" height="43" viewBox="0 0 57 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.6838 3.96289C13.1875 3.96289 4 20.9629 4 20.9629C4 20.9629 13.1875 37.9629 28.6838 37.9629C43.8125 37.9629 53 20.9629 53 20.9629C53 20.9629 43.8125 3.96289 28.6838 3.96289ZM28.5 9.62956C35.2987 9.62956 40.75 14.7296 40.75 20.9629C40.75 27.2529 35.2987 32.2962 28.5 32.2962C21.7625 32.2962 16.25 27.2529 16.25 20.9629C16.25 14.7296 21.7625 9.62956 28.5 9.62956ZM28.5 15.2962C25.1313 15.2962 22.375 17.8462 22.375 20.9629C22.375 24.0796 25.1313 26.6296 28.5 26.6296C31.8687 26.6296 34.625 24.0796 34.625 20.9629C34.625 20.3962 34.38 19.8862 34.2575 19.3762C33.7675 20.2829 32.7875 20.9629 31.5625 20.9629C29.8475 20.9629 28.5 19.7162 28.5 18.1296C28.5 16.9962 29.235 16.0896 30.215 15.6362C29.6638 15.4662 29.1125 15.2962 28.5 15.2962Z" fill="#1D4C77"/>
                    </svg>                  
                </div>
                <input type="submit" value="Suivant"/>
            </form>
            <p>En créant ou en vous connectant à un compte, vous acceptez nos <a href="">conditions générales</a> et notre <a href="">charte de confidentialité</a>.</p>
        </section>
    </main>
</body>
</html>