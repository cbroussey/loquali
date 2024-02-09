<?php 
error_reporting(0);
 include('connect_params.php');
 $id = $_GET["id_logement"];
 $date_debut = $_GET["date_debut"];
 $date_fin = $_GET["date_fin"];
 try {
     $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

     $query1 = "SELECT * FROM test.getPlageData(:id_logement, :date_debut, :date_fin);";
     $stmt = $dbh->prepare($query1);
     $stmt->bindParam(':id_logement', $id, PDO::PARAM_INT);
     $stmt->bindParam(':date_debut', $date_debut, PDO::PARAM_STR); 
     $stmt->bindParam(':date_fin', $date_fin, PDO::PARAM_STR); 
     $stmt->execute();
     $info = $stmt->fetch(PDO::FETCH_ASSOC);
     $info['date_debut'] = $date_debut;
     $info['date_fin'] = $date_fin;
 } catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

echo json_encode($info);




?>  