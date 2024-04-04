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
    $res = $dbh->prepare("DELETE FROM test.ical WHERE id_compte = :id AND token = :token");
    $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
    $res->bindParam('token', $_GET["confirmDelete"], PDO::PARAM_STR);
    try {
        $res->execute();
    } catch (Exception $e) {
        ;
    }
    //print_r($_POST);
    header("Location: compte.php?ind=5");
?>