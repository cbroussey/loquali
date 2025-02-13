<?php
session_start();
error_reporting(0);

include ('connect_params.php');

//récupération de l'id du logement
$idLogement = $_GET['id'];

//initialisation bdd
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

//récupération du prix de base du logement et de son libellé
$query = "SELECT prix_base_ht, libelle_logement, id_compte FROM test.logement WHERE id_logement = :id_logement;";
$stmt = $dbh->prepare($query);
$stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
$stmt->execute();
$stmt = $stmt->fetch();
$prixBase = $stmt['prix_base_ht'];
$libelle = $stmt['libelle_logement'];
$verifCompte = $stmt['id_compte'];

//vérification de l'identité du propriétaire
if ($verifCompte != $_SESSION['userId']) {
    header("Location: index.php");
    exit();
}

//récupération des réservations sur ce logement
$query = "SELECT debut_reservation, fin_reservation FROM test.reservation NATURAL JOIN test.devis WHERE id_logement = :id_logement AND acceptation = :acceptation;";
$stmt = $dbh->prepare($query);
$acceptation = 1;
$stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
$stmt->bindParam('acceptation', $acceptation, PDO::PARAM_INT);
$stmt->execute();
$reservedDays = $stmt->fetchAll();

//si on a validé un formulaire, insertion/modifications des disponibilités dans la bdd
if (isset($_POST['allDays'])) {
    $modifiedDays = explode(',', $_POST['allDays']);
    $query = "SELECT jour FROM test.planning WHERE id_logement = :id_logement;";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
    $stmt->execute();
    $daysInBdd = $stmt->fetchAll();
    $daysInBdd = array_column($daysInBdd, 'jour');
    $boolean = ($modifiedDays[0] == 'true') ? 1 : 0; //true si le logement est disponible, false sinon
    $unavailabilityReason = $_POST['raisonIndispo'];

    for ($i = 1; $i < sizeof($modifiedDays); $i++) {
        $modifiedDay = addZero($_POST['year'] . '-' . $_POST['month'] . '-' . $modifiedDays[$i]);
        $modifiedPrix = $_POST['prix'];
        if (in_array($modifiedDay, $daysInBdd)) {
            $query = "UPDATE test.planning SET disponibilite = :disponibilite, prix_ht = :prix_ht, raison_indisponible = :raison_indisponible WHERE id_logement = :id_logement AND jour = :jour;";
        } else {
            $query = "INSERT INTO test.planning(disponibilite, prix_ht, jour, raison_indisponible, id_logement) VALUES (:disponibilite, :prix_ht, :jour, :raison_indisponible, :id_logement);";
        }
        $stmt = $dbh->prepare($query);
        $stmt->bindParam('disponibilite', $boolean, PDO::PARAM_INT);
        $stmt->bindParam('prix_ht', $modifiedPrix, PDO::PARAM_INT);
        $stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
        $stmt->bindParam('jour', $modifiedDay, PDO::PARAM_STR);
        $stmt->bindParam('raison_indisponible', $unavailabilityReason, PDO::PARAM_STR);
        $stmt->execute();
    }
}

//requête de récupération de toutes les dates indisponibles du logement
$query = "SELECT disponibilite, jour, prix_ht, raison_indisponible FROM test.planning WHERE id_logement = :id_logement;";
$stmt = $dbh->prepare($query);
$stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
$stmt->execute();
$dispo = $stmt->fetchAll();

//fonction qui renvoie les jours indisponibles d'un mois ($month) donné
function getAnavailableDaysInOneMonth($month, $data)
{
    $availableDays = array();
    foreach ($data as $value) {
        $day = explode("-", $value['jour']);
        //vérification du mois et de l'indisponibilité du logement
        if ($day[1] == $month) {
            $availableDays[$value['jour']] = [$value['prix_ht'], $value['disponibilite']];
        }
    }
    return $availableDays;
}

//fonction qui rajoute des zéros à une date pour avoir le format attendu
function addZero($date)
{
    //divise la date en année, mois et jour
    $explodedDate = explode('-', $date);
    $year = $explodedDate[0];
    $month = $explodedDate[1];
    $day = $explodedDate[2];

    // Ajouter des zéros si nécessaire
    if (strlen($month) < 2) {
        $month = '0' . $month;
    }
    if (strlen($day) < 2) {
        $day = '0' . $day;
    }

    return "$year-$month-$day";
}

//fonction qui renvoie un tableau contenant toutes les dates comprises entre deux dates, start et end
function getDaysBetweenBounds($start, $end)
{
    $days = [];
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);

    //ajout de la date de début
    $days[] = $startDate->format('Y-m-d');

    //ajout des jours intermédiaires
    while ($startDate < $endDate) {
        $startDate->modify('+1 day');
        $days[] = $startDate->format('Y-m-d');
    }
    return $days;
}

if (isset($_POST['prevOrNext'])) {

    //passe au mois suivant si l'utilisateur clique sur "suivant"
    if ($_POST['prevOrNext'] == 'next') {
        $month = $_POST['month'] + 1;
        $year = $_POST['year'];
        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        //passe au mois suivant si l'utilisateur clique sur "précédent"
    } else if ($_POST['prevOrNext'] == 'prev') {
        $month = $_POST['month'] - 1;
        $year = $_POST['year'];
        if ($month <= 0) {
            $month = 12;
            $year -= 1;
        }
    } else if ($_POST['prevOrNext'] == 'submit') {
        $month = $_POST['month'];
        $year = $_POST['year'];
    }

} else {
    $today = explode('-', date("Y-m-d"));
    $month = $today[1];
    $year = $today[0];
}

