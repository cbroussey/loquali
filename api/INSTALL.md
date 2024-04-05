# Installation APIrator
- Créez un fichier .json de configuration pour le programme (voir FORMAT.md pour la configuration)
- Créez les dossiers nécessaires pour que l'APIrator mette ses donnés et fichiers de logs
- Ouvrez un terminal à l'endroit du programme et exécutez comme ceci : `./apirator chemin_du_fichier_de_config.json`
- Vérifiez le bon fonctionnement de votre configuration
- S'il n'y a pas d'erreur vous pouvez mettre en place un moyen d'automatisation tel que cron comme suit :
  - Dans un terminal, entrez `crontab -e`
  - Rendez vous tout en bas du fichier puis mettez en place votre ligne cron en utilisant la documentation suivante : https://doc.ubuntu-fr.org/cron
  - Quittez l'éditeur de texte en sauvegardant, vous voilà fin prêt