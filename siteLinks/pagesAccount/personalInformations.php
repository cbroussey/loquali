<div id="compteInfosPerso" class="comptePage">
    <div class="lignes">
        <form method="post" action="compte.php">
            <p>Nom</p>
            <p id="nom" class="displayInfos"><?php echo ($infos['nom']) ?></p>
            <input type="text" name="nom" id="nom" class="modifInfos" cols="30" rows="10" value="<?php echo ($infos['nom']) ?>">
            <a href="#" id="boutonInfos" class="modificationsBtn boutonInfosstyle" alt="">Modifier</a>
            <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton">
        </form>
    </div>

    <div class="separateurgenre"></div>

    <div class="lignes">
        <form method="post" action="compte.php">
            <p>Prénom</p>
            <p id="prenom" class="displayInfos2"><?php echo ($infos['prenom']) ?></p>
            <input type="text" name="prenom" id="prenom" class="modifInfos2" cols="30" rows="10" value="<?php echo ($infos['prenom']) ?>">
            <a href="#" id="boutonInfos" class="modificationsBtn2 boutonInfosstyle" alt="">Modifier</a>
            <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton2">
        </form>
    </div>

    <div class="separateurgenre"></div>

    <div class="lignes">
        <form method="post" action="compte.php">
            <p>Adresse e-mail</p>
            <p id="adresse_mail" class="displayInfos3"><?php echo ($infos['adresse_mail']) ?></p>
            <input type="text" name="adresse_mail" id="adresse_mail" class="modifInfos3" cols="30" rows="10" value="<?php echo ($infos['adresse_mail']) ?>">
            <a href="#" id="boutonInfos" class="modificationsBtn3 boutonInfosstyle" alt="">Modifier</a>
            <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton3">
        </form>
    </div>

    <div class="separateurgenre"></div>

    <div class="lignes">
        <form method="post" action="compte.php">
            <p>Numéros de téléphone</p>
            <?php
            $tel = isset($telephone['numero']) ? $telephone["numero"] :  'Information non renseignée';
            ?>
            <p id="numero" class="displayInfos4"><?php echo htmlentities($tel) ?></p>
            <input type="text" name="numero" id="numero" class="modifInfos4" cols="30" rows="10" value="<?php echo ($telephone['numero']) ?>">
            <a href="#" id="boutonInfos" class="modificationsBtn4 boutonInfosstyle" alt="">Modifier</a>
            <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton4">
        </form>
    </div>

    <div class="separateurgenre"></div>

    <div class="lignes">
        <form method="post" action="compte.php">
            <p>Adresse</p>
            <?php
            $adresse = isset($infos['adresse']) ? $infos["adresse"] :  'Information non renseignée';
            ?>
            <p id="adresse" class="displayInfos5"><?php echo htmlentities($adresse) ?></p>
            <input type="text" name="adressePersonne" id="adressePersonne" class="modifInfos5" cols="30" rows="10" value="<?php echo ($infos['adresse']) ?>">
            <a href="#" id="boutonInfos" class="modificationsBtn5 boutonInfosstyle" alt="">Modifier</a>
            <input type="submit" name="submit" value="Enregistrer" id="modifEnregistrer" class="modifBouton5">
        </form>
    </div>
</div>