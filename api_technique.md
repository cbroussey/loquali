# Protocole
Le client tente de se connecter au serveur

Le serveur accepte la connexion

Et jusqu'à la déconnexion (`\x04` = EOT):
- Le serveur envoie du texte au client (ex: résultat d'une commande/prompt de commande ou de clé API)
- Le client affiche le texte jusqu'à ce qu'il détecte la fin du texte (`\x03` = ETX)
- L'utilisateur entre sa commande dans le prompt
- Le client envoie la commande de l'utilisateur en brut
- Le serveur reçoie la commande de l'utilisateur et la traite

Une fois déconnecté