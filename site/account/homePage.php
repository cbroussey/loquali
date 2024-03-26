<div id="compteAccueil" class="comptePage">

  <div class="accueil">
    <p id="bonjour">Bonjour <?php echo ($infos['prenom']) ?> !</p>
    <div class="container">

      <label for="fileInput">
        <?php //récupération du nom de l'image (avec extension)

        if ($images = opendir('asset/img/profils/')) {
          while (false !== ($fichier = readdir($images))) {
            $imgInfos = pathinfo($fichier);
            if ($imgInfos['filename'] == $_SESSION['userId']) {
              $pathName = 'asset/img/profils/' . $fichier;
              break;
            }
          }
          if ($pathName == '') {
            $pathName = 'asset/img/profils/default.jpg';
          }
          closedir($images);
        }
        ?>
        <img src=<?php echo $pathName ?> alt="" id="photoProfil">
      </label>

      <input type="file" id="fileInput" style="display: none;" accept="image/jpeg, image/png" onchange="changeProfilePhoto(event)">
      <form method="post" enctype="multipart/form-data" id="profileForm">

        <div class="middle">

          <input type="file" id="profilImage" name="profilImage" accept="image/*" style="color:transparent;" onchange="submitForm()" />

          <label for="photo" id="custom-button-pp" aria-placeholder=""> <img src="asset/icons/blanc/photo.svg" alt="">
          </label>

          <input type="file" id="photo" name="photo[]" multiple />

        </div>
      </form>
    </div>
    <p id="textchange">changer votre photo de profil</p>


    <div id="caseAccueil">
      <p class="bienvenue">Accédez à votre <a href="pagePersoProprio.php" class="lienPagePerso">page personnel</a>.</p>
      <?php
      if ($_SESSION['userType'] === 'proprietaire') {
      ?>
        <div class="separateurCompte"></div>
        <a href="newLogement.php" id="comptePro">Créer une annonce</a>
      <?php
      }
      ?>
    </div>

  </div>
</div>