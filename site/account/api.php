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
<script src="asset/js/api.js"></script>
<div id="comptePaiementAPI" class="comptePage" style="width: 100%; position:relative;">
  <div id="compteAPIContainer">
    <div id="apiSection">
      <table>
        <thead>
          <tr><th>Information :</th></tr>
        </thead>
        <tbody>
          <tr><td style="font-weight: 300;">
          Faites attention au permissions que vous donnez à vos clés et qui a accès à celles ci.<br>
          Pour raisons de confidentialité, il est recommandé d'utiliser la permission "accès calendrier" seule lorsque vous souhaitez partager un calendrier de disponibilité de vos logements avec un autre site
          </td></tr>
        </tbody>
      </table>
      <?php
      if (empty($result)) {
        echo "<p>Aucune clé API n'est associée à cet ID.</p>";
      } else {
      ?>
      <form method="post" action="api_save.php">
        <table>
          <thead>
            <tr>
              <th>Nom de la clé API</th>
              <th>Privilèges</th>
              <th>Accès Calendrier (disponibilité)</th>
              <th>Mise Indispo</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($result as $index => $row) {
              $info = $row;
              ?>
              <tr>
                <td>
                  <?php echo $info["cle"]; ?>
                </td>
                <td>
                  <input type="checkbox" name="<?php echo $info['cle'] . "_p"; ?>" <?php echo ($info['privilegie'] ? "checked" : "") ?> disabled>
                </td>
                <td>
                  <input type="checkbox" name="<?php echo $info['cle'] . "_c"; ?>" <?php echo ($info['accescalendrier'] ? "checked" : "") ?>>
                </td>
                <td>
                  <input type="checkbox" name="<?php echo $info['cle'] . "_i"; ?>" <?php echo ($info['miseindispo'] ? "checked" : "") ?>>
                </td>
                <td class="separBar">
                </td>
                <td>
                  <a onclick="openAModal('myModalapi<?php echo $index ?>')"><img src="asset/icons/bleu/trash.svg" alt=""></a>
                  <div class="confirmation-modal" id="myModalapi<?php echo $index ?>">
                    <div class="modal-content" class="choix_logements">
                      <p>Êtes-vous sûr de vouloir supprimer ?</p>
                      <input type="hidden" name="confirmDelete" value="<?php echo $info["cle"] ?>">
                      <div class="boutons_choix">
                        <a onclick="closeAModal('myModalapi<?php echo $index ?>')"
                          class="confirm-button">Annuler</a>
                        <a href="api_del.php?confirmDelete=<?php echo $info["cle"] ?>"
                          id="confirmChange">Confirmer</a>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
        <button type="submit">Appliquer les changements</button>
      </form>
      <?php } ?>
      <form action="api_add.php" method="POST">
          <table>
            <tbody>
              <tr>
                <td>Nouvelle clé</td>
                <td>Accès calendrier : <input type="checkbox" name="cal"></td>
                <td>Mise indispo : <input type="checkbox" name="indisp"></td>
                <td class="separBar" style='width: 0'></td>
                <td width="1%"><button type="submit">Ajouter</button></td>
              </tr>
            </tbody>
          </table>
          <p style="font-weight: 300">Note : Seul un administrateur du site peut créer des clés privilégiées</p>
      </form>
    </div>
    <?php
      $query = $dbh->prepare("SELECT * FROM test.ical WHERE id_compte = :id");
      $query->bindParam(':id', $id, PDO::PARAM_INT);
      $query->execute();
      $result = $query->fetchAll();
    ?>
    <div id="icalSection" style="margin-top: 1em">
        <?php
        if (empty($result)) {
          echo "<p>Aucun ICAL n'a été généré avec ce compte</p>";
        } else {
        ?>
        <form method="post" action="api_save.php">
          <table>
            <thead>
              <tr>
                <th>Token ICAL</th>
                <th>ID logement</th>
                <th>Date de début</th>
                <th>Date de fin</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result as $index => $row) {
                $info = $row;
                ?>
                <tr>
                  <td>
                    <a href="ical.php?t=<?php echo $info["token"] ?>" style="color: black"><?php echo $info["token"] ?></a>
                  </td>
                  <td>
                    <?php echo $info["id_logement"] ?>
                  </td>
                  <td>
                    <?php echo $info["date_debut"] ?>
                  </td>
                  <td>
                    <?php echo $info["date_fin"] ?>
                  </td>
                  <td class="separBar">
                  </td>
                  <td>
                    <a onclick="openAModal('myModalical<?php echo $index ?>')"><img src="asset/icons/bleu/trash.svg" alt=""></a>
                    <div class="confirmation-modal" id="myModalical<?php echo $index ?>">
                      <div class="modal-content" class="choix_logements">
                        <p>Êtes-vous sûr de vouloir supprimer ?</p>
                        <input type="hidden" name="confirmDelete" value="<?php echo $info["token"] ?>">
                        <div class="boutons_choix">
                          <a onclick="closeAModal('myModalical<?php echo $index ?>')"
                            class="confirm-button">Annuler</a>
                          <a href="ical_del.php?confirmDelete=<?php echo $info["token"] ?>"
                            id="confirmChange">Confirmer</a>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
          <p style="font-weight: 300">Note : le lien universel pour les ICAL est <u><?php echo $_SERVER['SERVER_NAME'] . "/ical.php?t=votre_token" ?></u></p>
        </form>
        <?php } ?>
        <form action="ical_add.php" method="POST">
          <table>
            <tbody>
              <tr>
                <td>Nouveau token</td>
                <td>ID logement : <input type="number" name="id_log"></td>
                <td>Date de début : <input type="date" name="debut"></td>
                <td>Date de fin : <input type="date" name="fin"></td>
                <td class="separBar"></td>
                <td><button type="submit">Ajouter</button></td>
              </tr>
            </tbody>
          </table>
      </form>
      </div>
  </div>
</div>