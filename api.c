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

#define MAXCMD 1024

void perrorOut() {
    printf("err: %s\n", strerror(errno));
    exit(1);
}

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

void emptyArgs(int argc, char* cmdargs[]) {
    int i;
    for (i = 0; i < argc; i++) {
        if (cmdargs[i] != NULL) {
            free(cmdargs[i]);
            cmdargs[i] = NULL;
        }
    }
}

char* strToUpper(char * lower) {
  char * name;
  name = strtok(lower,":");

  // Convert to upper case
  char *s = name;
  while (*s) {
    *s = toupper((unsigned char) *s);
    s++;
  }
  return (char*)s;
}

int main(int argc, char *argv[]) {
    int sock, ret;
    struct sockaddr_in addr;
    int size;
    int cnx;
    int i;
    struct sockaddr_in conn_addr;
    size = (int)sizeof(conn_addr);
    char c;
    char cmd[MAXCMD];
    char tmp[(int)MAXCMD/2];
    int ping;
    bool verbose = false;
    int foutput; // Fichier de sortie des logs si définit
    int port;
    bool connected = false;
    int accID; // Identifiant du compte
    char accNom[256]; // Nom du compte
    bool accPriv; // Compte privilégié ?
    bool accCalend; // Peut afficher le calendrier ?
    bool accDesact; // Peut mettre un logement en indisponible ?
    char* cmdargs[MAXCMD];

    PGconn *db;
    PGresult *res;

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
                printf("option %s", long_options[option_index].name);
                if (optarg)
                    printf(" with arg %s", optarg);
                printf("\n");
                break;
            case 'v':
                verbose = true;
                if (optarg && strlen(optarg) > 0) {
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
                        fprintf(stderr, "Unknown option: -%c\n", optopt ? optopt : c);
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
    printf("Port : %d\n", atoi(argv[optind]));
    if (verbose) printf("verbose mode\n");
    //return 0;

    // Partie BDD
    db = PQconnectdb("host=localhost dbname=sae user=sae password=roh9oCh4xahj3tae"); // Connection à la BDD

    if (PQstatus(db) != CONNECTION_OK) { // Vérification de la connexion
        fprintf(stderr, "DB init error: %s", PQerrorMessage(db));
        PQfinish(db);
        close(foutput);
        return 1;
    }


    // Partie Socket
    printf("Socket init...\t ");
    sock = socket(AF_INET, SOCK_STREAM, 0);
    if (sock == -1) perrorOut();

    printf("%d\nSocket ip :\t ", sock);
    addr.sin_addr.s_addr = inet_addr("127.0.0.1"); // INADDR_ANY;
    if (addr.sin_addr.s_addr == -1) perrorOut();

    printf("%d\nSocket family :\t ", addr.sin_addr.s_addr);
    addr.sin_family = AF_INET;

    printf("%d\nSocket port :\t ", addr.sin_family);
    addr.sin_port = htons(port);

    printf("%d\nSocket bind...\t ", addr.sin_port);
    ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));
    if (ret == -1) perrorOut();
    while (1) {
        connected = false;

        while (!connected) {
            printf("%d\nSocket listen... ", ret);
            ret = listen(sock, 1);
            if (ret == -1) perrorOut();

            printf("%d\nSocket accept... ", ret);
            cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
            if (cnx == -1) perrorOut();

            printf("%d\n", cnx);
            //read(cnx, &c, 1024);
            //printf("%s", c);

            memset(tmp, 0, strlen(tmp)); // Vider la variable tmp
            write(cnx, "API key > ", 10);
            printf("Waiting for API key...\n");
            ret = read(cnx, &tmp, sizeof(tmp));
            if (ret == -1) perrorOut();
            while (tmp[strlen(tmp) - 1] == '\n' || tmp[strlen(tmp) - 1] == '\r') tmp[strlen(tmp) - 1] = '\0';
            memset(cmd, 0, strlen(cmd));
            sprintf(cmd, "SELECT cle,id_compte,privilegie,accesCalendrier,miseIndispo FROM test.api WHERE cle = '%s';", tmp);
            printf("%s\n", cmd);
            res = PQexec(db, cmd);
            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                printf("DB error: %s\nDisconnect\n", PQerrorMessage(db));
                memset(cmd, 0, strlen(cmd));
                sprintf(cmd, "DB error: %s\r\n", PQerrorMessage(db));
                write(cnx, cmd, strlen(cmd));
                close(cnx);
            } else if (PQntuples(res) > 0) { //strncmp(&cmd, "0123456789ABCDEF", 16) == 0
                //memset(cmd, 0, strlen(cmd));
                //sprintf(cmd, "SELECT FROM test.api WHERE cle = '%.16s';", tmp);
                printf("Valid API key\n");
                accID = atoi(PQgetvalue(res, 0, 1));
                strcpy(accNom, PQgetvalue(res, 0, 0));
                accPriv = (PQgetvalue(res, 0, 2)[0] == 't');
                accCalend = (PQgetvalue(res, 0, 3)[0] == 't');
                accDesact = (PQgetvalue(res, 0, 4)[0] == 't');
                memset(cmd, 0, strlen(cmd));
                sprintf(cmd, "Authentication successful. Welcome %s\r\n", accNom);
                write(cnx, cmd, strlen(cmd));
                write(cnx, "Type \"help\" to get a list of available commands\r\n", 49);
                write(cnx, "LoQuali> ", 9);
                connected = true;
            } else {
                printf("Invalid API key. Disconnect\n");
                write(cnx, "\eInvalid API key\r\n", 4);
                close(cnx);
                //return 1;
            }
        }

        memset(cmd, 0, sizeof(cmd));
        printf("Reading...\n > ");

        //c = ' ';
        i = 0;
        ping = 0;
        while(strcmp(cmd, "exit\r")) { // le read() > 0 n'a pas l'air de faire grand chose, et le strcmp non plus pcq on vide cmd avant chaque utilisation
            read(cnx, &c, 1);
            //printf("%d ", c);
            if (i < MAXCMD && c != '\r' && c != '\n') { // Ici i sert à limiter la commande reçue pour empêcher une segfault
                printf("%c", c);
                cmd[i] = c;
                i++;
            }
            if ((c == '\r' || c == '\n') && i) {
                printf("\n");
                i = getArgs(cmd, cmdargs); // Ici i sert à compter le nombre d'arguments dans la commande (nom de la commande inclus)
                if (strcmp(cmdargs[0], "hello") == 0) {
                    //printf("Writing...\n");
                    write(cnx, "._.\r\n", 5);
                } else if (strcmp(cmd, "ping") == 0) {
                    //printf("Writing...\n");
                    ping++;
                    memset(cmd, 0, strlen(cmd)); // Pas nécessaire car la longueur de la chaine est plus grande que "PING" au final
                    sprintf(cmd, "PONG N°%d\r\n", ping); // Mais ça permet d'être plus propre au niveau mémoire
                    write(cnx, cmd, strlen(cmd));
                } else if (strcmp(cmdargs[0], "list_all") == 0) {
                    //printf("Writing...\n");
                    if (accPriv) {
                        res = PQexec(db, "SELECT id_logement, libelle_logement FROM test.logement;");
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
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
                        if (i >= 4) { // nom de la commande + 3 arguments
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "SELECT jour, disponibilite FROM test.planning where id_logement = %d AND jour >= '%s' AND jour <= '%s';", atoi(cmdargs[1]), cmdargs[2], cmdargs[3]);
                            res = PQexec(db, cmd);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                write(cnx, cmd, strlen(cmd));
                            } else {
                                // Affichage des attributs
                                writeTable(res, cnx);
                            }
                            PQclear(res);
                        } else {
                            write(cnx, "Not enough arguments\r\nUsage : planning <id> <start-date> <end-date>\r\n", 69);
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
                            memset(cmd, 0, strlen(cmd));
                            sprintf(cmd, "SELECT * FROM test.planning WHERE id_logement = %d AND jour = '%s';", atoi(cmdargs[1]), cmdargs[2]);
                            res = PQexec(db, cmd);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                memset(cmd, 0, strlen(cmd));
                                sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                write(cnx, cmd, strlen(cmd));
                            } else {
                                memset(cmd, 0, strlen(cmd));
                                if (PQntuples(res) > 0) {
                                    if (i >= 4) {
                                        memset(tmp, 0, strlen(tmp));
                                        sprintf(tmp, " raison_indisponible = '%s' ", cmdargs[3]);
                                    }
                                    sprintf(cmd, "UPDATE test.planning SET disponibilite = FALSE%sWHERE id_logement = %d AND jour = '%s';", (i >= 4 ? tmp : " "), atoi(cmdargs[1]), cmdargs[2]);
                                    res = PQexec(db, cmd);
                                    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
                                        memset(cmd, 0, strlen(cmd));
                                        sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                        write(cnx, cmd, strlen(cmd));
                                    } else {
                                        write(cnx, "OK\r\n", 4);
                                    }
                                } else {
                                    memset(cmd, 0, strlen(cmd));
                                    sprintf(cmd, "SELECT prix_base_ht FROM test.logement WHERE id_logement = %d;", atoi(cmdargs[1]));
                                    res = PQexec(db, cmd); // Impossible de crash puisque le premier test avec les paramètres a déjà marché
                                    sprintf(cmd, "INSERT INTO test.planning VALUES (FALSE, %10.2f, '%s', '%s', %d);", atof(PQgetvalue(res, 0, 0)), cmdargs[2], (i >= 4 ? cmdargs[3] : ""), atoi(cmdargs[1]));
                                    res = PQexec(db, cmd);
                                    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
                                        memset(cmd, 0, strlen(cmd));
                                        sprintf(cmd, "Err: %s\r\n", PQerrorMessage(db));
                                        write(cnx, cmd, strlen(cmd));
                                    } else {
                                        write(cnx, "OK\r\n", 4);
                                    }
                                }

                            }
                            PQclear(res);
                        } else {
                            write(cnx, "Not enough arguments\r\nUsage : disable <id> <date> [reason]\r\n", 60);
                        }
                    } else {
                        write(cnx, "You do not have permission to execute that command\r\n", 52);
                    }
                } else if (strcmp(cmdargs[0], "help") == 0) {
                    //printf("Writing...\n");
                    write(cnx, "Available commands :\r\n", 22);
                    write(cnx, "  hello\r\n", 9);
                    write(cnx, "  ping\r\n", 8);
                    if (accPriv) write(cnx, "  list_all\r\n", 12);
                    write(cnx, "  list\r\n", 8);
                    if (accCalend) write(cnx, "  planning <id> <start-date> <end-date>\r\n", 41);
                    if (accDesact) write(cnx, "  disable <id> <date> [reason]\r\n", 32);
                    write(cnx, "  help\r\n", 8);
                    write(cnx, "  exit\r\n", 8);
                } else if (strcmp(cmdargs[0], "exit") == 0) {
                    //printf("Writing...\n");
                    write(cnx, "ok bozo\r\n", 9);
                    close(cnx);
                    break;
                } else {
                    //printf("Writing...\n");
                    write(cnx, "Unknown command\r\n", 17);
                }
                //printf("Done\n");
                //printf("%ld\n", strlen(cmd));
                //printf("aaaa\n");
                memset(cmd, 0, strlen(cmd)); // plutôt qu'une boucle for
                emptyArgs(i, cmdargs);
                write(cnx, "LoQuali > ", 10);
                printf(" > ");
                i = 0;
            }
        }
        close(cnx);
        printf("Disconnected\n");
    }
    //printf("Writing...\n");
    //write(cnx, "Moi aussi j'aime les ananas\r\n", 29);
    //printf("Done\n");
    //PQclear(res);
    PQfinish(db);
    close(foutput);
    return 0;
}
