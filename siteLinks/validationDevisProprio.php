<?php
include('connect_params.php');
try {

    $nouveau_prix = $_POST["total"];
    $nouveau_delai = (new DateTime())->add(new DateInterval('P5D'))->format('Y-m-d');
    $reservation = $_POST["reservation"];


    
    
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

    $stmt = $dbh->prepare("
    UPDATE test.devis
    SET prix_devis=:nouveau_prix,
        delai_acceptation=:nouveau_delai
    WHERE id_reservation=:id_reservation
");

    $stmt->bindParam(':nouveau_prix', $nouveau_prix); 
    $stmt->bindParam(':nouveau_delai', $nouveau_delai);
    $stmt->bindParam(':id_reservation', $reservation);

    $stmt->execute();
    header("Location: compte.php");
    exit();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