//récupération de tous les jours disponible dans le mois
$indispoInMonth = getAnavailableDaysInOneMonth($month, $dispo);

//récupération de tous les jours réservés
$allReservedDays = [];
foreach ($reservedDays as $oneOccurence) {
    $oneOccurence = getDaysBetweenBounds($oneOccurence['debut_reservation'], $oneOccurence['fin_reservation']);
    foreach ($oneOccurence as $day) {
        $allReservedDays[] = $day;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/calendar.css">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <title>Calendrier |
        <?php echo $libelle ?>
    </title>
</head>

<!-- inclusion du header -->
<?php include "header.php" ?>

<body>
    <div id="centerCalendar">
        <form id="calendar" method="post">

            <input type="hidden" name="year" value=<?php echo $year ?>>
            <input type="hidden" name="month" value=<?php echo $month ?>>
            <input id="prevOrNext" type="hidden" name="prevOrNext" value="">
            <input id="allDays" type="hidden" name="allDays" value="">
            <input id="prixBase" type="hidden" name="prixBase" value=<?php echo $prixBase ?>>

            <?php
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthsInFrench = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
            $monthName = $monthsInFrench[$month - 1];

            $firstDayOfMonth = date("N", mktime(0, 0, 0, $month, 1, $year));
            $currentDayOfWeek = $firstDayOfMonth - 1;
            ?>

            <div id="directionChoice">
                <input id="prevYear" type="submit" value="<">
                <h4 id="currentMonth">
                    <?php echo "$monthName $year" ?>
                </h4>
                <input id="nextYear" type="submit" value=">">
            </div>
            <table>
                <tr>
                    <th>Lun.</th>
                    <th>Mar.</th>
                    <th>Mer.</th>
                    <th>Jeu.</th>
                    <th>Ven.</th>
                    <th>Sam.</th>
                    <th>Dim.</th>
                </tr>
                <tr>
                    <?php
                    // Remplir les cases vides avant le premier jour du mois
                    for ($i = 0; $i < $currentDayOfWeek; $i++) {
                        echo '<td></td>';
                    }

                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $cDay = addZero($year . "-" . $month . "-$i");

                        //ouverture de la cellule
                        echo '<td class="cal-data">';

                        //affichage du prix
                        if (array_key_exists($cDay, $indispoInMonth)) {
                            $prix = $indispoInMonth[$cDay][0];
                            if ($indispoInMonth[$cDay][1] != 1) {
                                $checked = true;
                            } else {
                                $checked = false;
                            }
                        } else {
                            $prix = $prixBase;
                            $checked = false;
                        }

                        //variable affectée à true si le jour est réservé, false sinon
                        $isReserved = (in_array($cDay, $allReservedDays)) ? true : false;
                        if ($isReserved) {
                            error_log("day reserved : $cDay");
                        }
                        //variable affectée à true si le jour est passé, false sinon
                        $isPassed = (strtotime($cDay) < time()) ? true : false;
                        echo '<label class="nbjourcalend" for="case-' . $i . '">' . $i . ' <div class="prixdujour"> <p> ' . $prix . ' €</p> </div>  </label> ';

                        //affichage du jour
                        echo '<input type="hidden" name="reservations" class="reservations" value=' . $isReserved . '>';
                        echo '<input type="hidden" name="oldDates" class="oldDates" value=' . $isPassed . '>';
                        echo '<input class="nbcasejourcalend" id="case-' . $i . '" type="checkbox" value=' . $i . ' ' . ($checked ? 'checked' : '') . '>';
                        echo '</td>';

                        // Passer à la nouvelle ligne chaque fois que nous atteignons la fin d'une semaine
                        $currentDayOfWeek++;
                        if ($currentDayOfWeek == 7) {
                            echo '</tr><tr>';
                            $currentDayOfWeek = 0; // Réinitialiser le compteur pour la nouvelle ligne
                        }
                    }

                    // Remplir les cases vides après le dernier jour du mois
                    while ($currentDayOfWeek < 7) {
                        echo '<td></td>';
                        $currentDayOfWeek++;
                    }
                    ?>
                </tr>
            </table>

            <div id="menuSelectedDays">
                <div id="barreselectionjour">
                    <p id="date">
                        <?php echo $monthName . ' ' . $year ?>
                    </p>
                    <div>
                        <p id="isAvailableText">logement disponible</p>&nbsp;&nbsp;
                        <label class="switch">
                            <input type="checkbox" id="isAvailable" name="isAvailable">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div id="changeInformations">
                    <div id="changeInformationsAlignement">
                        <div id="containerPrixAndRaison">
                            <div>
                                <label for="prix">Prix (en €)</label>
                                <input class="quantity" id="prix" name="prix" type="number" value="">
                            </div>
                            <div id="raisonIndispoContainer">
                                <label for="raisonIndispo">Raison d'indisponibilité</label>
                                <input class="quantity" id="raisonIndispo" name="raisonIndispo" type="text" value="">
                            </div>
                        </div>
                    </div>
                    <button type="button" id="valideyy">Valider</button>
                </div>
            </div>
        </form>
    </div>
    <script src="asset/js/calendar.js"></script>
</body>

</html>