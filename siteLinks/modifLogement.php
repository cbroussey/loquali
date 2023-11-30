<?php
    session_start();
    $linkAccount = 'connexion.php';
    if (isset($_SESSION['username'])) {
        $linkAccount = 'account.php';
    }

    $id=$_GET["id"];




    include('connect_params.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $query = "SELECT * FROM test.logement WHERE id_logement = :id_logement";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('id_logement', $id, PDO::PARAM_STR);
        $stmt->execute();
        $info = $stmt->fetch();







        foreach($dbh->query("SELECT * from test.photo_logement NATURAL JOIN test.image WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

            $photo[]=$row;
            $i++;
        }



        foreach($dbh->query("SELECT * from test.amenagement WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

            $amena[]=$row;
        }


        $nom_amena=[];
        foreach ($amena as $val){
            $nom_amena[]=$val["nom_amenagement"];
        }




        

        foreach($dbh->query("SELECT * from test.installation WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

            $instal[]=$row;
            $i++;
        }




        $nom_instal=[];
        foreach ($instal as $val){
            $nom_instal[]=$val["nom_installation"];
        }








        foreach($dbh->query("SELECT * from test.service WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

            $service[]=$row;
        }




        $nom_service=[];
        foreach ($service as $val){
            $nom_service[]=$val["nom_service"];
        }




        foreach($dbh->query("SELECT * from test.lit WHERE id_logement =$id", PDO::FETCH_ASSOC) as $row) {

            $lit=$row;
        }

      



    }   catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="asset/css/headerAndFooter.css">
    <link rel="stylesheet" href="asset/css/style.css">
</head>

<body id="newLog">



    <div class="sticky_header_log">
    <header>
    <a href="">
      <img src="asset/img/logo.png" alt="logo">
    </a>
    <div></div>
    <div id="headerEmptyDiv"></div>
    <nav>
      <div>
        <svg width="30" height="30" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M23.7497 10.7258C22.7264 4.4244 20.3126 0 17.5035 0C14.6945 0 12.2807 4.4244 11.2573 10.7258H23.7497ZM10.728 17.5C10.728 19.0665 10.8127 20.5696 10.9609 22.0161H24.0391C24.1873 20.5696 24.272 19.0665 24.272 17.5C24.272 15.9335 24.1873 14.4304 24.0391 12.9839H10.9609C10.8127 14.4304 10.728 15.9335 10.728 17.5ZM33.6449 10.7258C31.6263 5.93448 27.5398 2.22984 22.4934 0.733871C24.2156 3.11895 25.4013 6.71069 26.0224 10.7258H33.6449ZM12.5066 0.733871C7.46723 2.22984 3.37366 5.93448 1.36217 10.7258H8.98467C9.59871 6.71069 10.7844 3.11895 12.5066 0.733871ZM34.4001 12.9839H26.3047C26.4529 14.4657 26.5376 15.9829 26.5376 17.5C26.5376 19.0171 26.4529 20.5343 26.3047 22.0161H34.393C34.7812 20.5696 35 19.0665 35 17.5C35 15.9335 34.7812 14.4304 34.4001 12.9839ZM8.46945 17.5C8.46945 15.9829 8.55414 14.4657 8.70236 12.9839H0.606977C0.225852 14.4304 0 15.9335 0 17.5C0 19.0665 0.225852 20.5696 0.606977 22.0161H8.6953C8.55414 20.5343 8.46945 19.0171 8.46945 17.5ZM11.2573 24.2742C12.2807 30.5756 14.6945 35 17.5035 35C20.3126 35 22.7264 30.5756 23.7497 24.2742H11.2573ZM22.5005 34.2661C27.5398 32.7702 31.6334 29.0655 33.6519 24.2742H26.0294C25.4083 28.2893 24.2226 31.881 22.5005 34.2661ZM1.36217 24.2742C3.38072 29.0655 7.46723 32.7702 12.5136 34.2661C10.7915 31.881 9.60577 28.2893 8.98467 24.2742H1.36217Z" fill="#F5F5F5" />
        </svg>
        <svg id="headerArrowLang" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.99141 13.4874C9.54926 14.1709 10.4552 14.1709 11.0131 13.4874L19.5816 2.98945C20.1395 2.30599 20.1395 1.19605 19.5816 0.512594C19.0238 -0.170866 18.1178 -0.170866 17.56 0.512594L10 9.77485L2.44003 0.518062C1.88218 -0.165399 0.976236 -0.165399 0.418387 0.518062C-0.139462 1.20152 -0.139462 2.31146 0.418387 2.99492L8.98695 13.4929L8.99141 13.4874Z" fill="#F5F5F5" />
        </svg>
      </div>
      <svg id="headerHamburger" width="28" height="31" viewBox="0 0 28 31" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect y="0.738281" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
        <rect y="13.6523" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
        <rect y="26.5645" width="28" height="3.52174" rx="1" fill="#F5F5F5" />
      </svg>
      <?php
      if (isset($_SESSION['userId'])) {
      ?>
        <h4><a href="">Messagerie</a></h4>
        <h4><a href="">Mes réservations</a></h4>
        <h4><a href="compteAccueil.php">Mon compte</a></h4>
      <?php } else {
      ?>
        <h4><a href="connexion.php">Se connecter</a></h4>
      <?php
      }
      ?>
    </nav>
    <div id="headerPopup">
      <ul>
        <li>français</li>
        <li>english</li>
        <li>español</li>
        <li>deutsch</li>
        <li>brezhonneg</li>
      </ul>
    </div>
    <div></div>
  </header>


        <div class="barre_fin_header_log"> <!-- Début de la barre de séparation du header -->
            <svg width="100%" height="23" viewBox="0 0 1920 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_15_355)">
                    <path d="M0 6H1920V9H0V6Z" fill="#97ADC1" />
                </g>
                <defs>
                    <filter id="filter0_d_15_355" x="-7" y="0" width="1940" height="23" filterUnits="userSpaceOnUse"
                        color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                            result="hardAlpha" />
                        <feOffset dx="3" dy="4" />
                        <feGaussianBlur stdDeviation="5" />
                        <feComposite in2="hardAlpha" operator="out" />
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_15_355" />
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_15_355" result="shape" />
                    </filter>
                </defs>
            </svg>
        </div> <!-- Fin de la barre de séparation du header -->


    </div> <!-- Fin du header -->


    <main>

        <div class="testdelamort" id="gros_div">

        

            <form action="apercuModif.php" method="post" enctype="multipart/form-data">

                <div class="page" id="page1">

                

                    <div class="choix_type_log_ajlog">
                        <h2>Type de votre logement</h2>
                        <div id="newLogementInput" class="barre_btn_choix_type_ajlog">
                            <input type="radio" id="type1" name="type" value="Maison" <?php if ($info["nature_logement"]=="Maison") { echo("checked"); }?>/>
                            <label for="type1" class="btn_choix_ajlog">Maison</label>
                            <input type="radio" id="type2" name="type" value="Appartement" <?php if ($info["nature_logement"]=="Appartement") { echo("checked"); } ?> />
                            <label for="type2" class="btn_choix_ajlog">Appartement</label>
                        </div>
                    </div>






                    <div class="partie_endroit_log_ajlog">

                        <h2>Ou est situé votre logement ?</h2>
                        
                        <div class="box_select_endroit_log_ajlog">

                            <div class="ligne_select_info_log_ajlog">
                                <select id="dep" name="dep" class="select_info_log_ajlog" >
                                    <option value="" disabled selected>Département</option>
                                    <option value="Finistère" <?php if ($info["departement"]=="Finistère") { echo("selected"); }?>>Finistère</option>
                                    <option value="Côtes-d'Armor" <?php if ($info["departement"]=="Côtes-d'Armor") { echo("selected"); }?>>Côtes-d'Armor</option>
                                    <option value="Ille-et-Vilaine" <?php if ($info["departement"]=="Ille-et-Vilaine") { echo("selected"); }?>>Ille-et-Vilaine</option>
                                    <option value="Morbihan" <?php if ($info["departement"]=="Morbihan") { echo("selected"); }?>>Morbihan</option>
                                </select>


                                <input type="text" id="ville" name="ville"  class="select_info_log_ajlog" required placeholder="Ville" value="<?php echo($info["localisation"]); ?>"/>
                            </div>

                            <div class="ligne_select_info_log_ajlog">

                                <input type="text" id="adresse" name="adresse" class="select_info_log_ajlog" required placeholder="Adresse postale" />

                                <input type="text" id="code_postal" name="code_postal" class="select_info_log_ajlog" required placeholder="Code postale" value="<?php echo($info["code_postal"]); ?>"/>

                            </div>

                            <div class="ligne_select_info_log_ajlog">

                                <input type="text" id="appartement" name="appartement" class="select_info_log_ajlog" required placeholder="Appartement, résidence.."/>

                            </div>

                        </div>
                    </div>


                    <div class="ajout_nombre_piece_ajlog">

                        <h2>Informations du logement</h2>

                        <div class="ligne_champ_nombre_ajlog">
                            <label for="nbChambre">Chambres</label>
                            <div class="number-input">
                                <button type="button" onclick="decrement('nbChambre')" class="minus">-</button>
                                <input class="quantity" id="nbChambre" name="nbChambre" value="<?php echo($info["nb_chambre"]); ?>" type="text">
                                <button type="button" onclick="increment('nbChambre')" class="plus">+</button>
                            </div>
                        </div>
            
                        <div class="ligne_champ_nombre_ajlog">
                            <label for="nbLit">Lit</label>
                            <div class="number-input">
                                <button type="button" onclick="decrement('nbLit')" class="minus">-</button>
                                <input class="quantity" id="nbLit" name="nbLit" value="<?php echo($lit["nombre_lit"]); ?>" type="text">
                                <button type="button" onclick="increment('nbLit')" class="plus">+</button>
                            </div>
                        
                        </div>

                        <div class="ligne_champ_nombre_ajlog">
                            <label for="nbSalle_bain">Salle de bains</label>
                            <div class="number-input">
                                <button type="button" onclick="decrement('nbSalle_bain')" class="minus">-</button>
                                <input class="quantity" id="nbSalle_bain" name="nbSalle_bain" value="<?php echo($info["nb_salle_de_bain"]); ?>" type="text">
                                <button type="button" onclick="increment('nbSalle_bain')" class="plus">+</button>
                            </div>
                        </div>

                        <div class="ligne_champ_nombre_ajlog">
                            <label for="Pieces">Pièces</label>
                            <div class="number-input">
                                <button type="button" onclick="decrement('Pieces')" class="minus">-</button>
                                <?php 
                                    $nb_pièce=explode("T",$info['type_logement']);
                                ?>
                                <input class="quantity" id="Pieces" name="Pieces" value="<?php echo($nb_pièce[1]); ?>" type="text">
                                <button type="button" onclick="increment('Pieces')" class="plus">+</button>
                            </div>
                        </div>

                        <div class="ligne_champ_nombre_ajlog">
                            <label for="Personne">Personne</label>
                            <div class="number-input">
                                <button type="button" onclick="decrement('Personne')" class="minus">-</button>
                                <input class="quantity" id="Personne" name="Personne" value="<?php echo($info["nb_pers_max"]); ?>" type="text">
                                <button type="button" onclick="increment('Personne')" class="plus">+</button>
                            </div>
                        </div>

                    </div>

            
                        
                    <div class="barre_btn_fin_form">
                        <a href="logement.html">Annuler</a>
                        <a href="#" class="Suivant" id="suivantBtn1">Suivant</a>
                    </div>
                    


                </div>

                <div class="page" id="page2">
                    
                    <div class="choix_aménagement_log_ajlog">
                        <h2>Aménagement / Installation de votre logement</h2>


                        <div class="barre_btn_choix_aménagement_ajlog">


                            <input type="checkbox" id="amena1" name="amena[]" value="jardin" 
                            <?php  
                                if (in_array("jardin",$nom_amena)) { echo("checked");}
                            ?>
                            />
                            <label for="amena1" class="btn_choix2_ajlog" >jardin</label>


                            <input type="checkbox" id="amena2" name="amena[]" value="balcon" 
                            <?php  
                                if (in_array("balcon",$nom_amena)) { echo("checked");}
                            ?>
                            />
                            <label for="amena2" class="btn_choix2_ajlog">balcon</label>


                            <input type="checkbox" id="amena3" name="amena[]" value="terrasse"
                            <?php  
                                if (in_array("terrasse",$nom_amena)) { echo("checked");}
                            ?>
                             />
                            <label for="amena3" class="btn_choix2_ajlog">terrasse</label>


                            <input type="checkbox" id="amena4" name="amena[]" value="parking" 
                            <?php  
                                if (in_array("parking",$nom_amena)) { echo("checked");}
                            ?>
                            />
                            <label for="amena4" class="btn_choix2_ajlog">parking</label>
                        </div>



                        <div class="barre_btn_choix_aménagement_ajlog">


                            <input type="checkbox" id="instal1" name="instal[]" value="climatisation" 
                            
                            <?php  
                                if (in_array("climatisation",$nom_instal)) { echo("checked");}
                            ?>
                            />
                            <label for="instal1" class="btn_choix2_ajlog">climatisation</label>


                            <input type="checkbox" id="instal2" name="instal[]" value="piscine" 
                            <?php  
                                if (in_array("piscine",$nom_instal)) { echo("checked");}
                            ?>
                            />
                            <label for="instal2" class="btn_choix2_ajlog">piscine</label>


                            <input type="checkbox" id="instal3" name="instal[]" value="jacuzzi" 
                            <?php  
                                if (in_array("jacuzzi",$nom_instal)) { echo("checked");}
                            ?>
                            />
                            <label for="instal3" class="btn_choix2_ajlog">jacuzzi</label>


                            <input type="checkbox" id="instal4" name="instal[]" value="hammam" 
                            <?php  
                                if (in_array("hammam",$nom_instal)) { echo("checked");}
                            ?>
                            />
                            <label for="instal4" class="btn_choix2_ajlog">hammam</label>

                            <input type="checkbox" id="instal5" name="instal[]" value="sauna" 
                            <?php  
                                if (in_array("sauna",$nom_instal)) { echo("checked");}
                            ?>
                            />
                            <label for="instal5" class="btn_choix2_ajlog">sauna</label>
                        </div>


                        <div class="barre_btn_choix_aménagement_ajlog">


                            <input type="checkbox" id="service1" name="service[]" value="linge" 
                            <?php  
                                if (in_array("linge",$nom_service)) { echo("checked");}
                            ?>
                            />
                            <label for="service1" class="btn_choix2_ajlog">linge</label>


                            <input type="checkbox" id="service2" name="service[]" value="ménage" 
                            <?php  
                                if (in_array("ménage",$nom_service)) { echo("checked");}
                            ?>
                            />
                            <label for="service2" class="btn_choix2_ajlog">ménage</label>


                            <input type="checkbox" id="service3" name="service[]" value="taxi" 
                            <?php  
                                if (in_array("taxi",$nom_service)) { echo("checked");}
                            ?>
                            />
                            <label for="service3" class="btn_choix2_ajlog">taxi</label>

                            <input type="checkbox" id="service4" name="service[]" value="repas" 
                            <?php  
                                if (in_array("repas",$nom_service)) { echo("checked");}
                            ?>
                            />
                            <label for="service4" class="btn_choix2_ajlog">repas</label>

                        </div>



                    </div>

                    <div class="ajout_photo_ajlog">
                        <h2>Ajouter vos photos</h2>
                        <div class="envoie_photo_ajlog">
                            <label for="photo" id="custom-button" aria-placeholder="">Joindre un ou plusieurs .png</label>
                            <input type="file" id="photo" name="photo[]" multiple/>
                        </div>
                        <div id="liste_img_ajlog">
                            <!-- Les images importées seront affichées ici -->



                                
                            <?php 
                            
                                foreach ($photo as $img) {
                                    ?>

                                        <img src="asset/img/logements/<?php echo($img["id_image"].".".$img["extension_image"]); ?>" alt="">

                                    <?php
                                }

                            ?>
                        </div>
                    </div>










                    <div class="ajout_titre_log_ajlog">



                        <h2>Titre de l'annonce</h2>

                        <input type="text" id="titre" name="titre" required placeholder="Magnifique maison en bord de mer" value="<?php echo($info["libelle_logement"]) ?>"/>




                    </div>



                    <div class="ajout_long_text1_log_ajlog">


                        <h2>Description du logement</h2>


                        <textarea id="description" class="txt_area_ajlog" name="description" rows="7" cols="100" placeholder="Magnifique maison en bord de mer" ><?php echo($info["descriptif"]); ?></textarea>

                    </div>


                    <div class="ajout_long_text2_log_ajlog">


                        <h2>Règlement du logement</h2>


                        <textarea id="Règlement" class="txt_area_ajlog" name="Règlement" rows="7" cols="100" placeholder="Magnifique maison en bord de mer"><?php echo($info["reglement_interieur"]); ?></textarea>

                    </div>

                    <div class="ajout_long_text1_log_ajlog">


                        <h2>Information d'arrivé</h2>


                        <textarea id="info_arrive" class="txt_area_ajlog" name="info_arrive" rows="7" cols="100" placeholder="Magnifique maison en bord de mer"><?php echo($info["info_arrivee"]); ?></textarea>

                    </div>

                    <div class="ajout_long_text2_log_ajlog">


                        <h2>Information de départ</h2>


                        <textarea id="info_depart" class="txt_area_ajlog" name="info_depart" rows="7" cols="100" placeholder="Magnifique maison en bord de mer"><?php echo($info["info_depart"]); ?></textarea>

                    </div>


                    <div class="barre_btn_fin_form">
                        <a href="#" id="retour1">Retour</a>
                        <a href="#" class="Suivant" id="suivantBtn2">Suivant</a>
                    </div>
                </div>

                <div class="page" id="page3">
                    

                    <div class="ajout_plage_disp_ajlog">



                        <h2>Plage de disponibilité</h2>

                        <p>Votre logement sera mis indisponible par défaut si aucune plage n'est choisie</p>

                    </div>



                    <div class="ajout_plage_disp2_ajlog">



                        <p>Date de disponibilité du logement :</p>

                        <div class="ligne_ajout_plage_disp_ajlog">

                            <div class="ajout_plage_disp_gauche_ajlog">
                                <p>disponibilité ponctuelle</p>
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="25" height="25" fill="#1D4C77"/>
                                    <circle cx="12.5" cy="12.5" r="2.5" fill="white"/>
                                </svg>

                            </div>



                            <div class="ajout_plage_disp_droite_ajlog">
                                <p>Ajouter une plage de dates</p>
                                <div class="ajout_plage_disp_droite_cal_ajlog">
                                    <svg width="60" height="46" viewBox="0 0 60 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M29.5064 23.8623H26.7206C26.261 23.8623 25.8849 23.458 25.8849 22.9639V19.9691C25.8849 19.4749 26.261 19.0706 26.7206 19.0706H29.5064C29.966 19.0706 30.3421 19.4749 30.3421 19.9691V22.9639C30.3421 23.458 29.966 23.8623 29.5064 23.8623ZM37.0278 22.9639V19.9691C37.0278 19.4749 36.6517 19.0706 36.1921 19.0706H33.4064C32.9467 19.0706 32.5706 19.4749 32.5706 19.9691V22.9639C32.5706 23.458 32.9467 23.8623 33.4064 23.8623H36.1921C36.6517 23.8623 37.0278 23.458 37.0278 22.9639ZM43.7135 22.9639V19.9691C43.7135 19.4749 43.3374 19.0706 42.8778 19.0706H40.0921C39.6324 19.0706 39.2564 19.4749 39.2564 19.9691V22.9639C39.2564 23.458 39.6324 23.8623 40.0921 23.8623H42.8778C43.3374 23.8623 43.7135 23.458 43.7135 22.9639ZM37.0278 30.1514V27.1566C37.0278 26.6624 36.6517 26.2581 36.1921 26.2581H33.4064C32.9467 26.2581 32.5706 26.6624 32.5706 27.1566V30.1514C32.5706 30.6455 32.9467 31.0498 33.4064 31.0498H36.1921C36.6517 31.0498 37.0278 30.6455 37.0278 30.1514ZM30.3421 30.1514V27.1566C30.3421 26.6624 29.966 26.2581 29.5064 26.2581H26.7206C26.261 26.2581 25.8849 26.6624 25.8849 27.1566V30.1514C25.8849 30.6455 26.261 31.0498 26.7206 31.0498H29.5064C29.966 31.0498 30.3421 30.6455 30.3421 30.1514ZM43.7135 30.1514V27.1566C43.7135 26.6624 43.3374 26.2581 42.8778 26.2581H40.0921C39.6324 26.2581 39.2564 26.6624 39.2564 27.1566V30.1514C39.2564 30.6455 39.6324 31.0498 40.0921 31.0498H42.8778C43.3374 31.0498 43.7135 30.6455 43.7135 30.1514ZM50.3992 10.6852V37.0394C50.3992 39.0234 48.9019 40.6331 47.0564 40.6331H22.5421C20.6965 40.6331 19.1992 39.0234 19.1992 37.0394V10.6852C19.1992 8.70117 20.6965 7.09147 22.5421 7.09147H25.8849V3.19824C25.8849 2.7041 26.261 2.2998 26.7206 2.2998H29.5064C29.966 2.2998 30.3421 2.7041 30.3421 3.19824V7.09147H39.2564V3.19824C39.2564 2.7041 39.6324 2.2998 40.0921 2.2998H42.8778C43.3374 2.2998 43.7135 2.7041 43.7135 3.19824V7.09147H47.0564C48.9019 7.09147 50.3992 8.70117 50.3992 10.6852ZM47.0564 36.5902V14.279H22.5421V36.5902C22.5421 36.8372 22.7301 37.0394 22.9599 37.0394H46.6385C46.8683 37.0394 47.0564 36.8372 47.0564 36.5902Z" fill="#F5F5F5"/>
                                    </svg>
                                </div>
                            </div>

                        </div>

                        
                        <div class="ligne_ajout_plage_disp_ajlog">

                            <div class="ajout_plage_disp_gauche_ajlog">
                                <p>disponibilité récurrente</p>
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="25" height="25" fill="#1D4C77"/>
                                    <circle cx="12.5" cy="12.5" r="2.5" fill="white"/>
                                </svg>

                            </div>



                            <div class="ajout_plage_disp_droite_ajlog">
                                <p>Ajouter une plage de dates</p>
                                <div class="ajout_plage_disp_droite_cal_ajlog">
                                    <svg width="60" height="46" viewBox="0 0 60 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M29.5064 23.8623H26.7206C26.261 23.8623 25.8849 23.458 25.8849 22.9639V19.9691C25.8849 19.4749 26.261 19.0706 26.7206 19.0706H29.5064C29.966 19.0706 30.3421 19.4749 30.3421 19.9691V22.9639C30.3421 23.458 29.966 23.8623 29.5064 23.8623ZM37.0278 22.9639V19.9691C37.0278 19.4749 36.6517 19.0706 36.1921 19.0706H33.4064C32.9467 19.0706 32.5706 19.4749 32.5706 19.9691V22.9639C32.5706 23.458 32.9467 23.8623 33.4064 23.8623H36.1921C36.6517 23.8623 37.0278 23.458 37.0278 22.9639ZM43.7135 22.9639V19.9691C43.7135 19.4749 43.3374 19.0706 42.8778 19.0706H40.0921C39.6324 19.0706 39.2564 19.4749 39.2564 19.9691V22.9639C39.2564 23.458 39.6324 23.8623 40.0921 23.8623H42.8778C43.3374 23.8623 43.7135 23.458 43.7135 22.9639ZM37.0278 30.1514V27.1566C37.0278 26.6624 36.6517 26.2581 36.1921 26.2581H33.4064C32.9467 26.2581 32.5706 26.6624 32.5706 27.1566V30.1514C32.5706 30.6455 32.9467 31.0498 33.4064 31.0498H36.1921C36.6517 31.0498 37.0278 30.6455 37.0278 30.1514ZM30.3421 30.1514V27.1566C30.3421 26.6624 29.966 26.2581 29.5064 26.2581H26.7206C26.261 26.2581 25.8849 26.6624 25.8849 27.1566V30.1514C25.8849 30.6455 26.261 31.0498 26.7206 31.0498H29.5064C29.966 31.0498 30.3421 30.6455 30.3421 30.1514ZM43.7135 30.1514V27.1566C43.7135 26.6624 43.3374 26.2581 42.8778 26.2581H40.0921C39.6324 26.2581 39.2564 26.6624 39.2564 27.1566V30.1514C39.2564 30.6455 39.6324 31.0498 40.0921 31.0498H42.8778C43.3374 31.0498 43.7135 30.6455 43.7135 30.1514ZM50.3992 10.6852V37.0394C50.3992 39.0234 48.9019 40.6331 47.0564 40.6331H22.5421C20.6965 40.6331 19.1992 39.0234 19.1992 37.0394V10.6852C19.1992 8.70117 20.6965 7.09147 22.5421 7.09147H25.8849V3.19824C25.8849 2.7041 26.261 2.2998 26.7206 2.2998H29.5064C29.966 2.2998 30.3421 2.7041 30.3421 3.19824V7.09147H39.2564V3.19824C39.2564 2.7041 39.6324 2.2998 40.0921 2.2998H42.8778C43.3374 2.2998 43.7135 2.7041 43.7135 3.19824V7.09147H47.0564C48.9019 7.09147 50.3992 8.70117 50.3992 10.6852ZM47.0564 36.5902V14.279H22.5421V36.5902C22.5421 36.8372 22.7301 37.0394 22.9599 37.0394H46.6385C46.8683 37.0394 47.0564 36.8372 47.0564 36.5902Z" fill="#F5F5F5"/>
                                    </svg>
                                </div>
                            </div>

                        </div>



                    </div>



                    <div class="ajout_plage_disp2_ajlog">



                        <p>Date d’indisponibilité du logement :</p>

                        <div class="ligne_ajout_plage_disp_ajlog">

                            <div class="ajout_plage_disp_gauche_ajlog">
                                <p>indisponibilité ponctuelle</p>
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="25" height="25" fill="#1D4C77"/>
                                    <circle cx="12.5" cy="12.5" r="2.5" fill="white"/>
                                </svg>

                            </div>



                            <div class="ajout_plage_disp_droite_ajlog">
                                <p>Ajouter une plage de dates</p>
                                <div class="ajout_plage_disp_droite_cal_ajlog">
                                    <svg width="60" height="46" viewBox="0 0 60 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M29.5064 23.8623H26.7206C26.261 23.8623 25.8849 23.458 25.8849 22.9639V19.9691C25.8849 19.4749 26.261 19.0706 26.7206 19.0706H29.5064C29.966 19.0706 30.3421 19.4749 30.3421 19.9691V22.9639C30.3421 23.458 29.966 23.8623 29.5064 23.8623ZM37.0278 22.9639V19.9691C37.0278 19.4749 36.6517 19.0706 36.1921 19.0706H33.4064C32.9467 19.0706 32.5706 19.4749 32.5706 19.9691V22.9639C32.5706 23.458 32.9467 23.8623 33.4064 23.8623H36.1921C36.6517 23.8623 37.0278 23.458 37.0278 22.9639ZM43.7135 22.9639V19.9691C43.7135 19.4749 43.3374 19.0706 42.8778 19.0706H40.0921C39.6324 19.0706 39.2564 19.4749 39.2564 19.9691V22.9639C39.2564 23.458 39.6324 23.8623 40.0921 23.8623H42.8778C43.3374 23.8623 43.7135 23.458 43.7135 22.9639ZM37.0278 30.1514V27.1566C37.0278 26.6624 36.6517 26.2581 36.1921 26.2581H33.4064C32.9467 26.2581 32.5706 26.6624 32.5706 27.1566V30.1514C32.5706 30.6455 32.9467 31.0498 33.4064 31.0498H36.1921C36.6517 31.0498 37.0278 30.6455 37.0278 30.1514ZM30.3421 30.1514V27.1566C30.3421 26.6624 29.966 26.2581 29.5064 26.2581H26.7206C26.261 26.2581 25.8849 26.6624 25.8849 27.1566V30.1514C25.8849 30.6455 26.261 31.0498 26.7206 31.0498H29.5064C29.966 31.0498 30.3421 30.6455 30.3421 30.1514ZM43.7135 30.1514V27.1566C43.7135 26.6624 43.3374 26.2581 42.8778 26.2581H40.0921C39.6324 26.2581 39.2564 26.6624 39.2564 27.1566V30.1514C39.2564 30.6455 39.6324 31.0498 40.0921 31.0498H42.8778C43.3374 31.0498 43.7135 30.6455 43.7135 30.1514ZM50.3992 10.6852V37.0394C50.3992 39.0234 48.9019 40.6331 47.0564 40.6331H22.5421C20.6965 40.6331 19.1992 39.0234 19.1992 37.0394V10.6852C19.1992 8.70117 20.6965 7.09147 22.5421 7.09147H25.8849V3.19824C25.8849 2.7041 26.261 2.2998 26.7206 2.2998H29.5064C29.966 2.2998 30.3421 2.7041 30.3421 3.19824V7.09147H39.2564V3.19824C39.2564 2.7041 39.6324 2.2998 40.0921 2.2998H42.8778C43.3374 2.2998 43.7135 2.7041 43.7135 3.19824V7.09147H47.0564C48.9019 7.09147 50.3992 8.70117 50.3992 10.6852ZM47.0564 36.5902V14.279H22.5421V36.5902C22.5421 36.8372 22.7301 37.0394 22.9599 37.0394H46.6385C46.8683 37.0394 47.0564 36.8372 47.0564 36.5902Z" fill="#F5F5F5"/>
                                    </svg>
                                </div>
                            </div>

                        </div>

                        
                        <div class="ligne_ajout_plage_disp_ajlog">

                            <div class="ajout_plage_disp_gauche_ajlog">
                                <p>indisponibilité récurrente</p>
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="25" height="25" fill="#1D4C77"/>
                                    <circle cx="12.5" cy="12.5" r="2.5" fill="white"/>
                                </svg>

                            </div>



                            <div class="ajout_plage_disp_droite_ajlog">
                                <p>Ajouter une plage de dates</p>
                                <div class="ajout_plage_disp_droite_cal_ajlog">
                                    <svg width="60" height="46" viewBox="0 0 60 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M29.5064 23.8623H26.7206C26.261 23.8623 25.8849 23.458 25.8849 22.9639V19.9691C25.8849 19.4749 26.261 19.0706 26.7206 19.0706H29.5064C29.966 19.0706 30.3421 19.4749 30.3421 19.9691V22.9639C30.3421 23.458 29.966 23.8623 29.5064 23.8623ZM37.0278 22.9639V19.9691C37.0278 19.4749 36.6517 19.0706 36.1921 19.0706H33.4064C32.9467 19.0706 32.5706 19.4749 32.5706 19.9691V22.9639C32.5706 23.458 32.9467 23.8623 33.4064 23.8623H36.1921C36.6517 23.8623 37.0278 23.458 37.0278 22.9639ZM43.7135 22.9639V19.9691C43.7135 19.4749 43.3374 19.0706 42.8778 19.0706H40.0921C39.6324 19.0706 39.2564 19.4749 39.2564 19.9691V22.9639C39.2564 23.458 39.6324 23.8623 40.0921 23.8623H42.8778C43.3374 23.8623 43.7135 23.458 43.7135 22.9639ZM37.0278 30.1514V27.1566C37.0278 26.6624 36.6517 26.2581 36.1921 26.2581H33.4064C32.9467 26.2581 32.5706 26.6624 32.5706 27.1566V30.1514C32.5706 30.6455 32.9467 31.0498 33.4064 31.0498H36.1921C36.6517 31.0498 37.0278 30.6455 37.0278 30.1514ZM30.3421 30.1514V27.1566C30.3421 26.6624 29.966 26.2581 29.5064 26.2581H26.7206C26.261 26.2581 25.8849 26.6624 25.8849 27.1566V30.1514C25.8849 30.6455 26.261 31.0498 26.7206 31.0498H29.5064C29.966 31.0498 30.3421 30.6455 30.3421 30.1514ZM43.7135 30.1514V27.1566C43.7135 26.6624 43.3374 26.2581 42.8778 26.2581H40.0921C39.6324 26.2581 39.2564 26.6624 39.2564 27.1566V30.1514C39.2564 30.6455 39.6324 31.0498 40.0921 31.0498H42.8778C43.3374 31.0498 43.7135 30.6455 43.7135 30.1514ZM50.3992 10.6852V37.0394C50.3992 39.0234 48.9019 40.6331 47.0564 40.6331H22.5421C20.6965 40.6331 19.1992 39.0234 19.1992 37.0394V10.6852C19.1992 8.70117 20.6965 7.09147 22.5421 7.09147H25.8849V3.19824C25.8849 2.7041 26.261 2.2998 26.7206 2.2998H29.5064C29.966 2.2998 30.3421 2.7041 30.3421 3.19824V7.09147H39.2564V3.19824C39.2564 2.7041 39.6324 2.2998 40.0921 2.2998H42.8778C43.3374 2.2998 43.7135 2.7041 43.7135 3.19824V7.09147H47.0564C48.9019 7.09147 50.3992 8.70117 50.3992 10.6852ZM47.0564 36.5902V14.279H22.5421V36.5902C22.5421 36.8372 22.7301 37.0394 22.9599 37.0394H46.6385C46.8683 37.0394 47.0564 36.8372 47.0564 36.5902Z" fill="#F5F5F5"/>
                                    </svg>
                                </div>
                            </div>

                        </div>



                    </div>




                    <div class="barre_btn_fin_form">
                        <a href="#" id="retour2">Retour</a>
                        <a href="#" class="Suivant" id="suivantBtn3">Suivant</a>
                    </div>
                </div>

                <div class="page" id="page4">
                    


                    <div class="ajouter_prix_ajlog">



                        <div class="haut_ajout_prix_ajlog">
                            <h2>Fixer votre prix</h2>
                            <div class="prix_log_ajlog">
                                <input type="text" id="prix" name="prix" required oninput="calculerPrix();" onkeypress="return isNumberKey(event);" value="<?php echo($info["prix_base_ht"]) ?>" />
                                <h1> € la nuit</h1>
                            </div>
                        </div>
                        
                        <div class="bas_ajout_prix_ajlog">
                            <div class="ligne_tabprix_ajlog">
                                <p>Prix de base</p>
                                <p id="prixDeBase">0 €</p>
                            </div>
                            <div class="ligne_tabprix_ajlog">
                                <p>Frais de service du voyageur</p>
                                <p id="fraisService">0 €</p>
                            </div>


                            <div class="barre_sep_tabprix_ajlog">
                                <svg width="100%" height="3" viewBox="0 0 1041 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="1041" height="3" fill="#97ADC1"/>
                                </svg>
                            </div>


                            <div class="ligne_tabprix_ajlog">
                                <p>Prix total</p>
                                <p id="prixTotal">0 €</p>
                            </div>
                            <div class="ligne_tabprix_ajlog">
                                <p>Prix total pour 1 semaine</p>
                                <p id="prixTotalSem">0 €</p>
                            </div>
                        </div>


                        <div class="droit_prix_ajlog">

                            <p>Politique d'annulation</p>
                            <ul>
                                <li>Remboursement de tout si c'est pas 14 jour avant</li>
                                <li>rembourse rien sinon</li>
                            </ul>
                        </div>


                    </div>


                    <input type="text" id="id_loge" name="id_loge" value="<?php echo($id) ?>"/>

                    <div class="barre_btn_fin_form">
                        <a href="#" id="retour3">Retour</a>
                        <input type="submit" class="Suivant" value="Prévisualiser" />
                    </div>
                </div>


            </form>

        </div>

        <div id="menu">
            <div id="choix">
                <a href="compteAccueil.php" class="bouton">Mon compte</a>

                <a href="" class="bouton">Mes réservation</a>

                <a href="" class="bouton">Messagerie</a>
                
            <div id="separe"></div>

                <p>Changer la langue</p>
                <div id="langues">
                    <a href="">Français</a>
                    <div id="separe2"></div>
                    <a href="">Anglais</a>
                </div>
            </div>
        </div>

    </main>

    <footer>

<div id="infosFooter">
  <div id="footerCercleLogo">
    <img src="asset/img/logoRond.svg" alt="logo">
  </div>
  <div id="textefooter">
    <div id="infosLegal">
      <h2>Informations légales</h2>
      <ul>
        <li><a href="">Plan du site</a></li>
        <li><a href="">Mentions légales</a></li>
        <li><a href="">Conditions générales de ventes</a></li>
        <li><a href="">Données personnelles</a></li>
        <li><a href="">Gestions des cookies</a></li>
      </ul>
    </div>
    <div id="support">
      <h2>Support client</h2>
      <a href="">Contacter le support</a>
    </div>
    <div id="reseaux">
      <h2>Suivez nous</h2>
      <div id="logoReseaux">
        <a href=""><img src="asset/icons/blanc/facebook.svg" alt=""></a>
        <a href=""><img src="asset/icons/blanc/instagram.svg" alt=""></a>
        <a href=""><img src="asset/icons/blanc/steam.svg" alt=""></a>
      </div>
    </div>
    <div id="contact">
      <h2>Nous contacter</h2>
      <p>Rue Édouard Branly, 22300 Lannion</p>
      <p>02 96 46 93 00</p>
      <p>iut-lannion.univ-rennes.fr</p>
    </div>
  </div>
</div>

<div class="basFooter">
  <p>Copyright @ 2023 LoQuali.com</p>
</div>

</footer>
    <script src="asset/js/newLogement.js"></script>
</body>

</html>