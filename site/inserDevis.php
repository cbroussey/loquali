<?php
session_start();
error_reporting(0);
include('connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Convertir la date de début au format "Y-m-d"
    $start_date = DateTime::createFromFormat('d/m/Y', $_POST["start-date"]);
    $deb = $start_date->format('Y-m-d');
    
    // Convertir la date de fin au format "Y-m-d"
    $end_date = DateTime::createFromFormat('d/m/Y', $_POST["end-date"]);
    $fin = $end_date->format('Y-m-d');
    
    $id = $_POST["id"];
    $id_compte = $_SESSION['userId'];
    $nb_personne = intval($_POST["Personne"]);

    try {
        $stmt = $dbh->prepare("
            INSERT INTO test.reservation (
                debut_reservation,
                fin_reservation,
                nb_personne,
                id_compte,
                id_logement
            ) VALUES (
                :debut_reservation,
                :fin_reservation,
                :nb_personne,
                :id_compte,
                :id_logement
            ) returning id_reservation");

        $stmt->bindParam(':debut_reservation', $deb);
        $stmt->bindParam(':fin_reservation', $fin);
        $stmt->bindParam(':nb_personne', $nb_personne);
        $stmt->bindParam(':id_compte', $id_compte);
        $stmt->bindParam(':id_logement', $id);

        $stmt->execute();
        $Idres = $stmt->fetchAll()[0];

        if (!empty($nom_Charge)) {
            $stmt = $dbh->prepare("
                INSERT INTO test.charges_selectionnees (
                    id_reservation,
                    nom_charge
                ) VALUES (
                    :id_reservation,
                    :nom_charge
                )");

            $stmt->bindParam(':id_reservation', $Idres["id_reservation"]);
            $stmt->bindParam(':nom_charge', $nom_Charge);

            $stmt->execute();
        }
        $prix_devis = $_POST["prix"];
        $delai_acceptation = null;
        $acceptation = false;
        $date_devis = date("Y-m-d H:i:s");

        $stmt = $dbh->prepare(" 
            INSERT INTO test.devis (
                id_reservation,
                prix_devis,
                delai_acceptation,
                acceptation,
                date_devis
            ) VALUES (
                :id_reservation,
                :prix_devis,
                :delai_acceptation,
                :acceptation,
                :date_devis
            )
        ");

        $stmt->bindParam(':id_reservation',  $Idres["id_reservation"], PDO::PARAM_INT);
        $stmt->bindParam(':prix_devis', $prix_devis, PDO::PARAM_INT); 
        $stmt->bindParam(':delai_acceptation', $delai_acceptation, PDO::PARAM_NULL); 
        $stmt->bindParam(':acceptation', $acceptation, PDO::PARAM_BOOL);
        $stmt->bindParam(':date_devis', $date_devis, PDO::PARAM_STR);

        $stmt->execute();

        while ($deb <= $fin) {
            $stmt = $dbh->prepare("
                INSERT INTO test.planning (disponibilite, prix_ht, jour, raison_indisponible, id_logement)
                VALUES (false, 0.00, :current_date, :raison_indisponible, :id_logment)
            ");

            $stmt->bindParam(':current_date', $deb, PDO::PARAM_STR);
            $stmt->bindValue(':raison_indisponible', null, PDO::PARAM_NULL);
            $stmt->bindParam(':id_logment', $id, PDO::PARAM_INT);

            $stmt->execute();

            // Passez à la date suivante
            $deb = date("Y-m-d", strtotime($deb . " +1 day"));
            
        }
        header("Location: compte.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion du devis : " . $e->getMessage();
    }
}
?>
