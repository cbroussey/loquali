# LoQuali API

Bienvenue sur le document de présentation de l'API LoQuali. Vous retrouverez ici nombre d'informations concernant la compilation et l'exécution du programme, ansi que les arguments et la grammaire.

## Compilation

Installez gcc si ce n'est pas déjà fait
```bash
sudo su
apt-get update
apt-get install build-essential
```
Puis installez libpq, la librairie postgresql utilisée en C
```bash
apt-get install libpq-dev
```

Changez de répertoire pour arriver dans celui où le programme brut est présent
Puis, compilez avec
```bash
gcc api.c -o api -Wall -lpq
```
Vérifiez que vous possèdez bien les droits d'exécution
```bash
chmod +rx ./api
```
Et enfin exécutez le programme en indiquant le port du serveur que vous souhaitez
```bash
./api port_du_serveur
```

## Les arguments
La syntaxe réelle des arguments du programme est
```bash
./api [options] port
```
Vous pouvez obtenir la liste des options compatibles à tout moment en faisant
```bash
./api --help
```
### Verbose
Afficher plus d'informations à l'écran et dans un fichier si spécifié. Exemple :
```bash
./api --verbose 8080
```
Lancera le serveur sur le port 8080 et affichera plus d'informations dans la console. Et
```bash
./api --verbose=logs.txt 8080
```
Fera la même chose mais écrira en plus ces même logs dans le fichier logs.txt (fichier ouvert en ajout, pas de vidage avant écriture)