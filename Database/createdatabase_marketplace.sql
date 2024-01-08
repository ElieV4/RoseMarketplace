CREATE DATABASE IF NOT EXISTS rosemarketplace1;

USE rosemarketplace1;

CREATE TABLE gestionnaire (
	id_gestionnaire VARCHAR(11), 
	email_gestionnaire varchar(250), 
	password_gestionnaire varchar(255), 
		PRIMARY KEY (id_gestionnaire)
);

CREATE TABLE client (
    id_client INT(11) AUTO_INCREMENT,
    email_client varchar(250),
    type_client binary(1),
    raisonsociale_client varchar(36),
    siren_client varchar(9),
    nom_client varchar(100),
    prenom_client varchar(50),
    password_client varchar(255),
    numtel_client numeric(10) ZEROFILL,
    datedenaissance_client date,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_gestionnaire VARCHAR(11),
	statut_pro ENUM('en attente', 'validé', 'refusé') NOT NULL DEFAULT 'en attente',
		PRIMARY KEY (id_client),
		FOREIGN KEY (id_gestionnaire) REFERENCES gestionnaire (id_gestionnaire)
);

CREATE TABLE adresse (
    id_adresse INT(11) AUTO_INCREMENT,
    numetrue_adresse varchar(100),
    codepostal_adresse numeric(5),
    villeadresse_adresse varchar(50),
	type_adresse ENUM('facturation','livraison') NOT NULL DEFAULT 'facturation',
	id_client INT(11),
    	PRIMARY KEY (id_adresse),
		FOREIGN KEY (id_client) REFERENCES client(id_client)
);

CREATE TABLE paiement (
	id_paiement INT(11) AUTO_INCREMENT, 
	iban varchar(34), bic varchar(11), 
	numcb numeric(16), expirationcb varchar(7), cryptogrammecb numeric(3), banquecb varchar(50),
	type_paiement ENUM('iban','cb'), 
	titulaire varchar(150), 
	id_client INT(11), 
		PRIMARY KEY (id_paiement),
		FOREIGN KEY (id_client) REFERENCES client (id_client)
);

CREATE TABLE produit (
	id_produit INT(11) AUTO_INCREMENT, 
	id_fournisseur INT(11), 
	nom_produit varchar(250), 
	prixht_produit decimal(7), 
	quantitestock_produit numeric(6), 
	description_produit text(1000), 
	categorie_produit varchar(50),
	date_ajout_produit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	marque_produit varchar(50),
	statut_produit ENUM('supprimé', 'disponible','désactivé') NOT NULL DEFAULT 'disponible',
		PRIMARY KEY (id_produit),
		FOREIGN KEY (id_fournisseur) REFERENCES client (id_client)
);

CREATE TABLE commande (
	id_commande_produit VARCHAR(50),
    id_commande INT(11),
    date_commande TIMESTAMP,
    date_preparation TIMESTAMP,
    date_envoi TIMESTAMP,
    date_livraison TIMESTAMP,
    date_livree TIMESTAMP,
    etat_commande ENUM('à valider', 'en préparation', "en cours d'envoi", 'en cours de livraison', 'livrée', 'refusée','validée'),
    id_produit INT(11),
    quantité_produit INT(11),
    montant_total DECIMAL(9),
	idclient_commande INT(11),
    id_fournisseur INT(11),
	id_adresse INT(11),
	id_paiement INT(11),
    PRIMARY KEY (id_commande_produit),
    CONSTRAINT uc_id_commande_produit UNIQUE (id_commande, id_produit),
    FOREIGN KEY (id_fournisseur) REFERENCES client (id_client),
	FOREIGN KEY (idclient_commande) REFERENCES client (id_client),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit),
    FOREIGN KEY (id_adresse) REFERENCES adresse (id_adresse),
    FOREIGN KEY (id_paiement) REFERENCES paiement (id_paiement)
);

CREATE TABLE message (
	id_message INT(11) AUTO_INCREMENT,
	date_message TIMESTAMP,
	contenu_message text(1000),
	sens binary(1),
	type_message ENUM('notification', 'message'),
	idclient_message INT(11),
	idgestionnaire_message VARCHAR(11), 
		PRIMARY KEY (id_message),
		FOREIGN KEY (idclient_message) REFERENCES client(id_client),
		FOREIGN KEY (idgestionnaire_message) REFERENCES gestionnaire(id_gestionnaire)
);

CREATE TABLE photo (
	id_photo_produit INT(11) AUTO_INCREMENT,
	file_photo_produit varchar(250),
	id_produit INT(11),
	image LONGBLOB,
	image_type varchar(250),
		PRIMARY KEY (id_photo_produit),
		FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
);

CREATE TABLE panier (
	id_produit INT(11) AUTO_INCREMENT,
	quantité_produit INT(11),
	adresse_ip VARCHAR(255) NOT NULL,
		PRIMARY KEY(id_produit)
);
