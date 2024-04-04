<?php
    session_start();
    error_reporting(0);
    include_once("./connect_params.php");
    $cles = [];
    /*
    foreach($_POST as $i => $v) {
        if (!in_array(explode("_", $i)[0], $cles)) {
            array_push($cles, explode("_", $i)[0]);
        }
    }
    */
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $res = $dbh->prepare("INSERT INTO test.ical VALUES (:token, :date_debut, :date_fin, :id_log, :id)");
    $keygen = substr(md5(rand()), 0, 20);
    $res->bindParam('token', $keygen, PDO::PARAM_STR);
    $res->bindParam('date_debut', $_POST["debut"], PDO::PARAM_STR);
    $res->bindParam('date_fin', $_POST["fin"], PDO::PARAM_STR);
    $res->bindParam('id_log', $_POST["id_log"], PDO::PARAM_INT);
    $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
    try {
        $res->execute();
    } catch (Exception $e) {
        ;
    }
    //print_r($_POST);
    header("Location: compte.php?ind=5");
?>