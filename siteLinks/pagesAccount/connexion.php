<div id="compteConnection" class="comptePage">
  <div class="lignes">
    <p>Mot de passe</p>
    <button class="modifications" id="modifmaj">Mettre à jour</button>
  </div>

  <div class="separateurgenre"></div>

  <div class="lignes">
    <p>Historique de l’appareil</p>
    <p class="displayInfos">Session en cours</p>
    <button id="accountDisconnect2" class="modifications">Se déconnecter</button>
  </div>

  <div class="separateurgenre"></div>

  <div class="lignes">
    <p>Compte</p>
    <p class="displayInfos">Désactivez votre compte</p>
    <button class="modifications" onclick="openModal()">Désactiver</button>
    <div class="confirmation-modal" id="myModal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Êtes-vous sûr de vouloir supprimer ce compte ?</p>
        <form method="GET" action="compte.php">
          <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
          <button class="confirm-button">Confirmer</button>
          <?php
          ?>
        </form>
      </div>
    </div>
  </div>

</div>