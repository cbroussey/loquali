<div id="compteLogementReservation" class="comptePage">
  <div class="LesReservations">
    <?php
    try {
      $id = $_SESSION['userId'];
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $query = "SELECT COUNT(*) FROM test.reservation WHERE id_compte = $id;";
      $stmt = $dbh->prepare($query);
      $stmt->execute();
      $nbLogements = $stmt->fetch();

      if ($nbLogements['count'] == 0) {
        ?>
        <p id="AucuneReservCompte">Vous n'avez aucunes réservations pour le moment</p>
        <?php
      } else {

        foreach ($dbh->query("SELECT * FROM test.reservation 
                  INNER JOIN test.devis ON test.reservation.id_reservation = test.devis.id_reservation
                  INNER JOIN test.logement ON test.reservation.id_logement = test.logement.id_logement
                  WHERE test.reservation.id_compte = $id;", PDO::FETCH_ASSOC) as $row) {

          $info = $row;
          $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
          $query = "SELECT * FROM test.photo_logement NATURAL JOIN test.image WHERE id_logement = :id_logement;";

          $stmt = $dbh->prepare($query);
          $stmt->bindParam('id_logement', $info["id_logement"], PDO::PARAM_STR);
          $stmt->execute();
          $photo = $stmt->fetch();


          ?>





          <div class="compteListeUnLogement">
            <div class="toutLogement">

            <form class="devis" method="POST" action="consulterRes.php">
              <input type="hidden" name="qui" value="client">
              <input type="hidden" name="reservation" value="<?= $row["id_reservation"] ?>">
              <input type="hidden" name="id" value="<?= $row["id_logement"] ?>">
              
              <button type="submit">
                <div id=imajedelespagna>
                  <img src="asset/img/logements/<?php echo ($photo["id_image"]); ?>.<?php echo $photo["extension_image"] ?>"
                    width="100%" height="100%" alt="" class="imgListeLogementProprio">
                </div>
              </button>
              
              
            </form>

              <div class="unLogement">
                <div class="log_info_liste">
                  <h2>
                    <?php echo ($info["nature_logement"]); ?>
                    <?php echo ($info["type_logement"]); ?>,
                    <?php echo ($info["localisation"]); ?>
                  </h2>
                  <p class="laNoche">
                    <?php echo ($info["prix_devis"]); ?> €, par nuit
                  </p>
                  <div class="noteAvis">
                    <p>

                      <?php
                      $datedeb = $info["debut_reservation"];
                      $datefin = $info["fin_reservation"];

                      // Convertir la chaîne en objet de date
                      $dateObjdeb = new DateTime($datedeb);
                      $dateObjfin = new DateTime($datefin);

                      // Formater la date selon le format souhaité
                      $result1 = $dateObjdeb->format('d M');
                      $result2 = $dateObjfin->format('d M');

                      // Afficher le résultat
                      echo "$result1 -> $result2";
                      ?>

                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="compteBtnListeLogement">
              <a href="modifLogement.php?id=<?php echo ($info["id_logement"]) ?>"><img src="asset/icons/bleu/modification.svg"
                  alt=""></a>

              <a onclick="openModal2()"><img src="asset/icons/bleu/trash.svg" alt=""></a>
              <div class="confirmation-modal" id="myModal2">
                <div class="modal-content">
                  <span class="close" onclick="closeModal2()">&times;</span>
                  <p>Êtes-vous sûr de vouloir supprimer ?</p>
                  <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
                  <a href="logement.php?confirmDelete=<?php echo ($info["id_logement"]) ?>"
                    class="confirm-button">Confirmer</a>
                </div>
              </div>
            </div>
          </div>

          <div class="compteSeparateur1">a</div>
          <?php
        }
      }
    } catch (PDOException $e) {
      echo "Erreur : problème avec les réservations.";
    }
    ?>
  </div>
</div>