# LoQuali API cas d'utilisation
- Le serveur est lancé sur le port 8080 en verbose dans le fichier logs.txt
- Le client se connecte avec la bonne adresse IP et le bon port
- Il entre une mauvaise clé, et se retrouve déconnecté
- Il se reconnecte et entre une bonne clé
- Il peut désormais taper des commandes décrites dans le fichier d'aide client (ou taper help pour voir la liste des commandes disponibles)
- Si une commande retourne une erreur, elle sera signalée et affichée chez le client
- Il se déconnecte quand il veut avec la commande `exit`
- Le client se déconnecte du serveur et s'arrête, le serveur continue de tourner et attends une nouvelle connection