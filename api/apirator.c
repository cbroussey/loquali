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

#define MAXCMD 1024 // Taille max de toutes les chaines de caractères

const char *neededFields[2] = {
    "key",
    "id_log"
};

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
        printf("Error : Invalid JSON file");
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
                        printf("Error : value '%s' found but specified multiple times in the expected args\n", value);
                        exit(1);
                    } else {
                        j = i;
                    }
                }
            }
            if (j != numArgs) {
                printf("Parsed %s : ", value);
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
                printf("%s\n", value);
                numParsed++;
            }
        } else {
            printf("Error : Invalid JSON line at char %ld : '%s'\n", lseek(file, 0, SEEK_CUR)-strlen(line)-1, line);
            exit(1);
        }
        lseek(file, -1, SEEK_CUR);
        ret = read(file, &c, 1);
    }
    if (c != '}') {
        printf("Error : Expected JSON object closure, got '%c'\n", c);
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
            parseJSON(file, neededFields, 2, values);
            printf("key='%s', id=%s\n", values[0], values[1]);
        //}
    } else {
        printf("Error: missing JSON file path argument\n\n");
        printHelp(argv[0]);
        statusCode = EXIT_FAILURE;
    }
    return statusCode;
}