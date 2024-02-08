
<?php session_start();
error_reporting(0);?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/calendar.css">
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <title>Document</title>
</head>
<?php include "header.php" ?>
<body>
    <div id="centerCalendar">
        <form id="calendar" method="post">
            <?php
            include('connect_params.php');

            $idLogement = $_GET['id'];
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $query = "SELECT jour FROM test.planning WHERE id_logement = :id_logement;";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam('id_logement', $idLogement, PDO::PARAM_STR);
            $stmt->execute();
            $dispo = $stmt->fetchAll();

            $dispo = array_map(function ($item) {
                return $item['jour'];
            }, $dispo);

            //print_r($dispo);

            if (!isset($_SESSION['dispo'])) {
                $_SESSION['dispo'] = [];
            }

            if (isset($_POST['year'])) {

                //passe au mois suivant si on clique sur suivant, au mois précédent sinon
                if ($_POST['prevOrNext'] == 'next') {
                    $month = $_POST['month'] + 1; 
                    $year = $_POST['year'];
                    if ($month > 12) {
                        $month = 1;
                        $year += 1;
                    }
                } else if ($_POST['prevOrNext'] == 'prev') {
                    $month = $_POST['month'] - 1;
                    $year = $_POST['year'];
                    if ($month <= 0) {
                        $month = 12;
                        $year -= 1;
                    }
                }

                $query = "DELETE FROM test.planning WHERE EXTRACT(MONTH FROM jour) = :currentMonth AND id_logement = :idLogement";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam('currentMonth', $month, PDO::PARAM_INT);
                $stmt->bindParam('idLogement', $idLogement, PDO::PARAM_INT);
                $stmt->execute();

                foreach($_POST['dispo'] as $day) {
                    $day = $_POST['year'] . "-" . $_POST['month'] . "-$day";
                    $query = "INSERT INTO test.planning (disponibilite, jour, id_logement) VALUES (:dispo, :jour, :idLogement)";
                    $stmt = $dbh->prepare($query);
                    $boolean = false;
                    $stmt->bindParam('dispo', $boolean, PDO::PARAM_BOOL);
                    $stmt->bindParam(':jour', $day, PDO::PARAM_STR);
                    $stmt->bindParam('idLogement', $idLogement, PDO::PARAM_INT);
                    $stmt->execute();
                }
                if ($_POST['prevOrNext'] == 'submit') {
                    header("Location: compte.php");
                    exit();
                }

                ?>

                <input type="hidden" name="year" value=<?php echo $year ?>>
                <input type="hidden" name="month" value=<?php echo $month ?>>

                <?php
            } else {
                $today = explode('-', date("Y-m-d"));
                $month = $today[1];
                $year = $today[0];

                $query = "DELETE FROM test.planning WHERE EXTRACT(MONTH FROM jour) = :currentMonth AND id_logement = :idLogement";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam('currentMonth', $month, PDO::PARAM_INT);
                $stmt->bindParam('idLogement', $idLogement, PDO::PARAM_INT);
                $stmt->execute();
                ?>

                <input type="hidden" name="year" value=<?php echo $year ?>>
                <input type="hidden" name="month" value=<?php echo $month ?>>

                <?php
            }
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthName = date("F", mktime(0, 0, 0, $month, 1, date("Y")));
            ?>

            <div id="directionChoice">
                <input id="prevYear" type="submit" value="<">
                <h4 id="joyeuxanniversaire"><?php echo "$monthName $year" ?></h4>
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
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $currentDayOfWeek = $firstDayOfMonth - 1;


                    // Remplir les cases vides avant le premier jour du mois
                    for ($i = 0; $i < $currentDayOfWeek; $i++) {
                        echo '<td></td>';
                    }

                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $cDay = $year."-".$month."-$i";
                        $checked = in_array($cDay, $dispo);
                        echo '<td class="cal-data">';
                        echo '<label class="nbjourcalend" for="case-'.$i.'">' . $i . ' <div class="prixdujour"> <p> prix : </p> </div>  </label> ';
                        echo '<input class="nbcasejourcalend" id="case-'.$i.'" type="checkbox" name="dispo[]" value=' . $i . ' '.($checked ? 'checked' : '').'>';
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
        </form>
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
                        <input class="quantity" id="PrixMin" name="PrixMin" type="number" pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMin"] != "") { ?> value="<?php echo ($_POST["PrixMin"]) ?>" <?php   } ?>>
                    </div>
                    <p id="petitebarredeseparation">-</p>
                    <div class="b4r3" id="adroiteuuu">
                        <p>Nouveau prix</p>
                        <input class="quantity" id="PrixMax" name="PrixMax" type="number" pattern="(29|35|22|56)[0-9]{3}" <?php if ($_POST["PrixMax"] != "") { ?> value="<?php echo ($_POST["PrixMax"]) ?>" <?php   } ?>>
                    </div>
                </div>
                <input type="submit" value="valider" id="valideyy">
            </div>
        </div>
    </div>
    <script src="asset/js/calendar.js"></script>
</body>
</html>