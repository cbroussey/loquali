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
      <div class="button-container">
        <button  onclick="closeModal()">Annuler</button>
        <a href="compte.php?confirmDelete=<?php echo $id ?>" id="confirmChange" class="confirm-button">Confirmer</a>
      </div>
    </form>
  </div>
</div>