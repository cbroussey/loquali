#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <stdbool.h>
#include <sys/stat.h>
#include <sys/types.h>
#include <fcntl.h>
#include <time.h>
#include <stdarg.h>

#define MAXCMD 8192 // Taille max de toutes les chaines de caractères
#define fieldsAmnt 6

const char *neededFields[fieldsAmnt] = {
    "ip",
    "port",
    "key",
    "id",
    "data_path",
    "log_path"
};

int foutput = 0;

void errorOut(char *err) {
    printf("Err: %s\n", err);
    exit(1);
}

void printHelp(char name[]) {
    printf("Usage: %s <path_to_json_config> [polling_period]\n", name);
    printf("\tJSON configuration format is detailed in FORMAT.md\n");
    //printf("\tpolling_period must be formatted like this : NX");
    //printf("\t\tWhere N is an integer\n");
    //printf("\t\tAnd X is one character between D, W, or M\n");
    //printf("\t\t(Day, Week, or Month)\n");
}

// Fonction qui printf des logs à l'écran et dans un fichier (si un fichier a été ouvert)
// no_time = 0 -> Affichage à l'écran et écriture dans le fichier
// no_time = 1 -> Affichage à l'écran et écriture dans le fichier sans indication de temps (permet le formattage de certains affichages)
void printose(bool no_time, const char* format, ...) {
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);
    char txt[MAXCMD];
    char tmp[MAXCMD];
    va_list args;
    va_start(args, format);
    vsnprintf(txt, sizeof(txt), format, args); // do check return value
    va_end(args);
    memset(tmp, 0, sizeof(tmp));
    sprintf(tmp, "[%02d/%02d/%d %02d:%02d:%02d] ", tm.tm_mday, tm.tm_mon + 1, tm.tm_year + 1900, tm.tm_hour, tm.tm_min, tm.tm_sec);
    printf("%s%s", (no_time != true ? tmp : ""), txt);
    if (foutput) {
        if (no_time != true) write(foutput, tmp, strlen(tmp));
        write(foutput, txt, strlen(txt));
    }
}

void niceExit(char err[]) {
    printose(true, err);
    if (foutput) close(foutput);
    exit(1);
}

bool simpleMatch(char string[], char matcher[]) {
    int i = 0;
    int j;
    for (j = 0; j < strlen(string); j++) {
        if (string[j] == matcher[i] || (matcher[i] == '*' && i < strlen(matcher)-1 && j < strlen(string)-1 && string[j+1] == matcher[i+1])) {
            i++;
            if (i < strlen(matcher)-1 && j < strlen(string)-1 && string[j+1] == matcher[i+1]) i++; // If expected character after * is already the next one in the string
        }
    }
    return (i == strlen(matcher) || (i == strlen(matcher)-1 && matcher[i] == '*'));
}

// Ignore all emmpty characters
void ignEmpty(int file) {
    char c = ' ';
    while (c == ' ' || c < 33) read(file, &c, 1);
    lseek(file, -1, SEEK_CUR);
}

int parseJSON(int file, const char **expectedArgs, int numArgs, char **values) {
    int numParsed = 0;
    char line[MAXCMD];
    char value[MAXCMD];
    int i, j;
    int ret;
    char c;
    memset(line, 0, MAXCMD);
    memset(value, 0, MAXCMD);
    ignEmpty(file);
    ret = read(file, &c, 1);
    if (c != '{') {
        printose(true, "Error : Invalid JSON file");
        exit(1);
    }
    while (c != '}' && ret != 0) {
        ignEmpty(file);
        c = ' ';
        memset(line, 0, strlen(line));
        for (i = 0; c != ',' && c != '}'; i++) {
            ret = read(file, &c, 1);
            if (ret && c != ',' && c != '}') line[i] = c;
        }
        if (simpleMatch(line, "\"*\":*\"*\"*")) {
            //printf("%s\n", line);
            memset(value, 0, strlen(value));
            i = 1; // Define before otherwise check fails immediately
            for (i = 1; line[i] != '"'; i++) value[i-1] = line[i];
            j = numArgs;
            for (i = 0; i < numArgs; i++) {
                //printf("%s == %s\n", value, expectedArgs[i]);
                if (!strcmp(value, expectedArgs[i])) {
                    if (j != numArgs) {
                        printose(true, "Error : value '%s' found but specified multiple times in the expected args\n", value);
                        exit(1);
                    } else {
                        j = i;
                    }
                }
            }
            if (j != numArgs) {
                printose(true, "Parsed %s : ", value);
                i = 0;
                for (i = 0; line[i] != ':'; i++) c = ' ';
                for (i = i; line[i] != '"'; i++) c = ' ';
                memset(value, 0, strlen(value));
                i++;
                for (i = i; line[i] != '"'; i++) value[strlen(value)] = line[i];
                values[j] = malloc(strlen(value) + 1);  // Allocate memory for the string
                //if (value[j] == NULL) {
                //    printf("Error : malloc failed");
                //    exit(1);
                //}
                strcpy(values[j], value);
                printose(true, "%s\n", value);
                numParsed++;
            }
        } else {
            printose(true, "Error : Invalid JSON line at char %ld : '%s'\n", lseek(file, 0, SEEK_CUR)-strlen(line)-1, line);
            exit(1);
        }
        lseek(file, -1, SEEK_CUR);
        ret = read(file, &c, 1);
    }
    if (c != '}') {
        printose(true, "Error : Expected JSON object closure, got '%c'\n", c);
        exit(1);
    }
    return numParsed;
}

