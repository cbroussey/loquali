# LoQuali client API
Ce client simple vous permet de vous connecter à distance à l'API LoQuali. Munissez vous de l'addresse IP, du port, et de la clé API communiquée par votre administrateur

## Connexion
Lancez le programme dans un terminal avec
```bash
./api_client ip_du_serveur port_du_serveur
```
en remplaçant bien évidemment les champs par l'adresse IP du serveur et son port.
Si le serveur est en fonctionnement et vous avez bien entré les informations, vous devrez atterrir sur un prompt vous demandant votre clé API
```
API key >
```
Vous pouvez dès à présent entrer votre clé que vous avez reçu, puis appuyez sur entrée. Si tout se passe bien, vous recevrez un message indiquant que vous êtes connectés avec succès et un nouveau prompt attendra vos commandes, sinon vous serez déconnecté. Si le programme bloque, appuyez sur CTRL+C

## Liste des commandes
Une fois que vous êtes connectés et avez validé votre clé, vous pouvez obtenir de l'aide sur les commandes disponible et leurs syntaxe à n'importe quel moment en tapant "help"
### list_all
Commande réservée uniquement aux utilisateurs privilégiés (demandez l'autorisation à l'administrateur du site) et qui permet de lister tous les logements.

Pas d'arguments nécessaires.

Sortie au format JSON
```JSON
{"id_logement":"nom_logement"}
```
id_logement et nom_logement sont des chaînes de caractères (id logement est un entier converti en chaîne de caractères)

### list
Permet de lister vos logements.

Pas d'arguments nécessaires.

Sortie au format JSON
```JSON
{"id_logement":"nom_logement"}
```
id_logement et nom_logement sont des chaînes de caractères (id logement est un entier converti en chaîne de caractères)

### planning
Nécessite la permission du propriétaire. Permet de voir le planning pour un logement sur une date/période donnée.

Au moins 2 arguments sont nécessaires : l'identifiant du logement et la première date. Un troisième argument optionel permet de sélectionner une période plutôt qu'une date unique. Format des arguments de type date : `AAAA-MM-JJ` où A correspond au chiffres de l'année, M du mois, et J du jour

Sortie au format JSON
```JSON
{"date":"disponible"}
```
date est une chaine de caractères formattée comme suit : `AAAA-MM-JJ` où A correspond au chiffres de l'année, M du mois, et J du jour

disponible est une chaine de caractère booléenne, elle ne peut posséder que 2 valeurs : `t` pour VRAI et `f` pour FAUX. Un logement est disponible si la valeur est à `t`
### disable
Nécessite la permission du propriétaire. Permet de mettre en indisponible un logement sur une date/période donnée.

Au moins 2 arguments sont nécessaires : l'identifiant du logement et la première date. Un troisième argument optionel permet de sélectionner une période plutôt qu'une date unique. Format des arguments de type date : `AAAA-MM-JJ` où A correspond au chiffres de l'année, M du mois, et J du jour

Sortie de confirmation de l'exécution correcte de la commande
```
OK
```
### help
Permet d'afficher la liste des commandes et leur syntaxe.

Pas d'arguments nécessaires.

Sortie affiche la liste des commandes disponibles et leur syntaxe
### exit
Permet de fermer la connexion et de quitter le programme client

Pas d'argument nécessaire

Pas de sortie, vous retournerez sur votre terminal normal