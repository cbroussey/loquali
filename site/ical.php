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
            header('Content-type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="cal.ics"');
            $res = $res[0];
            $indisp = $dbh->prepare("SELECT * FROM test.planning JOIN test.logement ON test.planning.id_logement = test.logement.id_logement WHERE test.planning.id_logement = :id_log AND jour >= :date_debut AND jour <= :date_fin AND disponibilite = FALSE");
            $indisp->bindParam(':id_log', $res['id_logement'], PDO::PARAM_INT);
            $indisp->bindParam(':date_debut', $res['date_debut'], PDO::PARAM_STR);
            $indisp->bindParam(':date_fin', $res['date_fin'], PDO::PARAM_STR);
            $indisp->execute();
            $indisp = $indisp->fetchAll();
            $resa = $dbh->prepare("SELECT * FROM test.reservation JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement WHERE test.logement.id_logement = :id_log AND debut_reservation >= :date_debut AND debut_reservation <= :date_fin");
            $resa->bindParam(':id_log', $res['id_logement'], PDO::PARAM_INT);
            $resa->bindParam(':date_debut', $res['date_debut'], PDO::PARAM_STR);
            $resa->bindParam(':date_fin', $res['date_fin'], PDO::PARAM_STR);
            $resa->execute();
            $resa = $resa->fetchAll();
            //print_r($resa);
            $cal = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//Loquali//NONSGML alz.bzh//FR\n";
            foreach ($indisp as $v) {
                $cal .= "BEGIN:VEVENT\nDTSTART;TZID=Europe/Paris:" . date("Ymd\T000000\Z", strtotime($v['jour'])) . "\nDTEND:" . date("Ymd\T220000\Z", strtotime($v['jour'])) . "\nSUMMARY:Indisponible\nDESCRIPTION:" . $v["raison_indisponible"] . "\nLOCATION:" . $v["libelle_logement"] . ", " . $v["localisation"] . "\nEND:VEVENT\n";
            }
            foreach ($resa as $v) {
                if ($v["acceptation"] == 1) {
                    $cal .= "BEGIN:VEVENT\nDTSTART;TZID=Europe/Paris:" . date("Ymd\T000000\Z", strtotime($v['debut_reservation'])) . "\nDTEND:" . date("Ymd\T220000\Z", strtotime($v['fin_reservation'])) . "\nSUMMARY:Réservation\nLOCATION:" . $v["libelle_logement"] . ", " . $v["localisation"] . "\nEND:VEVENT\n";
                } else {
                    $cal .= "BEGIN:VEVENT\nDTSTART;TZID=Europe/Paris:" . date("Ymd\T000000\Z", strtotime($v['debut_reservation'])) . "\nDTEND:" . date("Ymd\T220000\Z", strtotime($v['fin_reservation'])) . "\nSUMMARY:Demande de réservation\nLOCATION:" . $v["libelle_logement"] . ", " . $v["localisation"] . "\nDESCRIPTION:Expiration de la demande le ". $v["delai_acceptation"] ."\nEND:VEVENT\n";
                }
            }
            $cal .= "END:VCALENDAR";
            echo $cal;
        }
    } else {
        die("400 - Bad Request");
    }
    //print_r($res);
?>