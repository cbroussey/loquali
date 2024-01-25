drop schema if exists test cascade;
create schema test;
set schema 'test';


create table image(
    id_image SERIAL not null,
    extension_image VARCHAR(5),
    constraint image_pk primary key (id_image)
);

create table compte(
    id_compte serial not null,
    identifiant varchar(255) unique,
    mdp varchar(255),
    nom_affichage varchar(255),
    date_creation date,
    nom varchar(255),
    prenom varchar(255),
    adresse_mail varchar(255) check (adresse_mail ~ '^[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9\.-]+\.\w{2,4}$'),
    ville varchar(255),
    code_postal varchar(255),
    adresse varchar(255),
    derniere_operation timestamp,
    photo_de_profil integer,
    piece_identite integer,
    constraint compte_pk primary key(id_compte),
    constraint compte_fk_pfp foreign key(photo_de_profil) references image(id_image)ON DELETE CASCADE,
    constraint compte_fk_identite foreign key(piece_identite) references image(id_image)ON DELETE CASCADE
);  

create table client(
    id_compte integer not null,
    note_client numeric(3,2) check (note_client >= 0 and note_client <= 5),
    -- civilite varchar(255), -- non-précisé pour le client, on peut empêcher la discrimination
    constraint client_pk primary key (id_compte),
    constraint client_fk_compte foreign key (id_compte) references compte(id_compte)ON DELETE CASCADE
);

create table proprietaire(
    id_compte integer not null,
    description varchar(255),
    note_proprio numeric(3,2) check (note_proprio >= 0 and note_proprio <= 5),
    civilite varchar(255) check (civilite in ('M', 'F', 'Mme')), -- nécessaire ici pcq précisé
    rib varchar(34) check (rib ~ '^\w{2}\d{2}[a-zA-Z0-9]{1,30}$'),
    constraint proprietaire_pk primary key (id_compte),
    constraint proprietaire_fk_compte foreign key (id_compte) references compte(id_compte)ON DELETE CASCADE
);

create table logement(
    id_logement serial not null,
    prix_ttc float,
    note_logement float check (note_logement >= 0 and note_logement <= 5),
    en_ligne boolean,
    ouvert BOOLEAN DEFAULT FALSE,
    type_logement varchar(255),
    nature_logement varchar(255),
    descriptif varchar(255),
    surface integer,
    disponible_defaut boolean,
    prix_base_ht numeric(10,2),
    duree_resa_min int, -- en jours
    delai_resa_min int, -- en jours, délai entre la date de réservation et l'arrivée du client
    delai_annul_defaut int, -- en jours
    pourcentage_retenu_defaut numeric(10,2),
    libelle_logement varchar(255),
    accroche VARCHAR(255),
    nb_pers_max integer,
    nb_chambre integer,
    nb_salle_de_bain integer,
    code_postal varchar(255) check (code_postal ~ '^[0-9]{5}$|^2[AB]$'),
    departement varchar(255),
    localisation varchar(255), -- = commune/ville
    adresse varchar(255), -- cacher pour les utlisateurs
    complement_adresse varchar(255),
    info_arrivee varchar(255),
    info_depart varchar(255),
    reglement_interieur varchar(255),
    id_compte integer,
    id_image_couv integer,
    constraint logement_pk primary key(id_logement),
    constraint logement_fk_proprietaire foreign key (id_compte) references proprietaire(id_compte) ON DELETE CASCADE,
    constraint logement_fk_couverture foreign key (id_image_couv) references image(id_image)
); 

