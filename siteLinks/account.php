<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
</head>
<body>
    <?php
        session_start();

        // Vérifie si l'utilisateur est déjà connecté
        if (isset($_SESSION['username'])) {
            echo 'Utilisateur connecté : ' . $_SESSION['username'];
        } else {
            echo 'Aucun utilisateur connecté.';
        }

        // Si le bouton de déconnexion est cliqué, déconnecte l'utilisateur
        if (isset($_POST['deconnexion'])) {
            session_destroy();
            header("Location: index.php"); // Redirige vers la page de connexion
        }
    ?>

    <!-- Affiche le bouton de déconnexion -->
    <form method="post">
        <button type="submit" name="deconnexion">Déconnexion</button>
    </form>
</body>
</html>

    
</body>
</html>