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
    $res = $dbh->prepare("SELECT cle FROM test.api WHERE id_compte = :id");
    $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
    $res->execute();
    $cles = $res->fetchAll();
    $cles = array_column($cles, 'cle');
    //print_r($cles);
    foreach($cles as $i) {
        $calend = (isset($_POST[$i . "_c"]) ? "TRUE" : "FALSE");
        $indisp = (isset($_POST[$i . "_i"]) ? "TRUE" : "FALSE");
        $disp = (isset($_POST[$i . "_d"]) && $calend == "FALSE" && $indisp == "FALSE" ? "TRUE" : "FALSE");
        $res = $dbh->prepare("UPDATE test.api SET accescalendrier = :calend, miseindispo = :indisp, misedispo = :disp WHERE cle=:cle AND id_compte = :id");
        $res->bindParam('cle', $i, PDO::PARAM_STR);
        $res->bindParam('calend', $calend, PDO::PARAM_STR);
        $res->bindParam('indisp', $indisp, PDO::PARAM_STR);
        $res->bindParam('disp', $disp, PDO::PARAM_STR);
        $res->bindParam('id', $_SESSION["userId"], PDO::PARAM_INT);
        try {
            $res->execute();
        } catch (Exception $e) {
            continue;
        }
    }
    //print_r($_POST);
    header("Location: compte.php?ind=5");
?>