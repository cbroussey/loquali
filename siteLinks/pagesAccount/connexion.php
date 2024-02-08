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
  </div>
</div>

<div class="confirmation-modal" id="myModal">
  <div class="modal-content">
    <h2>Êtes-vous sûr de vouloir supprimer ce compte ?</h2>
    <form method="GET" action="compte.php">
      <input type="hidden" name="confirmDelete" value="<?php echo $id ?>">
      <div class="button-container">
        <button id="confirmChange" onclick="closeModal()">Annuler</button>
        <button class="confirm-button">Confirmer</button>
      </div>
    </form>
  </div>
</div>