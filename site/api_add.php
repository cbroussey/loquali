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
    $res = $dbh->prepare("INSERT INTO test.api VALUES (:cle, FALSE, :cal, :indisp, :id)");
    $keygen = substr(md5(rand()), 0, 20);
    $cal = isset($_POST["cal"]);
    $indisp = isset($_POST["indisp"]);
    $res->bindParam('cle', $keygen, PDO::PARAM_STR);
    $res->bindParam('cal', $cal, PDO::PARAM_BOOL);
    $res->bindParam('indisp', $indisp, PDO::PARAM_BOOL);
    $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
    try {
        $res->execute();
    } catch (Exception $e) {
        ;
    }
    //print_r($_POST);
    header("Location: compte.php?ind=5");
?>