#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <errno.h>
#include <stdlib.h>
#include <getopt.h>
#include <stdbool.h>
#include <fcntl.h>
#include <sys/stat.h>
#include <postgresql/libpq-fe.h>
#include <ctype.h>
#include <stdarg.h>
#include <time.h>

#define MAXCMD 1024 // Taille max de toutes les chaines de caractères

bool verbose = false;
int foutput = 0; // Fichier de sortie des logs si définit

// Fonction qui printf des logs à l'écran et dans un fichier en fonction de la variable verbose et du mode
// mode = 0 -> Affichage uniquement si mode verbose activé
// mode = 1 -> Affichage peut importe le mode verbose
// mode = 2 -> Affichage uniquement si mode verbose activé sans indication de temps (permet le formattage de certains affichages)
void printose(int mode, const char* format, ...) {
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);
    char txt[MAXCMD];
    char tmp[MAXCMD];
    va_list args;
    if (verbose || mode == 1) {
        va_start(args, format);
        vsnprintf(txt, sizeof(txt), format, args); // do check return value
        va_end(args);
        memset(tmp, 0, sizeof(tmp));
        sprintf(tmp, "[%02d/%02d/%d %02d:%02d:%02d] ", tm.tm_mday, tm.tm_mon + 1, tm.tm_year + 1900, tm.tm_hour, tm.tm_min, tm.tm_sec);
        printf("%s%s", (mode != 2 ? tmp : ""), txt);
        if (foutput) {
            if (mode != 2) write(foutput, tmp, strlen(tmp));
            write(foutput, txt, strlen(txt));
        }
    }
}

// Fonction de simplification en cas d'erreur dans le lancement (éviter la répétition)
void perrorOut() {
    printose(2, "Err: %s\n", strerror(errno));
    exit(1);
}

// Fonction qui affiche le résultat d'une requête SQL au format JSON
void writeTable(PGresult *res, int cnx) {
    int i;
    //int j, n;
    char cmd[MAXCMD];
    write(cnx, "{", 1);
    /*
    n = PQnfields(res);
    for (i = 0; i < n; i++) {
        memset(cmd, 0, strlen(cmd));
        sprintf(cmd, "%-15s ", PQfname(res, i));
        write(cnx, cmd, strlen(cmd));
    }
    write(cnx, "\r\n\r\n", 4);
    */
    // Affichage des lignes
    for (i = 0; i < PQntuples(res); i++) // Pour chaque ligne du résultat
    {
        //for (j = 0; j < n; j++) {
        memset(cmd, 0, strlen(cmd));
        sprintf(cmd, "\"%s\": \"%s\"%s", PQgetvalue(res, i, 0), PQgetvalue(res, i, 1), (i < PQntuples(res) - 1 ? "," : "")); // Formattage en JSON : "id_logement": "nom"
        write(cnx, cmd, strlen(cmd));
        //}
        //write(cnx, "\r\n", 2);
    }
    write(cnx, "}\n", 2);
    return;
}

// Fonction qui permet de récupérer les arguments d'une commande reçue via le socket
int getArgs(char cmd[MAXCMD], char *cmdargs[]) {
    char tmp[MAXCMD];
    int i, j, k;

    memset(tmp, 0, sizeof(tmp));
    j = 0;
    k = 0;
    for (i = 0; cmd[i] != '\0'; i++) {
        if (cmd[i] != ' ') {
            tmp[j] = cmd[i];
            j++;
        } else {
            //printf("arg: %s\n", tmp);
            cmdargs[k] = malloc(strlen(tmp) + 1);
            strcpy(cmdargs[k], tmp);
            //printf("ok : %s\n", cmdargs[k]);
            memset(tmp, 0, j);
            j = 0;
            k++;
        }
    }
    //printf("arg: %s\n", tmp);
    cmdargs[k] = malloc(strlen(tmp) + 1);
    strcpy(cmdargs[k], tmp);
    //printf("ok : %s\n", cmdargs[k]);
    memset(tmp, 0, j);
    //printf("%d\n", i);
    return k+1;
}

// Fonction qui permet de vider la liste des arguments
void emptyArgs(int argc, char* cmdargs[]) {
    int i;
    for (i = 0; i < argc; i++) {
        if (cmdargs[i] != NULL) {
            free(cmdargs[i]);
            cmdargs[i] = NULL;
        }
    }
}

