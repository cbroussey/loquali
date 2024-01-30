<?php 
error_reporting(0);
    include "connect_params.php";
    if (isset($_POST) && isset($_POST["nb_cb"])){
        $nb_cb = $_POST["nb_cb"];
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $query = "DELETE FROM test.cb WHERE test.cb.numero_carte = :nb_cb";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam('nb_cb', $nb_cb, PDO::PARAM_INT);
        $stmt->execute();
        $dbo = null;
    }
    header('location: compte.php');
?>