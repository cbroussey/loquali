drop schema if exists test cascade;
create schema test;
set schema 'test';


create table image(
    id_image INTEGER NOT NULL,
    constraint image_pk primary key (id_image)
);

CREATE TABLE compte(
    id_compte SERIAL NOT NULL,
    identifiant VARCHAR(255) UNIQUE,
    mdp VARCHAR(255),
    nom_affichage VARCHAR(255),
    date_creation DATE,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse_mail VARCHAR(255) CHECK (adresse_mail ~ '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.(com|fr)$'),
    adresse_postale VARCHAR(255),
    derniere_operation TIMESTAMP,
    photo_de_profil INTEGER,
    piece_identite INTEGER,
    constraint compte_pk PRIMARY KEY(id_compte),
    CONSTRAINT compte_fk_pfp FOREIGN KEY(photo_de_profil) REFERENCES image(id_image),
    CONSTRAINT compte_fk_identite FOREIGN KEY(piece_identite) REFERENCES image(id_image)
);  

CREATE TABLE client(
    id_compte INTEGER NOT NULL,
    note_client NUMERIC(3,2) CHECK (note_client >= 0 AND note_client <= 5),
    -- civilite VARCHAR(255), -- Non-précisé pour le client, on peut empêcher la discrimination
    CONSTRAINT client_pk PRIMARY KEY (id_compte),
    CONSTRAINT client_fk_compte FOREIGN KEY (id_compte) REFERENCES compte(id_compte)
);

CREATE TABLE proprietaire(
    id_compte INTEGER NOT NULL,
    description VARCHAR(255),
    note_proprio NUMERIC(3,2) CHECK (note_proprio >= 0 AND note_proprio <= 5),
    civilite VARCHAR(255) CHECK (civilite IN ('M', 'F', 'Mme')), -- Nécessaire ici pcq précisé
    RIB VARCHAR(34) CHECK (RIB ~ '^FR[0-9]{25}$'),
    constraint proprietaire_pk primary key (id_compte),
    constraint proprietaire_fk_compte Foreign Key (id_compte) REFERENCES compte(id_compte)
);

create TABLE logement(
    id_logement SERIAL NOT NULL,
    prix_TTC FLOAT,
    note_logement FLOAT CHECK (note_logement >= 0 AND note_logement <= 5),
    en_ligne BOOLEAN,
    type_logement VARCHAR(255),
    nature_logement VARCHAR(255),
    localisation VARCHAR(255),
    descriptif VARCHAR(255),
    surface INTEGER,
    disponible_defaut BOOLEAN,
    prix_base_HT FLOAT,
    delai_annul_defaut int, -- en jours
    pourcentage_retenu_defaut NUMERIC(3),
    libelle_logement VARCHAR(255),
    nb_pers_max INTEGER,
    nb_chambre integer,
    nb_salle_de_bain integer,
    code_postal VARCHAR(255) CHECK (code_postal ~ '^[0-9]{5}$|^2[AB]$'),
    departement VARCHAR(255)  CHECK (departement IN ('Finistère', 'Morbihan', 'Côte-d"Armor', 'Ile-et-Vilaine')),
    info_arrivee VARCHAR(255),
    info_depart VARCHAR(255),
    reglement_interieur VARCHAR(255),
    id_compte INTEGER,
    constraint logement_pk PRIMARY KEY(id_logement),
    constraint logement_fk_proprietaire foreign key (id_compte) references proprietaire(id_compte)  
); 

create table photo_logement(
    id_image integer,
    id_logement integer,
    constraint photo_logement_fk_image foreign key (id_image) references image(id_image),
    constraint photo_logement_fk_logement foreign key (id_logement) references logement(id_logement)
);

CREATE TABLE CB(
    numero_carte Varchar(16) CHECK (numero_carte ~ '^[0-9]{16}$'),
    date_validite DATE,
    cryptogramme INTEGER,
    id_compte INTEGER,
    constraint CB_pk PRIMARY Key(numero_carte),
    constraint CB_fk_client foreign key (id_compte) REFERENCES client(id_compte)
);