create table photo_logement(
    id_image integer,
    id_logement integer,
    constraint photo_logement_pk primary key (id_image, id_logement),
    constraint photo_logement_fk_image foreign key (id_image) references image(id_image)ON DELETE CASCADE,
    constraint photo_logement_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table cb(
    type_cb VARCHAR(50),
    numero_carte varchar(16) check (numero_carte ~ '^[0-9]{16}$'),
    date_validite date,
    cryptogramme VARCHAR(3),
    id_compte integer,
    constraint cb_pk primary key(numero_carte),
    constraint cb_fk_client foreign key (id_compte) references client(id_compte)ON DELETE CASCADE
);

create table langue(
    nom_langue varchar(255),
    id_compte integer,
    constraint langue_pk primary key(nom_langue,id_compte),
    constraint langue_fk_compte foreign key (id_compte) references compte(id_compte)ON DELETE CASCADE
);

create table message(
    id_dest integer,
    id_msg integer,
    contenu varchar(255),
    date_msg timestamp,
    id_compte integer,
    constraint message_pk primary key (id_dest,id_msg,id_compte),
    constraint message_fk_compte foreign key (id_compte) references compte(id_compte) ON DELETE CASCADE,
    constraint message_fk_dest foreign key (id_dest) references compte(id_compte) ON DELETE CASCADE
);

create table message_type(
    titre_message varchar(255),
    contenu_type varchar(255),
    nb_jours_auto int,
    relativite varchar(10) check (relativite in ('arrivee', 'depart')),
    id_compte integer,
    constraint message_type_pk primary key (id_compte, titre_message),
    constraint message_type_fk_compte foreign key (id_compte) references compte(id_compte)ON DELETE CASCADE
);

create table telephone(
    numero varchar(10) check (numero ~ '^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$'), -- Inclut les indicatifs, nécessaire pour les téléphones étrangers
    info varchar(255),
    id_compte integer,
    constraint telephone_pk primary key (numero),
    constraint telephone_fk_compte foreign key (id_compte) references compte(id_compte)ON DELETE CASCADE
);

create table amenagement(
    nom_amenagement varchar(255),
    id_logement integer,
    constraint amenagement_pk primary key(nom_amenagement, id_logement),
    constraint amenagement_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table equipement(
    nom_equipement varchar(255),
    id_logement integer,
    constraint equipement_pk primary key(nom_equipement, id_logement),
    constraint equipement_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table service(
    nom_service varchar(255),
    id_logement integer,
    constraint service_pk primary key(nom_service, id_logement),
    constraint service_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table installation(
    nom_installation varchar(255),
    id_logement integer,
    constraint installation_pk primary key(nom_installation, id_logement),
    constraint installation_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);
create table reservation(
    id_reservation SERIAL not null,
    debut_reservation date,
    fin_reservation date,
    nb_personne integer,
    id_compte integer,
    id_logement integer,
    constraint reservation_pk primary key(id_reservation),
    constraint reservation_fk_client foreign key (id_compte) references client(id_compte)ON DELETE CASCADE,
    constraint reservation_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);


create table avis(
    id_avis SERIAL not null,
    id_parent integer,
    titre varchar(255),
    contenu varchar(255),
    date_avis timestamp,
    id_logement integer,
    note_avis numeric(3, 2) check (note_avis >= 0 and note_avis <= 5),
    constraint avis_pk primary key(id_avis),
    constraint avis_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table signalement(
    id_signalement integer not null,
    justification varchar(255),
    type_signalement varchar(255),
    id_compte integer, -- personne qui a signalé
    id_objet int,
    classe_objet varchar(255) check (classe_objet in ('compte', 'logement', 'avis', 'message')),
    constraint signalement_pk primary key(id_signalement)
    -- contrainte pour classe objet : ne peut être que "compte", "avis", "logement" ou "message"
);



create table charge_additionnelle(
    nom_charge varchar(255),
    constraint charge_additionnelle_pk primary key(nom_charge)
);

create table prix_charge(
    prix_charge numeric(10,2),
    id_logement integer,
    nom_charge varchar(255),
    constraint prix_charge_pk primary key (prix_charge, id_logement),
    constraint prix_charge_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE,
    constraint prix_charge_fk_charge_additionnelle foreign key (nom_charge) references  charge_additionnelle(nom_charge)ON DELETE CASCADE
);

  create table charges_selectionnees(
      id_reservation integer,
      nom_charge varchar(255),
      constraint charges_selectionnees_pk primary key (nom_charge, id_reservation),
      constraint charges_selectionnees_fk_reservation foreign key (id_reservation) references reservation(id_reservation)ON DELETE CASCADE,
      constraint charges_selectionnees_fk_charge_additionnelle foreign key (nom_charge) references  charge_additionnelle(nom_charge)ON DELETE CASCADE
  );

create table planning(
    disponibilite boolean,
    prix_ht numeric(10,2),
    jour date,
    raison_indisponible varchar(255),
    id_logement integer,
    constraint planning_pk primary key (jour, id_logement),
    constraint planning_fk_logement foreign key (id_logement) references logement(id_logement) ON DELETE CASCADE
);



create table devis(
    id_devis SERIAL not null,
    prix_devis float,
    delai_acceptation date,
    acceptation boolean,
    date_devis timestamp,
    id_reservation integer,
    constraint devis_pk primary key (id_devis),
    constraint devis_fk_reservation foreign key (id_reservation) references reservation(id_reservation)ON DELETE CASCADE
);

create table contrainte_reservation(
    duree_min_reservation int not null, -- en jours
    duree_max_reservation int not null, -- en jours
    delai_min_resa_arrive int not null, -- en jours, valeur par défaut à rajouter dans logement
    id_logement integer,
    constraint contrainte_reservation_pk primary key (id_logement),
    constraint contrainte_reservation_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table lit(
    type_lit varchar(255),
    nombre_lit integer,
    detail_lit varchar(255),
    id_logement integer,
    constraint lit_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
);

create table facture(
    id_facture SERIAL not null,
    prix_facture float,
    info_facture varchar(255),
    payement float, -- Ce qui a déjà été payé par rapport au prix de la facture
    id_devis integer,
    constraint facture_pk primary key(id_facture),
    constraint facture_fk_devis foreign key (id_devis) references devis(id_devis)ON DELETE CASCADE
);

create table api(
    cle varchar(32),
    privilegie boolean,
    accesCalendrier boolean,
    miseIndispo boolean,
    id_compte integer,
    constraint api_pk primary key(cle),
    constraint api_fk_compte foreign key (id_compte) references compte(id_compte)
);


-- TESTS


INSERT INTO image (extension_image)
VALUES
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('png'),
    ('jpg'),
    ('png'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg');

INSERT INTO compte (mdp, nom_affichage, date_creation, derniere_operation, adresse, adresse_mail, nom, prenom, photo_de_profil, piece_identite) 
VALUES
    ('$2y$10$6EJNz9joPL2uTtNky.PZVOZAZzCOd9tSHOedzkmhZxhkDuxGTT.rS', 'Utilisateur 1', '2023-10-19', now(),'789 Rue Client 3', 'client3@email.com', 'Durand', 'Jean',1,1),--mot de passe: 1234
    ('$2y$10$qHok/P3X6bmge2vlQ4SQwuCDLxOoRbhtjlJYNsIYWzYqwrOaFJXPS', 'Utilisateur 2', '2023-10-20','2023-11-03 00:42','123 Rue Client 1', 'client1@email.com', 'Dubois', 'Roger',2,2),--mot de passe: galette35
    ('$2y$10$a6BMR7TZjF5lbLJ090lmD.CPSxGtr97UWpukjp0ODOnL0DuGbjR22', 'Utilisateur 3', '2023-10-21','2023-10-25 09:37','456 Rue Client 2', 'client2@email.com', 'Petit', 'Damien',3,3),--mot de passe: JaimeLesClients
    ('$2y$10$qj/hnUrUwJp2Q2A1d8Kzouj3UzOkpqLrulVxR/B3PTC1gfURNOpaO', 'Utilisateur 4', '2023-10-22','2023-11-09 08:57','789 Rue vanier', 'proprio3@email.com', 'Moreau', 'François',4,4),--mot de passe: loquali
    ('$2y$10$aYirKQ2NApXf/bOxtCxh6OIaImA48sgggNY8ewJ2h4BLlK/H4J4qa', 'Utilisateur 5', '2023-10-23','2023-10-29 11:18','123 Rue de la lys','proprio1@email.com','Roux', 'Robert',5,5),--mot de passe: azerty35
    ('$2y$10$AcFOc8L3wBPwoqWvXgI23OVMVAiXK98WRnJtqXSK2LD.XBphuymLq', 'Utilisateur 6', '2023-10-24','2023-11-01 10:28','456 Rue du jardin','proprio2@email.com', 'Simon', 'Richard',6,6),--mot de passe: quoicoubeh
    ('$2y$10$uE1GonUNJAx7z/VRcYJYlOEOppQ3VHfRQRoThbOuWEk2ePLzXYX7a', 'Utilisateur 7', '2023-10-25', now(),'123 Rue Proprio 1', 'proprio4@email.com', 'Lefevre', 'Sophie',7,7),--mot de passe: proprio7
    ('$2y$10$W7TbqVYbSi1FTas/yU3ScubRnNu1NT1Rdo1.K2.b9V9tiijDIm7Iy', 'Utilisateur 8', '2023-10-26','2023-11-05 14:23','456 Rue Proprio 2', 'proprio5@email.com', 'Lemoine', 'Pierre',8,8),--mot de passe: proprietaire8
    ('$2y$10$G3Pz4Kp59ZB10eN2S.9OyuYd2xSXMIt/ZbrC9Zs6qA0eq3z18.uQK', 'Utilisateur 9', '2023-10-27','2023-11-10 09:12','789 Rue Proprio 3', 'proprio6@email.com', 'Leclerc', 'Marie',9,9),--mot de passe: proprietaire9
    ('$2y$10$HdjimLSQHMe6JqUK3oFlP.jCVmpmmTnsjM6X4NDLU3EDvFEEty.Fa', 'Utilisateur 10', '2023-10-28','2023-11-15 08:37','123 Rue Proprio 4', 'proprio7@email.com', 'Dufour', 'Luc',10,10),--mot de passe: proprietaire10
    ('$2y$10$VTL5qYN1Ynqk.Q1LbY.JD.xxygyaj.kphl1S0OD6c1QTDiB0.7w/e', 'Utilisateur 11', '2023-10-29','2023-11-20 11:45','456 Rue Proprio 5', 'proprio8@email.com', 'Michel', 'Claire',11,11);--mot de passe: proprietaire11



INSERT INTO client (id_compte, note_client)
VALUES
    (1, 4.5),
    (2, 3.8),
    (3, 4.2);


INSERT INTO proprietaire (id_compte, description, note_proprio, civilite, RIB)
VALUES
    (4, 'Description Propriétaire 1', 4.9,'M','FR7611315000011234567890134'),
    (5, 'Description Propriétaire 2', 4.2,'Mme','FR7611315000011234567890138'),
    (6, 'Description Propriétaire 3', 4.7,'F','FR7630002032531234567890168'),
    (7, 'Description Propriétaire 4', 4.9,'M','FR7611315000011234567890134'),
    (8, 'Description Propriétaire 5', 4.2,'Mme','FR7611315000011234567890138'),
    (9, 'Description Propriétaire 6', 4.7,'F','FR7630002032531234567890168'),
    (10, 'Description Propriétaire 7', 4.9,'M','FR7611315000011234567890134'),
    (11, 'Description Propriétaire 8', 4.2,'Mme','FR7611315000011234567890138');


INSERT INTO logement (prix_TTC, note_logement, en_ligne, type_logement, nature_logement, localisation, descriptif, surface, disponible_defaut, prix_base_HT, duree_resa_min, delai_resa_min, delai_annul_defaut, pourcentage_retenu_defaut, libelle_logement, accroche, nb_pers_max, nb_chambre, nb_salle_de_bain, code_postal,departement, info_arrivee, info_depart, reglement_interieur, id_compte, id_image_couv)
VALUES
    (150.00, 4.3, TRUE,'T4', 'Appartement', 'Brest', 'Bel appartement au coeur de Brest', 80, TRUE, 120.00, 2, 3, 5, 10.00, 'Appartement Brestois', 'Vue magnifique sur le port', 4, 2, 1, 29200 , 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 4, 1),
    (200.00, 4.5, TRUE, 'T3', 'Maison', 'Quimper', 'Charmante maison à Quimper', 120, TRUE, 180.00, 4, 5, 6, 15.00, 'Maison Quimpéroise', 'Proche de la plage', 6, 3, 2, 29000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 5, 5),
    (100.00, 4.0, TRUE, 'T1', 'Studio', 'Morlaix', 'Studio ensoleillé à Morlaix', 45, TRUE, 80.00, 2, 4, 3, 8.00, 'Studio Lumineux', 'Jardin privé et piscine', 2, 3, 1, 29600, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 6, 8),
    
    (120.00, 4.0, TRUE,'T2', 'Appartement', 'Lorient', 'Appartement lumineux à Lorient', 60, TRUE, 90.00, 3, 2, 7, 12.00, 'Appartement Lorientais', 'Idéalement situé en centre-ville', 2, 1, 1, 56100 , 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 7, 7),
    (180.00, 4.5, TRUE, 'T4', 'Maison', 'Vannes', 'Belle maison avec jardin à Vannes', 150, TRUE, 160.00, 4, 5, 6, 15.00, 'Maison Vannetaise', 'Proche de la mer', 8, 4, 3, 56000, 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 8, 8),
    (100.00, 3.8, TRUE, 'T1', 'Studio', 'Quimper', 'Studio confortable à Quimper', 40, TRUE, 80.00, 2, 1, 5, 10.00, 'Studio Quimpérois', 'Idéal pour un séjour touristique', 1, 0, 1, 29000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 9, 9),
    (150.00, 4.2, TRUE, 'T3', 'Appartement', 'Brest', 'Appartement moderne à Brest', 80, TRUE, 120.00, 3, 3, 7, 12.00, 'Appartement Brestois', 'Proximité des transports en commun', 6, 3, 2, 29200, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 10, 10),
    (130.00, 4.0, TRUE, 'T2', 'Appartement', 'Saint-Malo', 'Appartement avec vue sur la mer', 70, TRUE, 100.00, 2, 2, 6, 10.00, 'Appartement Malouin', 'Vue imprenable sur la plage', 4, 2, 1, 35400, 'Ille-et-Vilaine', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 11, 11),
    
    (120.00, 4.0, TRUE,'T2', 'Appartement', 'Saint-Brieuc', 'Appartement chaleureux au bord de Saint-Brieuc', 60, TRUE, 90.00, 3, 2, 7, 12.00, 'Appartement Briochins', 'Idéalement situé en centre-ville', 2, 1, 1, 56100 , 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 7, 7),
    (180.00, 4.5, TRUE, 'T4', 'Maison', 'Vannes', 'Maison avec vue incontournable sur Vannes', 150, TRUE, 160.00, 4, 5, 6, 15.00, 'Maison Vannetaise', 'Proche de la mer', 8, 4, 3, 56000, 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 8, 8),
    (100.00, 3.8, TRUE, 'T1', 'Studio', 'Rennes', 'Studio confort au centre de la capitale bretonne', 40, TRUE, 80.00, 2, 1, 5, 10.00, 'Studio Quimpérois', 'Idéal pour un séjour touristique', 1, 0, 1, 29000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 9, 9),
    (150.00, 4.2, TRUE, 'T3', 'Appartement', 'Brest', 'Appartement moderne à Brest', 80, TRUE, 120.00, 3, 3, 7, 12.00, 'Appartement Brestois', 'Proximité des transports en commun', 6, 3, 2, 29200, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 10, 8),
    (130.00, 4.0, TRUE, 'T2', 'Appartement', 'Saint-Malo', 'Appartement avec vue sur la mer', 70, TRUE, 100.00, 2, 2, 6, 10.00, 'Appartement Malouin', 'Vue imprenable sur la plage', 4, 2, 1, 35400, 'Ille-et-Vilaine', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 11, 11),
    
    (120.00, 4.0, TRUE,'T2', 'Appartement', 'Lorient', 'Appartement lumineux à Lorient', 60, TRUE, 90.00, 3, 2, 7, 12.00, 'Appartement Lorientais', 'Idéalement situé en centre-ville', 2, 1, 1, 56100 , 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 4, 7),
    (180.00, 4.5, TRUE, 'T4', 'Maison', 'Vannes', 'Belle maison avec jardin à Vannes', 150, TRUE, 160.00, 4, 5, 6, 15.00, 'Maison Vannetaise', 'Proche de la mer', 8, 4, 3, 56000, 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 8, 8),
    (100.00, 3.8, TRUE, 'T1', 'Studio', 'Quimper', 'Studio confortable à Quimper', 40, TRUE, 80.00, 2, 1, 5, 10.00, 'Studio Quimpérois', 'Idéal pour un séjour touristique', 1, 0, 1, 29000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 11, 9),
    (150.00, 4.2, TRUE, 'T3', 'Appartement', 'Brest', 'Appartement moderne à Brest', 80, TRUE, 120.00, 3, 3, 7, 12.00, 'Appartement Brestois', 'Proximité des transports en commun', 6, 3, 2, 29200, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 5, 10),
    (130.00, 4.0, TRUE, 'T2', 'Appartement', 'Saint-Malo', 'Appartement avec vue sur la mer', 70, TRUE, 100.00, 2, 2, 6, 10.00, 'Appartement Malouin', 'Vue imprenable sur la plage', 4, 2, 1, 35400, 'Ille-et-Vilaine', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 11, 11),
    
    (120.00, 4.0, TRUE,'T2', 'Appartement', 'Lorient', 'Appartement lumineux à Lorient', 60, TRUE, 90.00, 3, 2, 7, 12.00, 'Appartement Lorientais', 'Idéalement situé en centre-ville', 2, 1, 1, 56100 , 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 9, 7),
    (180.00, 4.5, TRUE, 'T4', 'Maison', 'Vannes', 'Belle maison avec jardin à Vannes', 150, TRUE, 160.00, 4, 5, 6, 15.00, 'Maison Vannetaise', 'Proche de la mer', 8, 4, 3, 56000, 'Morbihan', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 7, 8),
    (100.00, 3.8, TRUE, 'T1', 'Studio', 'Quimper', 'Studio confortable à Quimper', 40, TRUE, 80.00, 2, 1, 5, 10.00, 'Studio Quimpérois', 'Idéal pour un séjour touristique', 1, 0, 1, 29000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 8, 9),
    (150.00, 4.2, TRUE, 'T3', 'Appartement', 'Brest', 'Appartement moderne à Brest', 80, TRUE, 120.00, 3, 3, 7, 12.00, 'Appartement Brestois', 'Proximité des transports en commun', 6, 3, 2, 29200, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 10, 7),
    (130.00, 4.0, TRUE, 'T2', 'Appartement', 'Saint-Malo', 'Appartement avec vue sur la mer', 70, TRUE, 100.00, 2, 2, 6, 10.00, 'Appartement Malouin', 'Vue imprenable sur la plage', 4, 2, 1, 35400, 'Ille-et-Vilaine', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 11, 10);


INSERT INTO photo_logement(id_logement, id_image)
VALUES
    (1,1),
    (1,4),
    (1,2),
    (1,3),
    (2,10),
    (2,9),
    (2,11),
    (3,5),
    (3,1),
    (4,7),
    (5,13),
    (6,17),
    (6,7),
    (6,14),
    (7,6),
    (8,16),
    (8,10),
    (9,11),
    (10,12),
    (10,15),
    (11,12),
    (11,5),
    (12,6),
    (13,15),
    (14,1),
    (14,4),
    (15,2),
    (16,3),
    (17,7),
    (17,5),
    (18,6),
    (18,16),
    (19,4),
    (19,2),
    (20,3),
    (21,7),
    (22,5),
    (22,6),
    (23,6);

INSERT INTO CB (type_cb, numero_carte, date_validite, cryptogramme, id_compte)
VALUES
    ('MasterCard', '1234567890123456', '2025-12-31', 123, 1),
    ('MasterCard', '9876543210987654', '2024-10-31', 456, 2),
    ('MasterCard', '1111222233334444', '2026-06-30', 789, 3);

INSERT INTO langue (nom_langue, id_compte)
VALUES
    ('Français', 1),
    ('Anglais', 2),
    ('Espagnol', 3);
    
INSERT INTO message (id_dest, id_msg, contenu, date_msg, id_compte)
VALUES
    (1, 1, 'Bonjour, je suis intéressé par votre logement.', NOW(), 1),
    (2, 2, 'Pouvez-vous me donner plus d informations sur la maison ?', '2023-10-21 10:15', 2),
    (3, 3, 'Le studio semble parfait pour mes vacances.', '2023-10-22 15:45', 3);


INSERT INTO message_type (titre_message, contenu_type, nb_jours_auto, relativite, id_compte)
VALUES
    ('Information', 'votre réservation approche !', -3, 'arrivee', 1),
    ('Demande avis', 'votre séjour s''est bien déroulé ? n''hésitez pas à donner votre avis! ', 1, 'depart', 2);
    
INSERT INTO telephone (numero, info, id_compte)
VALUES
    ('0234567894', 'Téléphone principal', 1),
    ('0876543210', 'Téléphone secondaire', 2),
    ('0111222333', 'Numéro de contact', 3),
    ('0606060606', 'Numéro de contact', 4),
    ('0615784585', 'Numéro de contact', 5),
    ('0687815987', 'Numéro de contact', 6),
    ('0645872598', 'Téléphone principal', 7),
    ('0784523698', 'Téléphone secondaire', 8),
    ('0687421597', 'Numéro de contact', 9),
    ('0743690215', 'Numéro de contact', 10),
    ('0648579842', 'Numéro de contact', 11);
    
INSERT INTO amenagement (nom_amenagement, id_logement)
VALUES
    ('jardin', 1),
    ('parking', 1),
    ('balcon', 2),
    ('terrasse', 3),
    ('jardin', 4),
    ('parking', 4),
    ('balcon', 5),
    ('terrasse', 8),
    ('jardin', 8),
    ('parking', 8),
    ('balcon', 9),
    ('terrasse', 9),
    ('jardin', 11),
    ('parking', 12),
    ('balcon', 12),
    ('terrasse', 13),
    ('jardin', 14),
    ('parking', 15),
    ('balcon', 15),
    ('terrasse', 16),
    ('jardin', 16),
    ('parking', 16),
    ('balcon', 16),
    ('terrasse', 17),
    ('jardin', 17),
    ('parking', 18),
    ('balcon', 19),
    ('terrasse', 19),
    ('jardin', 21),
    ('parking', 21),
    ('balcon', 22),
    ('terrasse', 23),
    ('jardin', 23);
    
INSERT INTO equipement (nom_equipement, id_logement)
VALUES
    ('Wi-Fi', 1),
    ('Climatisation', 2),
    ('TV satellite', 3),
    ('Wi-Fi', 4),
    ('Climatisation', 4),
    ('TV satellite', 5),
    ('Wi-Fi', 6),
    ('Climatisation', 6),
    ('TV satellite', 8),
    ('Wi-Fi', 8),
    ('Climatisation', 9),
    ('TV satellite', 10),
    ('Wi-Fi', 11),
    ('Climatisation', 12),
    ('TV satellite', 13),
    ('Wi-Fi', 13),
    ('Climatisation', 14),
    ('TV satellite', 14),
    ('Wi-Fi', 14),
    ('Climatisation', 15),
    ('TV satellite', 15),
    ('Wi-Fi', 16),
    ('Climatisation', 17),
    ('TV satellite', 18),
    ('Wi-Fi', 18),
    ('Climatisation', 19),
    ('TV satellite', 20),
    ('Wi-Fi', 20),
    ('Climatisation', 21),
    ('TV satellite', 21),
    ('Wi-Fi', 22),
    ('Climatisation', 22),
    ('TV satellite', 22);
    
INSERT INTO service (nom_service, id_logement)
VALUES
    ('linge', 1),
    ('repas', 1),
    ('taxi', 1),
    ('ménage', 2),
    ('Petit-déjeuner inclus', 3),
    ('linge', 4),
    ('repas', 5),
    ('taxi', 5),
    ('ménage', 6),
    ('Petit-déjeuner inclus', 6),
    ('linge', 7),
    ('repas', 8),
    ('taxi', 8),
    ('ménage', 9),
    ('Petit-déjeuner inclus', 10),
    ('linge', 10),
    ('repas', 11),
    ('taxi', 12),
    ('ménage', 12),
    ('Petit-déjeuner inclus', 13),
    ('linge', 13),
    ('repas', 14),
    ('taxi', 14),
    ('ménage', 15),
    ('Petit-déjeuner inclus', 15),
    ('linge', 16),
    ('repas', 17),
    ('taxi', 17),
    ('ménage', 18),
    ('Petit-déjeuner inclus', 19),
    ('linge', 19),
    ('repas', 20),
    ('taxi', 20),
    ('ménage', 21),
    ('Petit-déjeuner inclus', 21),
    ('linge', 22),
    ('repas', 22),
    ('taxi', 23),
    ('ménage', 23),
    ('Petit-déjeuner inclus', 23);
    
INSERT INTO installation (nom_installation, id_logement)
VALUES
    ('jacuzzi', 1),
    ('piscine', 1),
    ('hammam', 2),
    ('sauna', 3),
    ('jacuzzi', 3),
    ('piscine', 4),
    ('hammam', 4),
    ('sauna', 5),
     ('jacuzzi', 6),
    ('piscine', 6),
    ('hammam', 6),
    ('sauna', 7),
     ('jacuzzi', 8),
    ('piscine', 8),
    ('hammam', 9),
    ('sauna', 9),
     ('jacuzzi', 10),
    ('piscine', 11),
    ('hammam', 12),
    ('sauna', 13),
     ('jacuzzi', 13),
    ('piscine', 14),
    ('hammam', 14),
    ('sauna', 15),
     ('jacuzzi', 16),
    ('piscine', 18),
    ('hammam', 19),
    ('sauna', 19),
     ('jacuzzi', 20),
    ('piscine', 20),
    ('hammam', 21),
    ('sauna', 22),
     ('jacuzzi', 22),
    ('piscine', 23),
    ('hammam', 23),
    ('sauna', 23);
    
INSERT INTO reservation (debut_reservation, fin_reservation, nb_personne, id_compte, id_logement)
VALUES
    ('2023-11-01', '2023-11-07', 2, 1, 1),
    ('2023-11-15', '2023-11-20', 4, 2, 2),
    ('2023-12-10', '2023-12-15', 1, 3, 3),
    ('2023-11-20', '2023-11-07', 2, 2, 20),
    ('2023-11-12', '2023-11-20', 4, 2, 15),
    ('2023-12-07', '2023-12-15', 1, 3, 13),
    ('2023-12-01', '2023-11-07', 2, 1, 7),
    ('2024-01-04', '2023-11-20', 4, 3, 21),
    ('2024-01-10', '2023-12-15', 1, 3, 19),
    ('2024-01-08', '2023-11-07', 2, 1, 16),
    ('2024-01-13', '2023-11-20', 4, 2, 12),
    ('2024-01-20', '2023-12-15', 1, 3, 13);
    
INSERT INTO avis (id_avis, id_parent, titre, contenu, date_avis, id_logement)
VALUES
    (1,1, 'Super séjour', 'Nous avons passé un excellent séjour dans cet appartement.', '2023-11-10', 1),
    (2,2, 'Magnifique maison', 'La maison était tout simplement magnifique. Nous avons adoré.', '2023-11-25', 2),
    (3,3, 'Studio agréable', 'Le studio était parfait pour nos vacances. Nous y retournerons.', '2023-12-15', 3);
    

INSERT INTO signalement (id_signalement, justification, type_signalement, id_compte, id_objet, classe_objet)
VALUES
    (1,'Commentaire inapproprié', 'Abus', 1, 1, 'avis'),
    (2,'Contenu offensant', 'Abus', 2, 2, 'compte'),
    (3,'Fausse information', 'Abus', 3, 3, 'logement');
    

INSERT INTO charge_additionnelle (nom_charge)
VALUES
    ('Frais de ménage'),
    ('Frais de piscine'),
    ('Frais de petit-déjeuner');

/*
INSERT INTO plage (disponibilite, prix_hT, delai_annul, pourcentage_retenu, date_debut, date_fin, id_logement)
VALUES
    (TRUE, 80.00, 5, 5.00, '2023-11-18', '2023-11-30', 1),
    (TRUE, 120.00, 3, 8.00, '2023-11-15', '2023-11-30', 2), -- Les plages ne doivent pas se superposer entre elles pour un même logement, les nouvelles plages remplacent certaines parties des anciennes
    (TRUE, 100.00, 5, 6.00, '2023-11-1', '2023-11-14', 2), -- Donc si la plage du dessus faisait du 1-30, sa date de début a été modifiée pour ne pas la superposer avec celle ci qui fait du 1-14
    (TRUE, 70.00, 1, 7.00, '2023-12-01', '2023-12-31', 3);
*/
    
INSERT INTO prix_charge (prix_charge, id_logement, nom_charge)
VALUES
    (20.00, 1, 'Frais de ménage'),
    (30.00, 2, 'Frais de piscine'),
    (15.00, 3, 'Frais de petit-déjeuner');
    
INSERT INTO charges_selectionnees(id_reservation,nom_charge)
VALUES
    (1,'Frais de ménage'),
    (2,'Frais de piscine'),
    (3,'Frais de petit-déjeuner');
    
INSERT INTO devis (prix_devis, delai_acceptation, acceptation, date_devis, id_reservation)
VALUES
    (250.00, '2023-10-30', TRUE,'2023-10-28 10:41', 1),
    (350.00, '2023-11-05', TRUE,'2023-11-01 13:15', 2),
    (150.00, '2023-11-20', FALSE,'2023-10-30 10:58', 3);
    
INSERT INTO contrainte_reservation (duree_min_reservation, duree_max_reservation, delai_min_resa_arrive, id_logement)
VALUES
    (3, 10, 1, 1),
    (1, 14, 3, 2),
    (1, 11, 4, 3);
    
INSERT INTO lit (type_lit, nombre_lit, detail_lit, id_logement)
VALUES
    ('Lit double', 1, 'King size', 1),
    ('Lit double', 2, 'Queen size', 2),
    ('Lit simple', 2, 'Simple', 3);
    
INSERT INTO facture (prix_facture, info_facture, payement, id_devis)
VALUES
    (250.00, 'Facture pour la réservation 1', 139.00, 1),
    (350.00, 'Facture pour la réservation 2', 200.00, 2),
    (150.00, 'Facture pour la réservation 3', 60.00, 3);


CREATE FUNCTION getDayData(id_log INT, day DATE)
  RETURNS TABLE(disponibilite BOOLEAN, prix_ht numeric(10,2), delai_annul integer, pourcentage_retenu numeric(10,2), raison_indisponible VARCHAR(255), jour DATE, id_logement INT) AS $$
BEGIN
  PERFORM * FROM planning WHERE planning.jour = day AND planning.id_logement = id_log;
  IF NOT FOUND THEN
    RETURN QUERY SELECT l.disponible_defaut, l.prix_base_ht, l.delai_annul_defaut, l.pourcentage_retenu_defaut, ''::varchar, day, id_log FROM logement l
      WHERE l.id_logement = id_log;
  ELSE
    RETURN QUERY SELECT p.disponibilite, p.prix_ht, l.delai_annul_defaut, l.pourcentage_retenu_defaut, p.raison_indisponible, day, id_log FROM planning p NATURAL JOIN logement l WHERE p.jour = day AND p.id_logement = id_log;
  END IF;
END;
$$ LANGUAGE plpgsql;

-- nbJours = nombre de jours passés dans le logement (jours non-entiers inclus, donc date de début et de fin inclus)
-- Nombre de nuits = nbJours-1
CREATE FUNCTION getPlageData(id_log INT, date_debut DATE, date_fin DATE)
  RETURNS TABLE(disponibilite BOOLEAN, prix_ht numeric(10,2), delai_annul integer, pourcentage_retenu numeric(10,2), raison_indisponible VARCHAR(255), id_logement INT, nbJours INT) AS $$
DECLARE
  disponibilite BOOLEAN = TRUE;
  prix_ht NUMERIC(10,2) = 0;
  delai_annul INTEGER;
  pourcentage_retenu NUMERIC(10,2);
  raison_indisponible VARCHAR(255);
  jour DATE = date_debut;
  ajout RECORD;
BEGIN
  IF date_debut > date_fin THEN
    RAISE EXCEPTION 'La date de début de la plage doit être inférieure à sa date de fin';
  END IF;
  SELECT l.delai_annul_defaut FROM test.logement l WHERE l.id_logement = id_log INTO delai_annul;
  SELECT l.pourcentage_retenu_defaut FROM test.logement l WHERE l.id_logement = id_log INTO pourcentage_retenu;
  WHILE jour <= date_fin LOOP
    SELECT * FROM getDayData(id_log, jour) INTO ajout;
    disponibilite = (disponibilite AND ajout.disponibilite);
    IF NOT disponibilite THEN
      raison_indisponible = ajout.raison_indisponible;
      RETURN QUERY SELECT disponibilite, prix_ht, delai_annul, pourcentage_retenu, raison_indisponible, id_log, DATE_PART('day', jour::timestamp - date_debut::timestamp)::int AS nbJours;
      RETURN;
    END IF;
    prix_ht = prix_ht + ajout.prix_ht;
    jour = jour + 1;
  END LOOP;
  RETURN QUERY SELECT disponibilite, prix_ht, delai_annul, pourcentage_retenu, raison_indisponible, id_log, DATE_PART('day', jour::timestamp - date_debut::timestamp)::int AS nbJours;
END;
$$ LANGUAGE plpgsql;

/*
CREATE FUNCTION ajoutPlage() RETURNS TRIGGER AS $$
BEGIN
  DELETE FROM plage WHERE plage.date_debut >= NEW.date_debut AND plage.date_fin <= NEW.date_fin AND plage.id_logement = NEW.id_logement;
  UPDATE plage SET date_fin = NEW.date_debut-1 WHERE plage.date_fin >= NEW.date_debut AND plage.id_logement = NEW.id_logement;
  UPDATE plage SET date_debut = NEW.date_fin+1 WHERE plage.date_debut <= NEW.date_fin AND plage.id_logement = NEW.id_logement;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER noPlageOverlap
BEFORE INSERT
ON plage FOR EACH ROW
EXECUTE PROCEDURE ajoutPlage();

CREATE TRIGGER plageUpdate
BEFORE UPDATE ON plage
FOR EACH ROW
WHEN (NEW.date_debut < OLD.date_debut OR NEW.date_fin > OLD.date_fin)
EXECUTE PROCEDURE ajoutPlage();

CREATE FUNCTION deleteOldPlage() RETURNS INTEGER AS $$
DECLARE
  lignes RECORD;
BEGIN
  UPDATE plage SET date_debut = CURRENT_DATE WHERE date_debut < CURRENT_DATE;
  WITH deleted AS (DELETE FROM plage WHERE plage.date_fin < plage.date_debut RETURNING *) SELECT count(*) AS nbDel FROM deleted INTO lignes;
  RETURN lignes(nbDel);
END;
$$ LANGUAGE plpgsql;
*/

INSERT INTO planning(disponibilite, prix_hT, jour, id_logement)
VALUES
    (TRUE, 80.00, '2023-11-18', 1),
    (TRUE, 120.00, '2023-11-1', 2), -- Les plages ne doivent pas se superposer entre elles pour un même logement, les nouvelles plages remplacent certaines parties des anciennes
    --(TRUE, 100.00, '2023-11-1', 2), -- Donc si la plage du dessus faisait du 1-30, sa date de début a été modifiée pour ne pas la superposer avec celle ci qui fait du 1-14
    (TRUE, 70.00, '2023-12-01', 3),
    (FALSE, 0, '2023-11-20', 1);

INSERT INTO api(cle, privilegie, accesCalendrier, miseIndispo, id_compte) VALUES
    ('0123456789ABCDEF', TRUE, TRUE, TRUE, 1),
    ('AAABBBCCCDDDEEE', FALSE, FALSE, FALSE, 11),
    ('MANGETESGRANDSMORTS', FALSE, TRUE, TRUE, 10),
    ('azeazeazeazeazeaze', FALSE, TRUE, FALSE, 8);

