<?php   /* Début interaction avec la bdd */

include('connect_params.php');
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    //$villes = $dbh->prepare("SELECT DISTINCT localisation FROM test.logement WHERE en_ligne = true");
    //$villes->execute();
    //$villes = $villes->fetchAll();
    //$villes = array_column($villes, 'localisation');

    /* Gestion des options de tri en attribuant à une variable la donnée dans la bdd sur laquel on veut trier les logements via un ORDER BY */

    if (isset($_GET["tri"])){
      switch ($_GET["tri"]) {


        case "Prix : Ordre Croissant":  // CAS POUR LE TRIX POUR LE PRIX

          $tri="prix_ttc";
          $sens="crois";


          break; // FIN DE CAS POUR TRI DU PRIX

        case "Prix : Ordre Décroissant":  // CAS POUR LE TRIX POUR LE PRIX

          $tri="prix_ttc";
          $sens="desc";



          break; // FIN DE CAS POUR TRI DU PRIX




        case "Récent": // CAS POUR LE TRI EN FONCTION DES LOGEMENT LES PLUS RECENT

          $tri="id_logement";
          $sens="desc";
          break; // FIN DU CAS EN FONCTION DES PLUS RECENT

        case "Ancien": // CAS POUR LE TRI EN FONCTION DES LOGEMENT LES PLUS RECENT

          $tri="id_logement";
          $sens="crois";
          break; // FIN DU CAS EN FONCTION DES PLUS RECENT


        case "Avis": // CAS EN FONCTION DE LA NOTE DU LOGEMENT

          $sens="desc";
          $tri="note_logement";
          break; // FIN DU CAS EN FONCTION DE LA NOTE DU LOGEMENT


        default: // CAS ERREUR
            echo "Erreur";
        
      }
    } else {
      $tri="id_logement";
      $sens="crois";
    }

    if (isset($_GET["test"])){ /* Gestion des filtres */

      /* Ici, on défini 2 variables, 1 qui va se remlire de "AND <donnée de la bdd>=<donnée rechercher par l'utilisateur> " afin d'être ajouter à la requête pour obtenir de multiple where. La deuxième s'occupe de savoir quoi joindre pour que la requête fonctionne (par exemple pout les installations et autres). */

      $filtre="";
      $join="";
      //print_r($_GET);
      foreach ($_GET as $ind => $val){


        if ($ind == "Personne" && $val !="") {
          $filtre.="AND nb_pers_max=$val ";
        }

        if ($ind == "type"){
          //$filtre.="AND nature_logement='$val' ";
          foreach($val as $type){
            $filtre.="AND nature_logement='$type' ";
          }
        }

        if ($ind == "amena"){
          $join.=" NATURAL JOIN test.amenagement ";
          foreach($val as $amena){
            $filtre.="AND nom_amenagement='$amena' ";
          }
        }

        if ($ind == "service"){
          $join.=" NATURAL JOIN test.service ";
          foreach($val as $service){
            $filtre.="AND nom_service='$service' ";
          }  
        }

        if ($ind == "instal"){
          $join.=" NATURAL JOIN test.installation ";          
          foreach($val as $instal){
            $filtre.="AND nom_installation='$instal' ";
          }  
        }

        if ($ind == "PrixMin" && $val !="") {
          $filtre.="AND prix_ttc>=$val ";
        }

        if ($ind == "PrixMax" && $val !="") {
          $filtre.="AND prix_ttc<=$val ";
        }

        if ($ind == "recherche" && $val !="") {
          $filtre.="AND LOWER(localisation) LIKE LOWER('%$val%') ";
        }

      }
    }

    /* Requete pour récupérer tout les logements correspondant au critère des filtres. Séparation en 2 cas : tri croissant ou décroissant. puis chaque cas se sépare en 2 : filtré ou pas filtré. Si c'est filtré, applique la requete pour retrouver les logements correspondant au filtres, sinon effectue une requête normal. Pour différention les tri croissant et décroissant, ajout de DESC arpès le ORDER BY. Le seuls cas ou c'est vraiment différent c'est quand les filtres sont sélectionner. On recherche d'abbord l'id de tout les logements correspondant à la recherche, puis après, on fait une 2ème requète pour obtenir toutes les informations des logements pout les afficher. S'en suit après, et dans tout les cas, l'affichages des logements  */

    if ($sens=="crois"){
      $nb_log_rech=0;

      if ($filtre!=""){
        foreach($dbh->query("SELECT DISTINCT id_logement, prix_ttc, note_logement
        FROM (
            SELECT *
            FROM test.logement
            $join
            WHERE en_ligne = true
            $filtre
        ) AS subquery ORDER BY $tri;", PDO::FETCH_ASSOC) as $row) {



          $id_log_req=$row['id_logement'];
  
          foreach($dbh->query("SELECT * from test.logement WHERE id_logement=$id_log_req", PDO::FETCH_ASSOC) as $row){
            $i=0;
            $id=$row["id_logement"];
            $info=$row;
            ?>
  
            <a href="logement.php?id=<?php echo($id);?>" class="maison">
                          <div id="triangle"></div>
                          <div class="etoile">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z"
                                fill="white" />
                            </svg>
                            <p></p>
                    
                          </div>
                          <?php
                            foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {
  
                              $photo[$i]=$row;
                              $i++;
                              
                          }
                          ?>
                    
                          <img src="asset/img/logements/<?php echo($photo[0]["id_image"]); ?>.<?php echo($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">
                    
                          <p class="ville"><?php  echo($info["libelle_logement"]);  ?>, <?php echo($info["localisation"]); ?></p>
                          <p class="prix"><strong><?php  echo($info["prix_ttc"]."€");  ?></strong> par nuit</p>
                      </a>
  
            <?php
            $info=[];
            $photo=[];
            $nb_log_rech++;
          }


        }

        if ($nb_log_rech==0){
          echo "pas de logement disponible pour cette recherche";
        }
      } else {
        foreach($dbh->query("SELECT DISTINCT * from test.logement WHERE en_ligne=true ORDER BY $tri", PDO::FETCH_ASSOC) as $row) {
          $i=0;
          $id=$row["id_logement"];
          $info=$row;
          ?>
  
          <a href="logement.php?id=<?php echo($id);?>" class="maison">
                        <div id="triangle"></div>
                        <div class="etoile">
                          <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z"
                              fill="white" />
                          </svg>
                          <p></p>
                  
                        </div>
                        <?php
                          foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {
  
                            $photo[$i]=$row;
                            $i++;
                            
                        }
                        ?>
                  
                        <img src="asset/img/logements/<?php echo($photo[0]["id_image"]); ?>.<?php echo($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">
                  
                        <p class="ville"><?php  echo($info["libelle_logement"]);  ?>, <?php echo($info["localisation"]); ?></p>
                        <p class="prix"><strong><?php  echo($info["prix_ttc"]."€");  ?></strong> par nuit</p>
                    </a>
  
          <?php
          $info=[];
          $photo=[];
      }
      if($i==0){
        echo("pas de logement correspondant à votre recherche");
      }
      }

      $dbh = null;
    }  else {

      if ($filtre!=""){
        $results = $dbh->query("SELECT DISTINCT id_logement, prix_ttc, note_logement
        FROM (
            SELECT *
            FROM test.logement
            $join
            WHERE en_ligne = true
            $filtre
        ) AS subquery ORDER BY $tri DESC;", PDO::FETCH_ASSOC);
        
        $nb_log_rech=0;


        foreach($results as $row) {
          
          $nb_log_rech++;

          $id_log_req=$row['id_logement'];

          foreach($dbh->query("SELECT * from test.logement WHERE id_logement=$id_log_req ORDER BY $tri", PDO::FETCH_ASSOC) as $row){
            $i=0;
            $id=$row["id_logement"];
            $info=$row;
            ?>
  
            <a href="logement.php?id=<?php echo($id);?>" class="maison">
                          <div id="triangle"></div>
                          <div class="etoile">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z"
                                fill="white" />
                            </svg>
                            <p></p>
                    
                          </div>
                          <?php
                            foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {
  
                              $photo[$i]=$row;
                              $i++;
                              
                          }
                          ?>
                    
                          <img src="asset/img/logements/<?php echo($photo[0]["id_image"]); ?>.<?php echo($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">
                    
                          <p class="ville"><?php  echo($info["libelle_logement"]);  ?>, <?php echo($info["localisation"]); ?></p>
                          <p class="prix"><strong><?php  echo($info["prix_ttc"]."€");  ?></strong> par nuit</p>
                      </a>
  
            <?php
            $info=[];
            $photo=[];
          }


      }

      if($nb_log_rech==0){
        echo("pas de logement correspondant à votre recherche");
      }

      } else {

      foreach($dbh->query("SELECT DISTINCT * from test.logement WHERE en_ligne=true ORDER BY $tri DESC", PDO::FETCH_ASSOC) as $row) {
        $i=0;
        $id=$row["id_logement"];
        $info=$row;
        ?>

        <a href="logement.php?id=<?php echo($id);?>" class="maison">
                      <div id="triangle"></div>
                      <div class="etoile">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M7.5 0L9.18386 5.52786H14.6329L10.2245 8.94427L11.9084 14.4721L7.5 11.0557L3.09161 14.4721L4.77547 8.94427L0.367076 5.52786H5.81614L7.5 0Z"
                            fill="white" />
                        </svg>
                        <p></p>
                
                      </div>
                      <?php
                        foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement=$id", PDO::FETCH_ASSOC) as $row) {

                          $photo[$i]=$row;
                          $i++;
                          
                      }
                      ?>
                
                      <img src="asset/img/logements/<?php echo($photo[0]["id_image"]); ?>.<?php echo($photo[0]["extension_image"]) ?>" withd="300" height="225" alt="img">
                
                      <p class="ville"><?php  echo($info["libelle_logement"]);  ?>, <?php echo($info["localisation"]); ?></p>
                      <p class="prix"><strong><?php  echo($info["prix_ttc"]."€");  ?></strong> par nuit</p>
                  </a>

        <?php
        $info=[];
        $photo=[];
        
    }
    $dbh = null;

    }


  }

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

/* Fin de la partie requète avec la bdd */

?>