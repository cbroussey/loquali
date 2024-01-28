# Protocole
Le client tente de se connecter au serveur
Le serveur accepte la connexion
Et jusqu'à la déconnexion :
- Le serveur envoie du texte au client (ex: résultat d'une commande)
- Le client affiche le texte jusqu'à ce qu'il détecte le texte d'un prompt
- L'utilisateur entre sa commande dans le prompt
- Le client envoie la commande de l'utilisateur en brut
- Le serveur reçoie la commande de l'utilisateur et la traite
On boucle