int main(int argc, char** argv) {
    int statusCode = EXIT_SUCCESS;
    //printf("%i\n", simpleMatch("abcdef", "abc*def"));
    //printf("%i\n", simpleMatch("\"key\": \"logementavecplanning\"", "\"*\":*\"*\"*"));
    //char pollType;
    //int pollTime;
    int file;
    char *values[MAXCMD];
    char filename[MAXCMD];
    char output[MAXCMD];
    char c;
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);;
    int sock;
    struct sockaddr_in server_addr;
    int i;
    if (argc < 2) {
        printHelp(argv[0]);
    } else if (argc >= 2 && access(argv[1], F_OK) == 0) {
        // Verification du JSON, sinon message d'erreur

        //if (argc > 2) {
            // Code de définition cron
            // Vérification de l'argument pérdiode, sinon message d'erreur
            //pollType = argv[2][strlen(argv[2])-1];
            //argv[2][strlen(argv[2])-1] = '\0';
            //pollTime = atoi(argv[2]);
            //xsystem("crontab -l > .crontemp; echo * %d %d * %d %s %s", ());
            //statusCode = system("cron");
        //} else {
            file = open(argv[1], O_RDONLY);
            parseJSON(file, neededFields, fieldsAmnt, values);
            close(file);
            for (i = 0; i < fieldsAmnt-1; i++) {
                if (values[i] == NULL) {
                    printose(true, "Error : one or more required attributes are missing, please check FORMAT.md for the documentation\n");
                    exit(1);
                }
            }
            if (values[fieldsAmnt-1] != NULL) {
                memset(filename, 0, sizeof(filename));
                tm = *localtime(&t);
                sprintf(filename, "%slogs-%02d_%02d_%d.log", values[fieldsAmnt-1], tm.tm_mday, tm.tm_mon + 1, tm.tm_year + 1900);
                //  printf("%s\n", filename);
                foutput = open(filename, O_WRONLY | O_APPEND | O_CREAT, 0644);
                if (foutput == -1) {
                    foutput = 0;
                    printose(true, "Warning : could not create log file. Ignoring");
                } else {
                    write(foutput, "===== [LOG START] =====\n", 24);
                    write(foutput, "Parsed attributes :\n", 20);
                    for (i = 0; i < fieldsAmnt; i++) {
                        write(foutput, "- '", 3);
                        write(foutput, neededFields[i], strlen(neededFields[i]));
                        write(foutput, "' = ", 4);
                        write(foutput, values[i], strlen(values[i]));
                        write(foutput, "\n", 1);
                    }
                }
            }
            printose(true, "\n");
            tm = *localtime(&t);
            memset(filename, 0, strlen(filename));
            sprintf(filename, "%sapirator-%02d_%02d_%d_%02d_%02d_%02d.json", values[4], tm.tm_mday, tm.tm_mon + 1, tm.tm_year + 1900, tm.tm_hour, tm.tm_min, tm.tm_sec);
            //printf("%s\n", filename);
            file = open(filename, O_WRONLY | O_APPEND | O_CREAT, 0644);
            if (file == -1) {
                niceExit("Error : could not create data result file. Aborting");
            }
            printose(false, "Connecting to the server... ");
            int server_port = atoi(values[1]);
            sock = socket(AF_INET, SOCK_STREAM, 0);
            if (sock == -1) {
                niceExit("Socket creation error\n");
            }
            server_addr.sin_family = AF_INET;
            server_addr.sin_port = htons(server_port);
            if (inet_pton(AF_INET, values[0], &server_addr.sin_addr) <= 0) {
                niceExit("Invalid server address\n");
            }
            if (connect(sock, (struct sockaddr *)&server_addr, sizeof(server_addr)) == -1) {
                niceExit("Connection error\n");
            }
            printose(true, "OK\n");
            printose(false, "Awaiting server input... ");
            c = ' ';
            do {
                if (recv(sock, &c, 1, 0) == -1) niceExit("Receive error"); 
            } while (c != '\x03');
            printose(true, "OK\n");
            printose(false, "Sending api key... ");
            if (send(sock, values[2], strlen(values[2]), 0) == -1) {
                niceExit("Send error");
            }
            printose(true, "OK \n");
            printose(false, "Awaiting server input... ");
            do {
                if (recv(sock, &c, 1, 0) == -1) niceExit("Receive error"); 
            } while (c != '\x03');
            printose(true, "OK\n");
            sprintf(output, "available %s\n", values[3]);
            printose(false, "Sending command... ");
            if (send(sock, output, strlen(output), 0) == -1) {
                niceExit("Send error");
            }
            printose(true, "OK\n");
            printose(false, "Receiving data... ");
            do {
                if (recv(sock, &c, 1, 0) == -1) niceExit("Receive error"); 
                write(file, &c, 1);
            } while (c != '\n');
            printose(true, "OK\n");
            printose(false, "Awaiting server input... ");
            do {
                if (recv(sock, &c, 1, 0) == -1) niceExit("Receive error"); 
            } while (c != '\x03');
            printose(true, "OK\n");
            printose(false, "Closing connection... ");
            if (send(sock, "exit\n", 5, 0) == -1) {
                niceExit("Send error");
            }
            printose(true, "OK\n");
            printose(false, "Everything seems good, program done\n");
            close(file);
        //}
    } else {
        printose(true, "Error: missing JSON file path argument\n\n");
        printHelp(argv[0]);
        statusCode = EXIT_FAILURE;
    }
    if (foutput) {
        write(foutput, "====== [LOG END] ======\n", 24);
        close(foutput);
    }
    return statusCode;
}