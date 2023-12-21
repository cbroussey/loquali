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

#define MAXCMD 1024

int main(int argc, char *argv[]) {
    int sock;
    int ret;
    struct sockaddr_in addr;
    int size;
    int cnx;
    struct sockaddr_in conn_addr;
    size = (int)sizeof(conn_addr);
    char c;
    char cmd[MAXCMD];
    int ping;
    int i;
    bool verbose = false;
    int foutput;
    int port;

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
    close(foutput);
    //return 0;

    printf("Socket init...\t ");
    sock = socket(AF_INET, SOCK_STREAM, 0);
    if (sock == -1) { printf("err: %s\n", strerror(errno)); return 1; }
    printf("%d\nSocket ip :\t ", sock);
    addr.sin_addr.s_addr = inet_addr("127.0.0.1"); // INADDR_ANY;
    if (addr.sin_addr.s_addr == -1) { printf("err: %s\n", strerror(errno)); return 1; }
    printf("%d\nSocket family :\t ", addr.sin_addr.s_addr);
    addr.sin_family = AF_INET;
    printf("%d\nSocket port :\t ", addr.sin_family);
    addr.sin_port = htons(port);
    printf("%d\nSocket bind...\t ", addr.sin_port);
    ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));
    if (ret == -1) { printf("err: %s\n", strerror(errno)); return 1; }
    while (1) {
    printf("%d\nSocket listen... ", ret);
    ret = listen(sock, 1);
    if (ret == -1) { printf("err: %s\n", strerror(errno)); return 1; }
    printf("%d\nSocket accept... ", ret);
    cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
    if (cnx == -1) { printf("err: %s\n", strerror(errno)); return 1; }
    printf("%d\n", cnx);
    //read(cnx, &c, 1024);
    //printf("%s", c);
    write(cnx, "API key > ", 10);
    printf("Waiting for API key...\n");
    read(cnx, &c, 1024);
    if (strncmp(&c, "123456789ABCDEF", 15) == 0) {
        printf("Valid API key\n");
        write(cnx, "Authentication successful. Welcome :user:\r\n", 43);
        write(cnx, "Type \"help\" to get a list of available commands\r\n", 49);
        write(cnx, "LoQuali> ", 9);
    } else {
        printf("Invalid API key\n");
        write(cnx, "Invalid API key\r\n", 4);
        close(cnx);
        return 1;
    }
    printf("Reading...\n > ");
    while(strcmp(cmd, "exit\r")) { // le read() > 0 n'a pas l'air de faire grand chose
        read(cnx, &c, 1);
        if (i < MAXCMD && c != '\n') {
            printf("%c", c);
            cmd[i] = c;
            i++;
        }
        if (c == '\r') {
            printf("\n");
            if (strcmp(cmd, "hello\r") == 0) {
                //printf("Writing...\n");
                write(cnx, "._.\r\n", 5);
            } else if (strcmp(cmd, "ping\r") == 0) {
                //printf("Writing...\n");
                ping++;
                memset(cmd, 0, strlen(cmd)); // Pas nécessaire car la longueur de la chaine est plus grande que "PING" au final
                sprintf(cmd, "PONG N°%d\r\n", ping); // Mais ça permet d'être plus propre au niveau mémoire
                write(cnx, cmd, strlen(cmd));
            } else if (strcmp(cmd, "help\r") == 0) {
                //printf("Writing...\n");
                write(cnx, "Available commands :\r\n", 22);
                write(cnx, "  hello\r\n", 10);
                write(cnx, "  ping\r\n", 9);
                write(cnx, "  help\r\n", 9);
                write(cnx, "  exit\r\n", 9);
            } else if (strcmp(cmd, "exit\r") == 0) {
                //printf("Writing...\n");
                write(cnx, "ok bozo\r\n", 9);
                //close(cnx);
                break;
            } else {
                //printf("Writing...\n");
                write(cnx, "Unknown command\r\n", 17);
            }
            //printf("Done\n");
            //printf("%ld\n", strlen(cmd));
            memset(cmd, 0, strlen(cmd)); // plutôt qu'une boucle for
            write(cnx, "LoQuali> ", 9);
            printf(" > ");
            i = 0;
        }
    }
    //printf("Writing...\n");
    //write(cnx, "Moi aussi j'aime les ananas\r\n", 29);
    //printf("Done\n");
    close(cnx);
    return 0;
}
