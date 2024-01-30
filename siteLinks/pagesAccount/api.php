<?php
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {

    foreach ($_POST as $key => $value) {
      if (strpos($key, 'privilegie') !== false) {
        $privilegie = $value == 'on' ? 1 : 0;

        $updateQuery = $dbh->prepare("UPDATE test.api SET privilegie = :privilegie WHERE cle = :cle AND id_compte = :id_compte");
        $updateQuery->bindParam(':privilegie', $privilegie, PDO::PARAM_INT);
        $updateQuery->bindParam(':cle', $value['cle'], PDO::PARAM_STR);
        $updateQuery->bindParam(':id_compte', $_SESSION['userId'], PDO::PARAM_INT);
        $updateQuery->execute();
      } elseif (strpos($key, 'accescalendrier') !== false) {
        $accescalendrier = $value == 'on' ? 1 : 0;

        $updateQuery = $dbh->prepare("UPDATE test.api SET accescalendrier = :accescalendrier WHERE cle = :cle AND id_compte = :id_compte");
        $updateQuery->bindParam(':accescalendrier', $accescalendrier, PDO::PARAM_INT);
        $updateQuery->bindParam(':cle', $value['cle'], PDO::PARAM_STR);
        $updateQuery->bindParam(':id_compte', $_SESSION['userId'], PDO::PARAM_INT);
        $updateQuery->execute();
      } elseif (strpos($key, 'miseindispo') !== false) {
        $indispo = $value == 'on' ? 1 : 0;

        $updateQuery = $dbh->prepare("UPDATE test.api SET miseindispo = :miseindispo WHERE cle = :cle AND id_compte = :id_compte");
        $updateQuery->bindParam(':miseindispo', $indispo, PDO::PARAM_INT);
        $updateQuery->bindParam(':cle', $value['cle'], PDO::PARAM_STR);
        $updateQuery->bindParam(':id_compte', $_SESSION['userId'], PDO::PARAM_INT);
        $updateQuery->execute();
      }
    }

    header("Location: compte.php");
    exit();
  } catch (PDOException $e) {
    echo "probleme";
  }
}

$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$query = $dbh->prepare("SELECT * FROM test.api WHERE id_compte = :id");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();

$result = $query->fetchAll();
?>

<div id="comptePaiementAPI" style="width: 100%; position:relative;">
  <div id="apiSection" style="width:60%; position:absolute; top:50%; left:50%; transform: translate(-50%,-50%);">
    <?php
    if (empty($result)) {
      echo "<p>Aucune clé API n'est associée à cet ID.</p>";
    }
    ?>

    <form method="post" action="api_save.php">
      <table>
        <thead>
          <tr>
            <th>Nom de la clé API</th>
            <th>Privilèges</th>
            <th>Accès Calendrier</th>
            <th>Mise Indispo</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($result as $index => $row) {
            $info = $row;
          ?>
            <tr>
              <td><?php echo $info["cle"]; ?></td>
              <td>
                <input type="checkbox" name="<?php echo $info['cle'] . "_p"; ?>" <?php echo ($info['privilegie'] ? "checked" : "") ?> disabled>
              </td>
              <td>
                <input type="checkbox" name="<?php echo $info['cle'] . "_c"; ?>" <?php echo ($info['accescalendrier'] ? "checked" : "") ?>>
              </td>
              <td>
                <input type="checkbox" name="<?php echo $info['cle'] . "_i"; ?>" <?php echo ($info['miseindispo'] ? "checked" : "") ?>>
              </td>
              <td>
              </td>
            </tr>
          <?php
            if ($index < count($result) - 1) {
              echo "<tr><td colspan='5'><hr></td></tr>";
            }
          }
          ?>
        </tbody>
      </table>
      <button type="submit">Appliquer les changements</button>
    </form>
  </div>
</div>