<?php
    session_start();
    error_reporting(0);
    include_once("./connect_params.php");
    $cles = [];
    foreach($_POST as $i => $v) {
        if (!in_array(explode("_", $i)[0], $cles)) {
            array_push($cles, explode("_", $i)[0]);
        }
    }
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    foreach($cles as $i) {
        $calend = (isset($_POST[$i . "_c"]) ? "TRUE" : "FALSE");
        $indisp = (isset($_POST[$i . "_i"]) ? "TRUE" : "FALSE");
        $res = $dbh->prepare("UPDATE test.api SET accescalendrier = :calend, miseindispo = :indisp WHERE cle=:cle AND id_compte = :id");
        $res->bindParam('cle', $i, PDO::PARAM_STR);
        $res->bindParam('calend', $calend, PDO::PARAM_STR);
        $res->bindParam('indisp', $indisp, PDO::PARAM_STR);
        $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
        $res->execute();
    }
    //print_r($_POST);
    header("Location: compte.php");
?>