<?php
include('connect_params.php');

$id = $_GET["id_logement"];


try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

    $queryLogement = "SELECT jour FROM test.planning WHERE id_logement = :id_logement AND disponibilite = :disp";
    $stmtLogement = $dbh->prepare($queryLogement);

    $dispValue = false; 
    $stmtLogement->bindParam(':id_logement', $id, PDO::PARAM_INT);
    $stmtLogement->bindParam(':disp', $dispValue, PDO::PARAM_BOOL);
    
    $stmtLogement->execute();
    $info = $stmtLogement->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


echo json_encode($info);
?>
