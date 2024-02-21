<?php
session_start();
error_reporting(0);

include('connect_params.php');

//récupération de l'id du logement
$idLogement = $_GET['id'];

//initialisation bdd
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

//récupération du prix de base du logement
$query = "SELECT prix_base_ht FROM test.logement WHERE id_logement = :id_logement;";
$stmt = $dbh->prepare($query);
$stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
$stmt->execute();
$prixBase = $stmt->fetch()['prix_base_ht'];

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
    }

} else {
    $today = explode('-', date("Y-m-d"));
    $month = $today[1];
    $year = $today[0];
}

//récupération de tous les jours disponible dans le mois
$dispoInMonth = getAnavailableDaysInOneMonth($month, $dispo);

echo "<pre>";
print_r($dispoInMonth);
echo "</pre>";

//récupération des jours cochés par l'utilisateur au mois précédent/suivant
if (isset($_POST['dispo'])) {

    print_r($_POST['dispo']);

    foreach ($_POST['dispo'] as $day) {
        if (!in_array($day, $dispo)) {
            $day = $_POST['year'] . "-" . $_POST['month'] . "-$day";
            print_r($day);
            $query = "INSERT INTO test.planning (disponibilite, jour, id_logement) VALUES (:dispo, :jour, :idLogement)";
            $stmt = $dbh->prepare($query);
            $boolean = false;
            $stmt->bindParam('dispo', $boolean, PDO::PARAM_BOOL);
            $stmt->bindParam(':jour', $day, PDO::PARAM_STR);
            $stmt->bindParam('idLogement', $idLogement, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

//si l'utilisateur clique sur "valider", il est redirigé vers son compte
if ($_POST['prevOrNext'] == 'submit') {
    header("Location: compte.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/calendar.css">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <title>Document</title>
</head>

<!-- inclusion du header -->
<?php include "header.php" ?>

<body>
    <div id="centerCalendar">
        <form id="calendar" method="post">

            <input type="hidden" name="year" value=<?php echo $year ?>>
            <input type="hidden" name="month" value=<?php echo $month ?>>

            <?php
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthName = date("F", mktime(0, 0, 0, $month, 1, date("Y")));
            ?>

            <div id="directionChoice">
                <input id="prevYear" type="submit" value="<">
                <h4 id="joyeuxanniversaire">
                    <?php echo "$monthName $year" ?>
                </h4>
                <input id="nextYear" type="submit" value=">">
            </div>

            <input id="prevOrNext" type="hidden" name="prevOrNext" value="">

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
                    $firstDayOfMonth = date("N", mktime(0, 0, 0, $month, 1, $year));
                    $currentDayOfWeek = $firstDayOfMonth - 1;


                    // Remplir les cases vides avant le premier jour du mois
                    for ($i = 0; $i < $currentDayOfWeek; $i++) {
                        echo '<td></td>';
                    }

                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $cDay = addZero($year . "-" . $month . "-$i");

                        //ouverture de la cellule
                        echo '<td class="cal-data">';

                        //affichage du prix
                        if (in_array($cDay, $dispoInMonth)) {
                            $prix = $dispoInMonth[$cDay][0];
                        } else {
                            $prix = $prixBase;
                        }
                        echo '<label class="nbjourcalend" for="case-' . $i . '">' . $i . ' <div class="prixdujour"> <p>' . $prix . '€</p> </div>  </label> ';

                        //affichage du jour
                        echo '<input class="nbcasejourcalend" id="case-' . $i . '" type="checkbox" name="dispo[]" value=' . $i . ' ' . ($checked ? 'checked' : '') . '>';
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

            <div id="petitmenuprix">
                <div id="barreselectionjour">
                    <p id="date">February 2024 / Mercredi 7</p>
                    <div>
                        <p>logement disponible</p>&nbsp;&nbsp;
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div id="leprixla">
                    <div id="leprixchangela">
                        <div class="b4r3">
                            <p>Prix actuel</p>
                            <input class="quantity" id="PrixMin" name="PrixMin" type="number"
                                pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMin"] != "") { ?>
                                    value="<?php echo ($_POST["PrixMin"]) ?>" <?php } ?>>
                        </div>
                        <p id="petitebarredeseparation">-</p>
                        <div class="b4r3" id="adroiteuuu">
                            <p>Nouveau prix</p>
                            <input class="quantity" id="PrixMax" name="PrixMax" type="number"
                                pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMax"] != "") { ?>
                                    value="<?php echo ($_POST["PrixMax"]) ?>" <?php } ?>>
                        </div>
                    </div>
                    <input type="submit" value="valider" id="valideyy">
                </div>
            </div>
        </form>
    </div>
    <script src="asset/js/calendar.js"></script>
</body>

</html>