

<div id="comptePaiementAPI" class="comptePage">
<div class="liste_carte">
  <?php
  $id_client = $_SESSION['userId'];
  $cartes = $dbh->query("SELECT * FROM test.cb INNER JOIN test.compte ON test.compte.id_compte = test.cb.id_compte WHERE test.compte.id_compte = $id_client", PDO::FETCH_ASSOC)->fetchAll();
  if (count($cartes) > 0) {
    foreach ($cartes as $row) { ?>
      <form class="carte" method="POST" action="deleteCarte.php">
        <input type="hidden" name="nb_cb" value="<?= $row["numero_carte"] ?>">
        <img src="./asset/img/mastercard.png" alt="logo mastercard" class="carte-logo">
        <div class="texte">
          <h3>Mr. <?= $row["nom"] . ' ' . $row["prenom"] ?></h3>
          <?php
          $numeroCarteFormate = preg_replace('/(\d{4})\d{8}(\d{3})/', '$1 **** **** $2', $row["numero_carte"]);
          ?>
          <p><?= $numeroCarteFormate ?></p>
        </div>
        <input type="submit" value="Supprimer">
      </form>
      <div class="separateur3"></div>
    <?php }
  } else { ?>
    <p id="AucuneCarte">Vous n'avez aucune carte enregistrée</p>
  <?php } ?>
</div>
</div>
