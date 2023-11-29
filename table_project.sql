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
    delai_annul_defaut int, -- en jours
    pourcentage_retenu_defaut numeric(10,2),
    libelle_logement varchar(255),
    accroche VARCHAR(255),
    nb_pers_max integer,
    nb_chambre integer,
    nb_salle_de_bain integer,
    code_postal varchar(255) check (code_postal ~ '^[0-9]{5}$|^2[AB]$'),
    departement varchar(255) check (departement in ('Finistère', 'Morbihan', 'Côte-d''Armor', 'Ille-et-Vilaine')), -- JSP si c'est très utile, on a déjà le code postal faut juste déduire
    localisation varchar(255), -- = commune/ville
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
    id_reservation integer not null,
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

create table plage(
    disponibilite boolean,
    prix_ht numeric(10,2),
    delai_annul int,
    pourcentage_retenu numeric(10,2),
    date_debut date,
    date_fin date,
    id_logement integer,
    constraint plage_fk_logement foreign key (id_logement) references logement(id_logement)ON DELETE CASCADE
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
    ('jpg'),
    ('jpg'),
    ('jpg'),
    ('jpg');

INSERT INTO compte (mdp, nom_affichage, date_creation, derniere_operation, adresse, adresse_mail, nom, prenom, photo_de_profil, piece_identite) 
VALUES
    ('motdepasse1', 'Utilisateur 1', '2023-10-19', now(),'789 Rue Client 3', 'client3@email.com', 'Durand', 'Jean',1,1),
    ('motdepasse2', 'Utilisateur 2', '2023-10-20','2023-11-03 00:42','123 Rue Client 1', 'client1@email.com', 'Dubois', 'Roger',2,2),
    ('motdepasse3', 'Utilisateur 3', '2023-10-21','2023-10-25 09:37','456 Rue Client 2', 'client2@email.com', 'Petit', 'Damien',3,3),
    ('motdepasse4', 'Utilisateur 4', '2023-10-22','2023-11-09 08:57','789 Rue vanier', 'proprio3@email.com', 'Moreau', 'François',4,4),
    ('motdepasse5', 'Utilisateur 5', '2023-10-23','2023-10-29 11:18','123 Rue de la lys','proprio1@email.com','Roux', 'Robert',5,5),
    ('motdepasse6', 'Utilisateur 6', '2023-10-24','2023-11-01 10:28','456 Rue du jardin','proprio2@email.com', 'Simon', 'Richard',6,6);


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


INSERT INTO logement (prix_TTC, note_logement, en_ligne, type_logement, nature_logement, localisation, descriptif, surface, disponible_defaut, prix_base_HT, delai_annul_defaut, pourcentage_retenu_defaut, libelle_logement, accroche, nb_pers_max, nb_chambre, nb_salle_de_bain, code_postal,departement, info_arrivee, info_depart, reglement_interieur, id_compte, id_image_couv)
VALUES
    (150.00, 4.3, TRUE,'T1', 'Appartement', 'Paris', 'Bel appartement au coeur de Paris', 80, TRUE, 120.00, 5, 10.00, 'Appartement Parisien', 'Vue magnifique sur la Tour Eiffel', 4, 2, 1, '2A', 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 4, 1),
    (200.00, 4.5, TRUE, 'T3', 'Maison', 'Nice', 'Charmante maison à Nice', 120, TRUE, 180.00, 6, 15.00, 'Maison Niçoise', 'Jardin privé et piscine', 6, 3, 2, 22000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 5, 5),
    (100.00, 4.0, TRUE, 'T5', 'Studio', 'Marseille', 'Studio ensoleillé à Marseille', 45, TRUE, 80.00, 3, 8.00, 'Studio Lumineux', 'Proche de la plage', 2, 3, 1, 22000, 'Finistère', 'boite à clé près de la porte d''entrée', 'veuillez ranger les clés dans la boite à clés', 'veuillez ne pas abimer le mobilier', 6, 8);
    

INSERT INTO photo_logement(id_logement, id_image)
VALUES
    (1,1),
    (1,4),
    (1,2),
    (1,3),
    (2,7),
    (2,5),
    (2,6),
    (3,8);

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
    ('Demande avis', 'votre séjour s''est bien déroulé ? n''hésitez pas à donner votre avis! ', 1, 'depart', 2);
    
INSERT INTO telephone (numero, info, id_compte)
VALUES
    ('0234567894', 'Téléphone principal', 1),
    ('0876543210', 'Téléphone secondaire', 2),
    ('0111222333', 'Numéro de contact', 3),
    ('0606060606', 'Numéro de contact', 4);
    
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


CREATE FUNCTION getCurrentData(id_log INT)
  RETURNS TABLE(disponibilite BOOLEAN, prix_ht numeric(10,2), delai_annul integer, pourcentage_retenu numeric(10,2), date_debut date, date_fin date, id_logement integer) AS $$
BEGIN
  PERFORM * FROM plage WHERE plage.date_debut <= CURRENT_DATE AND plage.date_fin >= CURRENT_DATE AND plage.id_logement = id_log;
  IF NOT FOUND THEN
    RETURN QUERY SELECT disponible_defaut, prix_base_ht, delai_annul_defaut, pourcentage_retenu_defaut, DATE('1970-01-01'), DATE('1970-01-01'), logement.id_logement FROM logement
      WHERE logement.id_logement = id_log;
  ELSE
    RETURN QUERY SELECT * FROM plage WHERE plage.date_debut <= CURRENT_DATE AND plage.date_fin >= CURRENT_DATE AND plage.id_logement = id_log;
  END IF;
END;
$$ LANGUAGE plpgsql;


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
  WITH deleted AS (DELETE FROM plage WHERE plage.date_fin < CURRENT_DATE RETURNING *) SELECT count(*) AS deleted FROM deleted INTO lignes;
  RETURN lignes(deleted);
END;
$$ LANGUAGE plpgsql;

INSERT INTO plage(disponibilite, prix_hT, delai_annul, pourcentage_retenu, date_debut, date_fin, id_logement)
VALUES
    (TRUE, 80.00, 5, 5.00, '2023-11-18', '2023-11-30', 1),
    (TRUE, 120.00, 3, 8.00, '2023-11-1', '2023-11-30', 2), -- Les plages ne doivent pas se superposer entre elles pour un même logement, les nouvelles plages remplacent certaines parties des anciennes
    (TRUE, 100.00, 5, 6.00, '2023-11-1', '2023-11-14', 2), -- Donc si la plage du dessus faisait du 1-30, sa date de début a été modifiée pour ne pas la superposer avec celle ci qui fait du 1-14
    (TRUE, 70.00, 1, 7.00, '2023-12-01', '2023-12-31', 3);
