<?php
    include_once("./connect_params.php");
    if (isset($_GET['t'])) {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $res = $dbh->prepare("SELECT * FROM test.ical WHERE token = :token");
        $res->bindParam(':token', $_GET['t'], PDO::PARAM_STR);
        $res->execute();
        $res = $res->fetchAll();
        if (empty($res)) {
            die("403 - Forbidden");
        } else {
            $res = $res[0];
            $venv = $dbh->prepare("SELECT * FROM test.planning WHERE id_logement = :id_log AND jour >= :date_debut AND jour <= :date_fin AND disponibilite = FALSE");
            $venv->bindParam(':id_log', $res['id_logement'], PDO::PARAM_INT);
            $venv->bindParam(':date_debut', $res['date_debut'], PDO::PARAM_STR);
            $venv->bindParam(':date_fin', $res['date_fin'], PDO::PARAM_STR);
            $venv->execute();
            $venv = $venv->fetchAll();
            $cal = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//Loquali//NONSGML alz.bzh//FR\n";
            foreach ($venv as $v) {
                $cal .= "BEGIN:VEVENT\nDTSTART:" . date("Ymd\THis\Z", strtotime($v['jour'])) . "\nDTEND:" . date("Ymd\THis\Z", strtotime($v['jour'] . " +1 day")) . "\nSUMMARY:Indisponible\nEND:VEVENT\n";
            }
            $cal .= "END:VCALENDAR";
            echo $cal;
            header('Content-type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="cal.ics"');
        }
    } else {
        die("400 - Bad Request");
    }
    //print_r($res);
?>