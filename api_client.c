#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <unistd.h>

#define MAX_BUFFER_SIZE 1024

int main(int argc, char *argv[]) {
    if (argc != 4) {
        fprintf(stderr, "Usage: %s <server_ip> <server_port> <api_key>\n", argv[0]);
        exit(1);
    }

    char *server_ip = argv[1];
    int server_port = atoi(argv[2]);
    char *api_key = argv[3];

    int sock = socket(AF_INET, SOCK_STREAM, 0);
    if (sock == -1) {
        perror("Socket creation error");
        exit(1);
    }

    struct sockaddr_in server_addr;
    server_addr.sin_family = AF_INET;
    server_addr.sin_port = htons(server_port);
    if (inet_pton(AF_INET, server_ip, &server_addr.sin_addr) <= 0) {
        perror("Invalid server address");
        exit(1);
    }

    if (connect(sock, (struct sockaddr *)&server_addr, sizeof(server_addr)) == -1) {
        perror("Connection error");
        exit(1);
    }

    // envoie de l'API au serveur pour identification
    if (send(sock, api_key, strlen(api_key), 0) == -1) {
        perror("Send error");
        exit(1);
    }

    char command[MAX_BUFFER_SIZE];
    char response[MAX_BUFFER_SIZE];

    while (1) {
        printf("Enter command (or 'exit' to quit): ");
        fgets(command, MAX_BUFFER_SIZE, stdin);

        // enlever le saut de ligne 
        command[strcspn(command, "\n")] = '\0';

        if (strcmp(command, "exit") == 0) {
            break;
        }

        if (send(sock, command, strlen(command), 0) == -1) {
            perror("Send error");
            exit(1);
        }

        memset(response, 0, sizeof(response));
        if (recv(sock, response, sizeof(response), 0) == -1) {
            perror("Receive error");
            exit(1);
        }

        printf("Server response: %s\n", response);
    }

    close(sock);
    return 0;
}
