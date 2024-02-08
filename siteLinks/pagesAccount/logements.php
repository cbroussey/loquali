<div id="compteLogementReservation" class="comptePage">
  <div id="compteLogementPropo">
    <div class="compteAjout_log">
      <a href="newLogement.php">
        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M23.7768 9.92104H15.7411V1.88532C15.7411 0.899275 14.9414 0.0996094 13.9554 0.0996094H12.1696C11.1836 0.0996094 10.3839 0.899275 10.3839 1.88532V9.92104H2.34821C1.36217 9.92104 0.5625 10.7207 0.5625 11.7068V13.4925C0.5625 14.4785 1.36217 15.2782 2.34821 15.2782H10.3839V23.3139C10.3839 24.2999 11.1836 25.0996 12.1696 25.0996H13.9554C14.9414 25.0996 15.7411 24.2999 15.7411 23.3139V15.2782H23.7768C24.7628 15.2782 25.5625 14.4785 25.5625 13.4925V11.7068C25.5625 10.7207 24.7628 9.92104 23.7768 9.92104Z"
            fill="#F5F5F5" />
        </svg>
        <p>Créer une annonce</p>
      </a>
    </div>


    <?php

    try {
      $id = $_SESSION['userId'];
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $query = "SELECT COUNT(*) FROM test.logement WHERE id_compte = $id;";
      $stmt = $dbh->prepare($query);
      $stmt->execute();
      $nbLogements = $stmt->fetch();

      if ($nbLogements['count'] == 0) {
        ?>
        <p id="AucunLogementCompte">Vous n'avez aucun logement en ligne</p>
        <?php
      }


      foreach ($dbh->query("SELECT * FROM test.logement WHERE id_compte = $id", PDO::FETCH_ASSOC) as $row) {

        $info = $row;
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT min(id_image) FROM test.photo_logement NATURAL JOIN test.image WHERE id_logement = :id_logement;";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_logement', $info["id_logement"], PDO::PARAM_STR);
        $stmt->execute();
        $photo = $stmt->fetch();

        $query = "SELECT extension_image FROM test.image WHERE id_image = :id_image;";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_image', $photo["min"], PDO::PARAM_STR);
        $stmt->execute();
        $extention = $stmt->fetch();  
        ?>

        <div class="compteListeUnLogement">

          <div class="toutLogement">
            <img src="asset/img/logements/<?php echo ($photo["min"]); ?>.<?php echo $extention["extension_image"] ?>"
              width="100%" height="100%" alt="" class="imgListeLogementProprio">

            <div class="unLogement">
              <div class="log_info_liste">
                <h2>
                  <?php echo ($info["libelle_logement"]); ?>,
                  <?php echo ($info["localisation"]); ?>
                </h2>
                <p class="logement_prix">
                  <?php echo ($info["prix_ttc"]); ?> €, par nuit
                </p>
              </div>

            </div>

          </div>

          <div class="compteBtnListeLogement">
            <a href="calendar.php?id=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/calendar.svg"
                alt=""></a>
            <a href="modifLogement.php?id=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/modification.svg"
                alt=""></a>


            <a onclick="openModal3()"><img src="asset/icons/bleu/trash.svg" alt=""></a>

            <div class="confirmation-modal" id="myModal3">
              <div class="modal-content">
                <span class="close" onclick="closeModal3()">&times;</span>
                <p>Êtes-vous sûr de vouloir supprimer ?</p>
                <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
                <a href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>"
                  class="confirm-button">Confirmer</a>
              </div>
            </div>
            <a href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>"><img
                src="asset/icons/bleu/troisPoints.svg" alt=""></a>

          </div>


        </div>

        <div class="compteSeparateur1">a</div>

        <?php
      }
    } catch (PDOException $e) {
      print "Erreur !: " . $e->getMessage() . "<br/>";
      die();
    }
    ?>
  </div>
</div>