create table langue(
    nom_langue VARCHAR(255),
    id_compte INTEGER,
    constraint langue_pk primary key(nom_langue,id_compte),
    constraint langue_fk_compte foreign key (id_compte) references compte(id_compte)
);

create table message(
    id_dest INTEGER,
    id_msg INTEGER,
    contenu VARCHAR(255),
    date_msg TIMESTAMP,
    id_compte INTEGER,
    constraint message_pk primary key (id_dest,id_msg,id_compte),
    constraint message_fk_compte foreign key (id_compte) references compte(id_compte)
);

create table message_type(
    titre_message VARCHAR(255),
    contenu_type VARCHAR(255),
    nb_jours_auto INT,
    relativite VARCHAR(10) CHECK (relativite IN ('arrivee', 'depart')),
    id_compte INTEGER,
    constraint message_type_fk_compte foreign key (id_compte) references compte(id_compte)  
);

create table telephone(
    numero VARCHAR(10) CHECK (numero ~ '^0[0-9]{9}$'),
    info VARCHAR(255),
    id_compte INTEGER,
    constraint telephone_pk primary key (numero),
    constraint telephone_fk_compte foreign key (id_compte) references compte(id_compte)
);

create table amenagement(
    nom_amenagement VARCHAR(255),
    id_logement INTEGER,
    CONSTRAINT amenagement_pk PRIMARY KEY(nom_amenagement, id_logement),
    constraint amenagement_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table equipement(
    nom_equipement VARCHAR(255),
    id_logement INTEGER,
    CONSTRAINT equipement_pk PRIMARY KEY(nom_equipement, id_logement),
    constraint equipement_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table service(
    nom_service VARCHAR(255),
    id_logement INTEGER,
    CONSTRAINT service_pk PRIMARY KEY(nom_service, id_logement),
    constraint service_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table installation(
    nom_installation VARCHAR(255),
    id_logement INTEGER,
    CONSTRAINT installation_pk PRIMARY KEY(nom_installation, id_logement),
    constraint installation_fk_logement foreign key (id_logement) references logement(id_logement)
);
create table reservation(
    id_reservation INTEGER NOT NULL,
    debut_reservation date,
    fin_reservation date,
    nb_personne INTEGER,
    id_compte INTEGER,
    id_logement INTEGER,
    constraint reservation_pk primary key(id_reservation),
    constraint reservation_fk_client foreign Key (id_compte) references client(id_compte),
    constraint reservation_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table avis(
    id_avis INTEGER NOT NULL,
    id_parent INTEGER,
    titre VARCHAR(255),
    contenu VARCHAR(255),
    date_avis TIMESTAMP,
    id_logement INTEGER,
    note_avis NUMERIC(3, 2) CHECK (note_avis >= 0 AND note_avis <= 5),
    constraint avis_pk primary key(id_avis),
    constraint avis_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table signalement(
    id_signalement INTEGER NOT NULL,
    justification VARCHAR(255),
    type_signalement VARCHAR(255),
    id_compte INTEGER, -- personne qui a signalé
    id_objet int,
    classe_objet varchar(255) CHECK (classe_objet IN ('compte', 'logement', 'avis', 'message')),
    constraint signalement_pk primary key(id_signalement)
    -- Contrainte pour classe objet : ne peut être que "compte", "avis", "logement" ou "message"
);



create table charge_additionnelle(
    nom_charge VARCHAR(255),
    constraint charge_additionnelle_pk primary key(nom_charge)
);

create table prix_charge(
    prix_charge NUMERIC(10,2),
    id_logement INTEGER,
    nom_charge VARCHAR(255),
    constraint prix_charge_fk_logement foreign key (id_logement) references logement(id_logement),
    constraint prix_charge_fk_charge_additionnelle foreign key (nom_charge) references  charge_additionnelle(nom_charge)
);

create table charges_selectionnees(
    id_reservation INTEGER,
    nom_charge VARCHAR(255),
    constraint charges_selectionnees_fk_reservation foreign key (id_reservation) references reservation(id_reservation),
    constraint charges_selectionnees_fk_charge_additionnelle foreign key (nom_charge) references  charge_additionnelle(nom_charge)
);

create table plage(
    disponibilite BOOLEAN,
    prix_HT NUMERIC(10,2),
    delai_annul INT,
    pourcentage_retenu NUMERIC(10,2),
    date_debut date,
    date_fin date,
    id_logement INTEGER,
    constraint plage_fk_logement foreign key (id_logement) references logement(id_logement)
);



create table devis(
    id_devis INTEGER NOT NULL,
    prix_devis FLOAT,
    delai_acceptation date,
    acceptation BOOLEAN,
    date_devis TIMESTAMP,
    id_reservation INTEGER,
    constraint devis_pk primary key (id_devis),
    constraint devis_fk_reservation foreign key (id_reservation) references reservation(id_reservation)
);

create table contrainte_reservation(
    duree_min_reservation INT NOT NULL, -- en jours
    duree_max_reservation INT NOT NULL, -- en jours
    delai_min_resa_arrive INT NOT NULL, -- en jours, valeur par défaut à rajouter dans logement
    id_logement INTEGER,
    constraint contrainte_reservation_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table lit(
    type_lit VARCHAR(255),
    nombre_lit INTEGER,
    detail_lit VARCHAR(255),
    id_logement INTEGER,
    constraint lit_fk_logement foreign key (id_logement) references logement(id_logement)
);

create table facture(
    id_facture INTEGER NOT NULL,
    prix_facture FLOAT,
    info_facture VARCHAR(255),
    paye FLOAT,
    id_devis INTEGER,
    constraint facture_pk primary key(id_facture),
    constraint facture_fk_devis foreign key (id_devis) references devis(id_devis)
);


-- TESTS


INSERT INTO image (id_image)
VALUES
    (1),
    (2),
    (3),
    (4),
    (5),
    (6);

INSERT INTO compte (id_compte ,mdp, nom_affichage, date_creation, derniere_operation, adresse_postale, adresse_mail, nom, prenom, photo_de_profil, piece_identite) 
VALUES
    (1,'motdepasse1', 'Utilisateur 1', '2023-10-19', now(),'789 Rue Client 3', 'client3@email.com', 'Durand', 'Jean',1,1),
    (2,'motdepasse2', 'Utilisateur 2', '2023-10-20','2023-11-03 00:42','123 Rue Client 1', 'client1@email.com', 'Dubois', 'Roger',2,2),
    (3,'motdepasse3', 'Utilisateur 3', '2023-10-21','2023-10-25 09:37','456 Rue Client 2', 'client2@email.com', 'Petit', 'Damien',3,3),
    (4,'motdepasse4', 'Utilisateur 4', '2023-10-22','2023-11-09 08:57','789 Rue vanier', 'proprio3@email.com', 'Moreau', 'François',4,4),
    (5,'motdepasse5', 'Utilisateur 5', '2023-10-23','2023-10-29 11:18','123 Rue de la lys','proprio1@email.com','Roux', 'Robert',5,5),
    (6,'motdepasse6', 'Utilisateur 6', '2023-10-24','2023-11-01 10:28','456 Rue du jardin','proprio2@email.com', 'Simon', 'Richard',6,6);


INSERT INTO client (id_compte, note_client)
VALUES
    (1, 4.5),
    (2, 3.8),
    (3, 4.2);


INSERT INTO proprietaire (id_compte, description, note_proprio, civilite, RIB)
VALUES
    (4, 'Description Propriétaire 1', 4.9,'M','FR7611315000011234567890134'),
    (5, 'Description Propriétaire 2', 4.2,'Mme','FR7611315000011234567890138'),
    (6, 'Description Propriétaire 3', 4.7,'M','FR7630002032531234567890168');


INSERT INTO logement (prix_TTC, note_logement, en_ligne, type_logement, nature_logement, localisation, descriptif, surface, disponible_defaut, prix_base_HT, delai_annul_defaut, pourcentage_retenu_defaut, libelle_logement, nb_pers_max, nb_chambre, nb_salle_de_bain, code_postal,departement, info_arrivee, info_depart, reglement_interieur, id_compte)
VALUES
    (150.00, 4.3, TRUE,'T1', 'Appartement', 'Paris', 'Bel appartement au cœur de Paris', 80, TRUE, 120.00, 5, 10.00, 'Appartement Parisien', 4, 2, 1, '2A', 'Finistère', 'boite à clé près de la porte d"entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 4),
    (200.00, 4.5, TRUE, 'T3', 'Maison', 'Nice', 'Charmante maison à Nice', 120, TRUE, 180.00, 6, 15.00, 'Maison Niçoise', 6, 3, 2, 22000, 'Finistère', 'boite à clé près de la porte d"entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 5),
    (100.00, 4.0, TRUE, 'T5', 'Studio', 'Marseille', 'Studio ensoleillé à Marseille', 45, TRUE, 80.00, 3, 8.00, 'Proche de la plage', 2, 3, 1, 22000, 'Finistère', 'boite à clé près de la porte d"entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 6);
    

INSERT INTO photo_logement(id_logement, id_image)
VALUES
    (1,1),
    (1,4),
    (1,2),
    (1,3),
    (2,3),
    (2,1),
    (2,2),
    (3,4);

INSERT INTO CB (numero_carte, date_validite, cryptogramme, id_compte)
VALUES
    ('1234567890123456', '2025-12-31', 123, 1),
    ('9876543210987654', '2024-10-31', 456, 2),
    ('1111222233334444', '2026-06-30', 789, 3);

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
    ('Demande avis', 'votre séjour s"est bien déroulé ? n"hésitez pas à donner votre avis! ', 1, 'depart', 2);
    
INSERT INTO telephone (numero, info, id_compte)
VALUES
    ('0234567894', 'Téléphone principal', 1),
    ('0876543210', 'Téléphone secondaire', 2),
    ('0111222333', 'Numéro de contact', 3);
    
INSERT INTO amenagement (nom_amenagement, id_logement)
VALUES
    ('jardin', 1),
    ('parking', 1),
    ('balcon', 2),
    ('terrasse', 3);
    
INSERT INTO equipement (nom_equipement, id_logement)
VALUES
    ('Wi-Fi', 1),
    ('Climatisation', 2),
    ('TV satellite', 3);
    
INSERT INTO service (nom_service, id_logement)
VALUES
    ('linge', 1),
    ('repas', 1),
    ('taxi', 1),
    ('ménage', 2),
    ('Petit-déjeuner inclus', 3);
    
INSERT INTO installation (nom_installation, id_logement)
VALUES
    ('jacuzzi', 1),
    ('piscine', 1),
    ('hammam', 2),
    ('sauna', 3);
    
INSERT INTO reservation (id_reservation, debut_reservation, fin_reservation, nb_personne, id_compte, id_logement)
VALUES
    (1,'2023-11-01', '2023-11-07', 2, 1, 1),
    (2,'2023-11-15', '2023-11-20', 4, 2, 2),
    (3,'2023-12-10', '2023-12-15', 1, 3, 3);
    
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

INSERT INTO plage (disponibilite, prix_hT, delai_annul, pourcentage_retenu, date_debut, date_fin, id_logement)
VALUES
    (TRUE, 80.00, 5, 5.00, '2023-11-01', '2023-11-30', 1),
    (TRUE, 120.00, 3, 8.00, '2023-11-15', '2023-11-30', 2),
    (TRUE, 70.00, 1, 7.00, '2023-12-01', '2023-12-31', 3);
    
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
    
INSERT INTO devis (id_devis, prix_devis, delai_acceptation, acceptation, date_devis, id_reservation)
VALUES
    (1, 250.00, '2023-10-30', TRUE,'2023-10-28 10:41', 1),
    (2, 350.00, '2023-11-05', TRUE,'2023-11-01 13:15', 2),
    (3, 150.00, '2023-11-20', FALSE,'2023-10-30 10:58', 3);
    
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
    
INSERT INTO facture (id_facture, prix_facture, info_facture, paye, id_devis)
VALUES
    (1, 250.00, 'Facture pour la réservation 1', 139.00, 1),
    (2, 350.00, 'Facture pour la réservation 2', 200.00, 2),
    (3, 150.00, 'Facture pour la réservation 3', 60.00, 3);
    
