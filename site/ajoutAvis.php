<?php
session_start();
error_reporting(0);
?>


<?php
include('connect_params.php');


if (isset($_POST["note"])){

try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT MAX(id_avis) FROM test.avis;";

    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $id_avis_max = $stmt->fetchAll();


} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$aviiiiiiiiis= $id_avis_max[0]["max"] + 1;



try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "INSERT INTO test.avis (id_avis, contenu, id_logement, note_avis, id_compte, date_avis) VALUES
(:id_avis, :newValue, :id_log, :note, :id_compte, NOW());";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':newValue', $_POST["descriptionAvis"], PDO::PARAM_STR);
    $stmt->bindParam(':id_avis', $aviiiiiiiiis, PDO::PARAM_INT);
    $stmt->bindParam(':id_log', $_POST["id"], PDO::PARAM_INT);
    $stmt->bindParam(':note', $_POST["note"], PDO::PARAM_INT);
    $stmt->bindParam(':id_compte', $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->execute();

    $rowCount2 = $stmt->rowCount();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}



try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT note_avis FROM test.avis WHERE id_logement = :id_logement";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_logement', $_POST["id"], PDO::PARAM_INT);
    $stmt->execute();
    $aviss = $stmt->fetchAll();

    $rrrrrrrrrrrowCount2 = $stmt->rowCount();

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


$som = 0;
foreach ($aviss as $tabNote) {
    $som += $tabNote["note_avis"];
}
$moy = round($som / $rrrrrrrrrrrowCount2, 1);

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $query = "UPDATE test.logement SET note_logement = :newValue WHERE id_logement = :id_logement;";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam('newValue', $moy, PDO::PARAM_STR);
    $stmt->bindParam('id_logement', $_POST['id'], PDO::PARAM_STR);
    $stmt->execute();
    $post = $stmt->fetch();

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
$idlogement=$_POST["id"];







try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT id_compte FROM test.logement WHERE id_logement = :id_logement";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_logement', $_POST["id"], PDO::PARAM_INT);
    $stmt->execute();
    $idcompte = $stmt->fetchAll();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT note_logement FROM test.logement WHERE id_compte = :id_compte";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_compte', $idcompte[0]["id_compte"], PDO::PARAM_INT);
    $stmt->execute();
    $noteLog = $stmt->fetchAll();
    $rrrrrrrrrrrowCount3 = $stmt->rowCount();

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$som = 0;
foreach ($noteLog as $tabNote) {
    $som += $tabNote["note_logement"];
}
$moy = round($som / $rrrrrrrrrrrowCount3, 1);


try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $query = "UPDATE test.proprietaire SET note_proprio = :newValue WHERE id_compte = :id_compte;";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam('newValue', $moy, PDO::PARAM_STR);
    $stmt->bindParam('id_compte', $idcompte[0]["id_compte"], PDO::PARAM_STR);
    $stmt->execute();
    $post = $stmt->fetch();

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
$idlogement=$_POST["id"];


}



?>

<script type="text/javascript">
    window.location.href = "logement.php?id=<?php echo($_POST["id"]); ?>";
</script>