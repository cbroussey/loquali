# Formattage du fichier de configuration APIrator
Le programme apirator n'accepte que les fichiers JSON contenant des paramètres au format "chaîne de caractères" (inclus également les potentiels nombres entiers tels que l'identifiant du logement)

Il doit comporter les clés et leurs valeurs associées suivantes :
- "key" pour votre clé API (obligatoire)
- "id" pour l'identifiant du bien dont vous souhaitez obtenir les données (obligatoire)
- "log_path" pour le chemin **absolu** du dossier vers les fichiers de logs de l'apirator (si non défini, aucun log ne sera enregistré)
- "data_path" pour le chemin **absolu** du dossier vers les fichiers de données récupérées (obligatoire)

Chacun de ces attributs peuvent être placés dans l'ordre qui vous convient mais le format type JSON et les guillemets pour chaque clé et chaque valeur sont obligatoires.

Les attributs non-cités dans ce fichier seront ignorés par le programme mais peuvent tout de même causer une erreur si mal formattés, pensez donc à n'écrire que le strict minimum.

Si une erreur survient dans la récupération des paramètres du fichier JSON, elle ne sera pas écrite dans le potentiel dossier de logs que vous aurez défini. Veuillez donc à tester votre fichier de configuration avec le programme via le terminal avant de mettre en place tout type d'automatisation (ex: cron)