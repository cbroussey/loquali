#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <stdbool.h>

#define MAXCMD 1024 // Taille max de toutes les chaines de caractères

void printHelp(char name[]) {
    printf("Usage: %s <path_to_json_config> [polling_period]\n", name);
    printf("\tJSON configuration format is detailed in FORMAT.md\n");
    printf("\tpolling_period must be formatted like this : NX");
    printf("\t\tWhere N is an integer\n");
    printf("\t\tAnd X is one character between D, W, or M\n");
    printf("\t\t(Day, Week, or Month)\n");
}

bool simpleMatch(char string[], char matcher[]) {
    int i = 0;
    int j;
    for (j = 0; j < strlen(string); j++) {
        if (string[j] == matcher[i] || (matcher[i] == '*' && i < strlen(matcher)-1 && j < strlen(string)-1 && string[j+1] == matcher[i+1])) {
            i += 1;
            if (i < strlen(matcher)-1 && j < strlen(string)-1 && string[j+1] == matcher[i+1]) i += 1; // If expected character after * is already the next one in the string
        }
    }
    return (i == strlen(matcher));
}

int main(int argc, char** argv) {
    int statusCode = EXIT_SUCCESS;
    printf("%i\n", simpleMatch("abcdef", "abc*def"));
    char pollType;
    int pollTime;
    if (argc < 2) {
        printHelp(argv[0]);
    } else if (argc >= 2 && access(argv[1], F_OK) == 0) {
        // Verification du JSON, sinon message d'erreur

        if (argc > 2) {
            // Code de définition cron
            // Vérification de l'argument pérdiode, sinon message d'erreur
            pollType = argv[2][strlen(argv[2])-1];
            argv[2][strlen(argv[2])-1] = '\0';
            pollTime = atoi(argv[2]);
            system("crontab -l > .crontemp; echo * %d %d * %d %s %s", ());
            statusCode = system("cron");
        } else {
            // Code de polling
        }
    } else {
        printf("Error: JSON file not found\n\n");
        printHelp();
        statusCode = EXIT_FAILURE;
    }
    return statusCode;
}