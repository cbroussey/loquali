<?php
error_reporting(0);
include('connect_params.php');

$idReservation = $_GET["idreservation"];
$idLogement = $_GET["id_logement"];

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $query = "SELECT * FROM test.reservation WHERE id_reservation = :id_reservation";
    $stmtLogement = $dbh->prepare($query);
    $stmtLogement->bindParam(':id_reservation', $idReservation, PDO::PARAM_INT);
    $stmtLogement->execute();
    $reservation = $stmtLogement->fetch(PDO::FETCH_ASSOC);
    $deb = $reservation["debut_reservation"];
    $fin = $reservation["fin_reservation"];
    // Convertir les dates en objets DateTime
    // Convertir les dates en objets DateTime
    $startDate = new DateTime($deb);
    $endDate = new DateTime($fin);

    $currentDate = clone $startDate; // Utiliser une copie de la date de début

    // Boucle jusqu'à ce que la date actuelle soit supérieure à la date de fin
    while ($currentDate <= $endDate) {
        $stmt = $dbh->prepare("
        DELETE FROM test.planning 
        WHERE jour = :current_date AND id_logement = :id_logement
    ");
        // Utiliser la méthode format() pour obtenir la date au format Y-m-d
        $currentDateString = $currentDate->format('Y-m-d');
        $stmt->bindParam(':current_date', $currentDateString, PDO::PARAM_STR);
        $stmt->bindParam(':id_logement', $idLogement, PDO::PARAM_INT);
        $stmt->execute();
        // Incrémenter la date de 1 jour
        $currentDate->modify('+1 day');
    }


    $queryDeleteReservation = "
        DELETE FROM test.reservation
        WHERE id_reservation = :id_reservation
    ";
    $stmtDeleteReservation = $dbh->prepare($queryDeleteReservation);
    $stmtDeleteReservation->bindParam(':id_reservation', $idReservation, PDO::PARAM_INT);
    $stmtDeleteReservation->execute();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
