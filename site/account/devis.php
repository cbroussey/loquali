<div id="compteDevis" class="comptePage">
  <?php
  $devisCount = 0;

  if ($_SESSION['userType'] == 'client') {


    $id_client = $_SESSION['userId'];
    foreach ($dbh->query("SELECT * FROM test.reservation 
                          INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation
                          
                          WHERE id_compte = $id_client", PDO::FETCH_ASSOC) as $row) {
      $devisExist = true;
      $id_logement = $row["id_logement"];
      $id_reservation = $row["id_reservation"];

      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $proprio_id = $dbh->query("SELECT * from test.logement WHERE id_logement =$id_logement", PDO::FETCH_ASSOC)->fetch()["id_compte"];

      $query = "SELECT * FROM test.compte NATURAL JOIN test.proprietaire WHERE id_compte = :id_compte";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('id_compte', $proprio_id, PDO::PARAM_STR);
      $stmt->execute();
      $proprio = $stmt->fetch();


      if ($images = opendir('asset/img/profils/')) {
        while (false !== ($fichier = readdir($images))) {
          $imgInfos = pathinfo($fichier);
          if ($imgInfos['filename'] == $proprio_id) {
            $pathName = 'asset/img/profils/' . $fichier;
            break;
          }
        }
        if ($pathName == '') {
          $pathName = 'asset/img/profils/default.jpg';
        }
        closedir($images);
      }

      if (!empty($row["prix_devis"])) {
        $devisCount++;

  ?>
        <div class="page_devis">

          <div class="liste_devis">
            <form class="devis" method="POST" action="demandeDevis.php">
              <input type="hidden" name="qui" value="client">
              <input type="hidden" name="reservation" value="<?= $row["id_reservation"] ?>">
              <input type="hidden" name="id" value="<?= $row["id_logement"] ?>">
              <img src="<?= $pathName ?>" alt="" class="logo">
              <div class="infos-devis">
                <div class="infos-header">
                  <h3><?= $proprio["nom_affichage"] ?></h3>
                  <p class="date"><?= explode(" ", $row["date_devis"])[0] ?></p>
                </div>
                <div class="infos-header">
                  <p>Vous a envoyé un devis.</p>
                  <button type="submit" class="voir-devis">Voir</button>
                </div>
              </div>
            </form>
            <div class="compteSeparateur1">a</div>
          </div>

        </div>

      <?php

      }
    }
  } else {
    $id_proprio = $_SESSION['userId'];
    foreach ($dbh->query("SELECT * FROM test.reservation 
                            INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation 
                            INNER JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
                            INNER JOIN test.compte ON test.reservation.id_compte = test.compte.id_compte
                            WHERE test.logement.id_compte = $id_proprio", PDO::FETCH_ASSOC) as $row) {
      $id_logement = $row["id_logement"];

      $id_reservation = $row["id_reservation"];

      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $client_id = $dbh->query("SELECT * FROM test.reservation WHERE id_reservation = $id_reservation")->fetch()["id_compte"];

      if ($images = opendir('asset/img/profils/')) {
        while (false !== ($fichier = readdir($images))) {
          $imgInfos = pathinfo($fichier);
          if ($imgInfos['filename'] == $client_id) {
            $pathName = 'asset/img/profils/' . $fichier;
            break;
          }
        }
        if ($pathName == '') {
          $pathName = 'asset/img/profils/default.jpg';
        }
        closedir($images);
      }
      $devisCount++;

      ?>
      <div class="page_devis">

        <div class="liste_devis">
          <form class="devis" method="POST" action="demandeDevis.php">
            <input type="hidden" name="qui" value="proprietaire">
            <input type="hidden" name="reservation" value="<?= $row["id_reservation"] ?>">
            <input type="hidden" name="id" value="<?= $row["id_logement"] ?>">
            <img src="<?= $pathName ?>" alt="" class="logo">
            <div class="infos-devis">
              <div class="infos-header">
                <h3><?= $row["nom_affichage"] ?></h3>
                <p class="date"><?= explode(" ", $row["date_devis"])[0] ?></p>
              </div>
              <div class="infos-header">
                <p>Vous a fait une demande de devis.</p>
                <button type="submit" class="voir-devis">Créer</button>
              </div>
            </div>
          </form>
          <div class="compteSeparateur1">a</div>
        </div>

      </div>
    <?php }
  }
  // Afficher le message s'il n'y a pas de devis
  if ($devisCount === 0) {
    ?>
    <p id="AucunDevisCompte">Vous n'avez aucuns devis pour le moment</p>
  <?php
  }
  ?>
</div>