// Fonction principale
int main(int argc, char *argv[]) {
    int sock, ret;
    struct sockaddr_in addr;
    int size;
    int cnx;
    int i;
    struct sockaddr_in conn_addr;
    size = (int)sizeof(conn_addr);
    char c; // Chaque caractère des commandes reçues
    char cmd[MAXCMD]; // Commande reçue en entier
    char tmp[(int)MAXCMD/2]; // Variable temporaire de manipulation de chaînes de caractères
    //int ping;
    int port;
    bool connected = false;
    int accID; // Identifiant du compte
    char accNom[256]; // Nom du compte
    bool accPriv; // Compte privilégié ?
    bool accCalend; // Peut afficher le calendrier ?
    bool accDesact; // Peut mettre un logement en indisponible ?
    bool accReact; // Peut mettre un logement en disponible ?
    char* cmdargs[MAXCMD]; // Arguments de la commande reçue

    PGconn *db; // BDD
    PGresult *res; // Retour de reqûete SQL

    // Vidage des variables chaînes de caractères
    memset(cmd, 0, sizeof(cmd));
    memset(tmp, 0, sizeof(tmp));


    opterr = 0; // Désactive le message d'erreur par défaut de getopt
    while (1) { // (c = getopt(argc, argv, "ah")) != -1
        int option_index = 0;
        static struct option long_options[] = {
            {"verbose", 2,    0,  'v'}, // 2 = optional_argument
            //{"output",  1,    0,  'o'}, // 1 = required_argument
            {"help",    0,    0,  'h'}, // 0 = no_argument
            {0,         0,    0,  0}
        };
        c = getopt_long(argc, argv, "v::h", long_options, &option_index); // o:
        if (c == -1)
            break;

        //printf("là c'est %d et %c\n", optopt, c);
        switch (c) {
            case 0:
                /*
                printf("option %s", long_options[option_index].name);
                if (optarg)
                    printf(" with arg %s", optarg);
                printf("\n");
                */
                break;
            case 'v':
                verbose = true;
                if (optarg && strlen(optarg) > 0) { // Si on a un argument pour le fichier de sortie
                    printf("Selected output file for verbose : %s\n", optarg);
                    foutput = open(optarg, O_WRONLY | O_APPEND | O_CREAT, 0644);
                }
                break;
            /*case 'o':
                printf("Selected output file : %s\n", optarg);
                break;*/
            default:
                switch (optopt) {
                    /*case 'o':
                        printf("Missing required argument for option -%c : file_name\n", optopt);
                        break;*/
                    case 0:
                        if (c == '?') {
                            printf("Unknown option: %s\n", argv[optind-1]);
                            break;
                        }
                    default:
                        printf("Unknown option: -%c\n", optopt ? optopt : c);
                }
                // Pas de break pcq on veut afficher l'aide
            case 'h':
                printf("Usage: %s [options] port\n", argv[0]);
                printf("Options:\n");
                printf("  -v, --verbose[=FILE]\tPrints out detailed logs on the console (and in the specified file)\n");
                //printf("  -o, --output=FILE     selected output file\n");
                printf("  -h, --help\t\tDisplay this help and exit\n");
                return 0;
        }
    }

    //for (i = optind; i < argc; i++) printf ("Non-option argument : %s\n", argv[i]);
    if (optind >= argc) {
        printf("Missing required argument : port\n");
        return 1;
    } else if (atoi(argv[optind]) <= 1024 || atoi(argv[optind]) > 65535) {
        printf("Invalid port number : %s\n", argv[optind]);
        return 1;
    }
    port = atoi(argv[optind]);
    if (verbose) printf("==== Verbose mode ====\n");
    printose(false, "Port : %d\n", atoi(argv[optind]));
    //return 0;

    // Partie BDD
    db = PQconnectdb("host=localhost dbname=sae user=sae password=roh9oCh4xahj3tae"); // Connection à la BDD

    if (PQstatus(db) != CONNECTION_OK) { // Vérification de la connexion
        printose(true, "DB init error: %s", PQerrorMessage(db));
        PQfinish(db);
        close(foutput);
        return 1;
    }


    // Partie Socket
    printose(true, "Starting server...\n");

    printose(false, "Socket init...\t");
    sock = socket(AF_INET, SOCK_STREAM, 0);
    if (sock == -1) perrorOut();
    printose(2, "%d\n", sock);

    printose(false, "Socket ip :\t");
    addr.sin_addr.s_addr = htonl(INADDR_ANY); // Accepter toutes les adresses entrantes
    if (addr.sin_addr.s_addr == -1) perrorOut();
    printose(2, "%d\n", addr.sin_addr.s_addr);

    printose(false, "Socket family :\t");
    addr.sin_family = AF_INET;
    printose(2, "%d\n", addr.sin_family);

    printose(false, "Socket port :\t");
    addr.sin_port = htons(port);
    printose(2, "%d\n", addr.sin_port);

    printose(false, "Socket bind...\t");
    ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));
    if (ret == -1) perrorOut();
    printose(2, "%d\n", ret);

    while (1) {
        connected = false;

        while (!connected) {
            printose(true, "Server started successfully\n");
            printose(false, "Socket listen... ");
            ret = listen(sock, 1);
            if (ret == -1) perrorOut();
            printose(2, "%d\n", ret);

            printose(true, "Waiting for connection...\n");
            printose(false, "Socket accept... ");
            cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size); // Attente de demande de connexion
            if (cnx == -1) perrorOut();
            printose(2, "%d\n", cnx);

            printose(true, "%s Connected\n", inet_ntoa(conn_addr.sin_addr));
            
            //read(cnx, &c, 1024);
            //printf("%s", c);
            memset(tmp, 0, strlen(tmp)); // Vider la variable tmp
            write(cnx, "API key > \x03", 11);
            printose(true, "Waiting for API key...\n");
            ret = read(cnx, &tmp, sizeof(tmp));
            if (ret == -1) perrorOut();
            // On garde uniquement la clé et on enlève les caractères superflus
            while (tmp[strlen(tmp) - 1] == '\n' || tmp[strlen(tmp) - 1] == '\r') tmp[strlen(tmp) - 1] = '\0';
            printose(false, "Key received : %s\n", tmp);
            memset(cmd, 0, strlen(cmd)); // Vider la variable cmd
            // Requête sql qui récupère toutes les infos en fonction de la clé API reçue
            sprintf(cmd, "SELECT cle,test.api.id_compte,privilegie,accesCalendrier,miseIndispo,nom_affichage FROM test.api INNER JOIN test.compte on test.api.id_compte = test.compte.id_compte WHERE cle = '%s';", tmp);
            //printf("%s\n", cmd);
            res = PQexec(db, cmd);
            if (PQresultStatus(res) != PGRES_TUPLES_OK) { // Cas d'erreur
                printose(true, "DB error: %s\nDisconnect\n", PQerrorMessage(db));
                memset(cmd, 0, strlen(cmd));
                sprintf(cmd, "DB error: %s\r\n\x04", PQerrorMessage(db));
                write(cnx, cmd, strlen(cmd));
                close(cnx);
            } else if (PQntuples(res) > 0 && !strcmp(tmp, PQgetvalue(res, 0, 0))) { //strncmp(&cmd, "0123456789ABCDEF", 16) == 0
                //memset(cmd, 0, strlen(cmd));
                //sprintf(cmd, "SELECT FROM test.api WHERE cle = '%.16s';", tmp);
                accID = atoi(PQgetvalue(res, 0, 1));
                accPriv = (PQgetvalue(res, 0, 2)[0] == 't');
                accCalend = (PQgetvalue(res, 0, 3)[0] == 't');
                accDesact = (PQgetvalue(res, 0, 4)[0] == 't');
                accReact = accDesact;
                strcpy(accNom, PQgetvalue(res, 0, 5));
                printose(true, "Valid API key : %s\n", accNom);
                memset(cmd, 0, strlen(cmd));
                sprintf(cmd, "Authentication successful. Welcome %s\r\n", accNom);
                write(cnx, cmd, strlen(cmd));
                write(cnx, "Type \"help\" to get a list of available commands\r\n", 49);
                write(cnx, "LoQuali > \x03", 11); // Le prompt affiché au client
                connected = true;
            } else { // Pas de tuples retournés donc pas de résultat donc clé API erronnée. On déconnecte
                write(cnx, "Invalid API key\r\n\x04", 18);
                printose(true, "Invalid API key. Disconnect\n");
                close(cnx);
                //return 1;
            }
        }

        memset(cmd, 0, sizeof(cmd));
        printose(true, "Reading commands...\n");

        //c = ' ';
        i = 0;
        //ping = 0;
        while(strcmp(cmd, "exit\r")) { // le read() > 0 n'a pas l'air de faire grand chose, et le strcmp non plus pcq on vide cmd avant chaque utilisation
            read(cnx, &c, 1);
            //printf("%d ", c);
            if (i < MAXCMD && c != '\r' && c != '\n') { // Ici i sert à limiter la commande reçue pour empêcher une segfault
                //printf("%c", c);
                cmd[i] = c;
                i++;
            }
            if ((c == '\r' || c == '\n') && i) { // Quand on reçoit un caractère de fin de ligne et que la commande n'est pas vide
                printose(false, "%s > %s\n", inet_ntoa(conn_addr.sin_addr), cmd); // Log de la commande
                i = getArgs(cmd, cmdargs); // Ici i sert à compter le nombre d'arguments dans la commande (nom de la commande inclus)
                if (1 == 0/*strcmp(cmdargs[0], "hello") == 0*/) {
                    //printf("Writing...\n");
                    //write(cnx, "._.\r\n", 5);
                /*} else if (strcmp(cmd, "ping") == 0) {
                    //printf("Writing...\n");
                    ping++;
                    memset(cmd, 0, strlen(cmd)); // Pas nécessaire car la longueur de la chaine est plus grande que "PING" au final
                    sprintf(cmd, "PONG N°%d\r\n", ping); // Mais ça permet d'être plus propre au niveau mémoire
                    write(cnx, cmd, strlen(cmd));*/
                } else if (strcmp(cmdargs[0], "list_all") == 0) {
                    //printf("Writing...\n");
                    if (accPriv) {
                        res = PQexec(db, "SELECT id_logement, libelle_logement FROM test.logement;");
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                            printose(true, cmd);
                            write(cnx, cmd, strlen(cmd));
                        } else {
                            //write(cnx, "Liste de tous les biens :\r\n", 27);

                            // Affichage des attributs
                            writeTable(res, cnx);
                        }
                        PQclear(res); // Vidage du résultat d'une potentielle requête SQL pour éviter les fuites de mémoire
                    } else {
                        write(cnx, "You do not have permission to execute that command\r\n", 52);
                    }
                } else if (strcmp(cmdargs[0], "list") == 0) {
                    //printf("Writing...\n");
                    memset(cmd, 0, strlen(cmd));
                    sprintf(cmd, "SELECT id_logement, libelle_logement FROM test.logement where id_compte = %d;", accID);
                    res = PQexec(db, cmd);
                    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                        memset(cmd, 0, strlen(cmd));
                        sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                        printose(true, cmd);
                        write(cnx, cmd, strlen(cmd));
                    } else {
                        //write(cnx, "Liste de tous les biens :\r\n", 27);

                        // Affichage des attributs
                        writeTable(res, cnx);
                    }
                    PQclear(res); // Vidage du résultat d'une potentielle requête SQL pour éviter les fuites de mémoire
                } else if (strcmp(cmdargs[0], "planning") == 0) {
                    if (accCalend) {
                        //i = getArgs(cmd, cmdargs);
                        //printf("\n%d, %s, %s\n", i, cmdargs[0], cmdargs[1]);
                        //printf("%d arguments\n", i);
                        if (i >= 3) { // nom de la commande + 2 arguments
                            if (i >= 4) { // Si il y a le troisième argument pour sélectionner une plage
                                memset(tmp, 0, strlen(tmp));
                                sprintf(tmp, " AND jour <= '%s'", cmdargs[3]);
                            }
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "SELECT jour, disponibilite FROM test.planning where id_logement = %d AND jour %s= '%s'%s;", atoi(cmdargs[1]), (i >= 4 ? ">" : ""), cmdargs[2], (i >= 4 ? tmp : ""));
                            res = PQexec(db, cmd);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                printose(true, cmd);
                                write(cnx, cmd, strlen(cmd));
                            } else {
                                // Affichage des attributs
                                writeTable(res, cnx);
                            }
                            PQclear(res);
                        } else {
                            write(cnx, "Not enough arguments\r\nUsage : planning <id> <start-date> [end-date]\r\n", 69);
                        }
                    } else {
                        write(cnx, "You do not have permission to execute that command\r\n", 52);
                    }
                } else if (strcmp(cmdargs[0], "disable") == 0) {
                    if (accDesact) {
                        //i = getArgs(cmd, cmdargs);
                        //printf("\n%d, %s, %s\n", i, cmdargs[0], cmdargs[1]);
                        //printf("%d arguments\n", i);
                        if (i >= 3) { // nom de la commande + 2 ou 3 arguments
                            if (i >= 4) {// Si il y a le troisième argument pour sélectionner une plage
                                memset(tmp, 0, strlen(tmp));
                                sprintf(tmp, ",'%s'", cmdargs[3]);
                            }
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "SELECT * FROM test.setEtatLogement(%d,FALSE,'%s'%s)", atoi(cmdargs[1]), cmdargs[2], (i >= 4 ? tmp : ""));
                            res = PQexec(db, cmd);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                printose(true, cmd);
                                write(cnx, cmd, strlen(cmd));
                            } else {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "%s\n", PQgetvalue(res, 0, 0));
                                write(cnx, cmd, strlen(cmd));
                            }
                            PQclear(res);
                        } else {
                            write(cnx, "Not enough arguments\r\nUsage : disable <id> <start-date> [end-date]\r\n", 60);
                        }
                    } else {
                        write(cnx, "You do not have permission to execute that command\r\n", 52);
                    }
                } else if (strcmp(cmdargs[0], "enable") == 0) {
                    if (accReact) {
                        //i = getArgs(cmd, cmdargs);
                        //printf("\n%d, %s, %s\n", i, cmdargs[0], cmdargs[1]);
                        //printf("%d arguments\n", i);
                        if (i >= 3) { // nom de la commande + 2 ou 3 arguments
                            if (i >= 4) {// Si il y a le troisième argument pour sélectionner une plage
                                memset(tmp, 0, strlen(tmp));
                                sprintf(tmp, ",'%s'", cmdargs[3]);
                            }
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "SELECT * FROM test.setEtatLogement(%d,TRUE,'%s'%s)", atoi(cmdargs[1]), cmdargs[2], (i >= 4 ? tmp : ""));
                            res = PQexec(db, cmd);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                printose(true, cmd);
                                write(cnx, cmd, strlen(cmd));
                            } else {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "%s\n", PQgetvalue(res, 0, 0));
                                write(cnx, cmd, strlen(cmd));
                            }
                            PQclear(res);
                        } else {
                            write(cnx, "Not enough arguments\r\nUsage : enable <id> <start-date> [end-date]\r\n", 59);
                        }
                    } else {
                        write(cnx, "You do not have permission to execute that command\r\n", 52);
                    }
                } else if (strcmp(cmdargs[0], "help") == 0) {
                    //printf("Writing...\n");
                    write(cnx, "Available commands :\r\n", 22);
                    //write(cnx, "  hello\r\n", 9);
                    //write(cnx, "  ping\r\n", 8);
                    if (accPriv) write(cnx, "  list_all\r\n", 12);
                    write(cnx, "  list\r\n", 8);
                    if (accCalend) write(cnx, "  planning <id> <start-date> [end-date]\r\n", 41);
                    if (accDesact) write(cnx, "  disable <id> <start-date> [end-date]\r\n", 40);
                    if (accReact) write(cnx, "  enable <id> <start-date> [end-date]\r\n", 39);
                    write(cnx, "  help\r\n", 8);
                    write(cnx, "  exit\r\n", 8);
                } else if (strcmp(cmdargs[0], "exit") == 0) {
                    //printf("Writing...\n");
                    write(cnx, "Goodbye\r\n\x04", 10);
                    close(cnx);
                    break;
                } else {
                    //printf("Writing...\n");
                    write(cnx, "Unknown command\r\n", 17);
                }
                //printf("Done\n");
                //printf("%ld\n", strlen(cmd));
                //printf("aaaa\n");
                memset(cmd, 0, strlen(cmd)); // Plutôt qu'une boucle for
                emptyArgs(i, cmdargs);
                write(cnx, "LoQuali > \x03", 11); // Le prompt affiché au client
                //printf(" > ");
                i = 0;
            }
        }
        close(cnx);
        printose(true, "Disconnected\n");
    }
    //printf("Writing...\n");
    //write(cnx, "Moi aussi j'aime les ananas\r\n", 29);
    //printf("Done\n");
    //PQclear(res);
    PQfinish(db);
    close(foutput);
    return 0;
}
