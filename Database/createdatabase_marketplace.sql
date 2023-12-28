CREATE DATABASE IF NOT EXISTS rosemarketplace;

USE rosemarketplace;

CREATE TABLE gestionnaire (
	id_gestionnaire INT(11) AUTO_INCREMENT, 
	email_gestionnaire varchar(250), 
	password_gestionnaire varchar(50), 
		PRIMARY KEY (id_gestionnaire));

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
    fournisseur binary(2),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_gestionnaire INT(11),
		PRIMARY KEY (id_client),
		FOREIGN KEY (id_gestionnaire) REFERENCES gestionnaire (id_gestionnaire));

CREATE TABLE adresse (
    id_adresse INT(11) AUTO_INCREMENT,
    numetrue_adresse varchar(100),
    codepostal_adresse numeric(5),
    villeadresse_adresse varchar(50),
	type_adresse ENUM('facturation','livraison'),
	id_client INT(11),
    	PRIMARY KEY (id_adresse),
		FOREIGN KEY (id_client) REFERENCES client(id_client));

CREATE TABLE paiement (
	id_paiement INT(11) AUTO_INCREMENT, 
	iban varchar(34), bic varchar(11), 
	numcb numeric(16), datedexpcb varchar(7), cryptogrammecb numeric(3), banquecb varchar(50),
	typepaiement ENUM('iban','cb'), 
	titulaire varchar(150), 
	id_client INT(11), 
		PRIMARY KEY (id_paiement),
		FOREIGN KEY (id_client) REFERENCES client (id_client));

CREATE TABLE produit (
	id_produit INT(11) AUTO_INCREMENT, 
	id_fournisseur INT(11), 
	nom_produit varchar(250), 
	prixht_produit decimal(7), 
	quantitestock_produit numeric(6), 
	description_produit text(1000), 
	categorie_produit varchar(50), 
	marque_produit varchar(50),
		PRIMARY KEY (id_produit),
		FOREIGN KEY (id_fournisseur) REFERENCES client (id_client));

CREATE TABLE facture (
	id_facture INT(11) AUTO_INCREMENT,
	idemetteur_facture INT(11),
	iddestinataire_facture INT(11),
	montantht_facture decimal(9),
	id_commande INT(11) 
		PRIMARY KEY (id_facture));

CREATE TABLE commande (
	id_commande INT(11) AUTO_INCREMENT, 
	date_commande date,
	idclient_commande INT(11),
	etat_commande varchar(20),
	id_client INT(11),
	id_facture INT(11) 
		PRIMARY KEY (id_commande),
		FOREIGN KEY (id_client) REFERENCES client (id_client),
 		FOREIGN KEY (id_facture) REFERENCES facture (id_facture));

CREATE TABLE message (
	id_message INT(11) AUTO_INCREMENT,
	date_message datetime,
	contenu_message text(1000),
	sens binary(1),
	idclient_message INT(11),
	idgestionnaire_message INT(11) 
		PRIMARY KEY (id_message),
		FOREIGN KEY (idclient_message) REFERENCES client(id_client),
		FOREIGN KEY (idgestionnaire_message) REFERENCES gestionnaire(id_gestionnaire));

CREATE TABLE photo (
	id_photo_produit INT(11) AUTO_INCREMENT,
	file_photo_produit varchar(250),
	id_produit INT(11) image LONGBLOB,
	image_type varchar(250)
		PRIMARY KEY (id_photo_produit),
		FOREIGN KEY (id_produit) REFERENCES produit (id_produit));

CREATE TABLE panier (
	id_produit INT(11) AUTO_INCREMENT,
	quantité_produit INT(11),
	adresse_ip VARCHAR(255) NOT NULL
		PRIMARY KEY(id_produit));

CREATE TABLE constitue (
	id_commande INT(11),
	id_produit INT(11) NOT NULL,
	nombrearticle_commande numeric(3) 
		PRIMARY KEY (id_commande, id_produit),
		FOREIGN KEY (id_commande) REFERENCES commande (id_commande),
 		FOREIGN KEY (id_produit) REFERENCES produit (id_produit));

CREATE TABLE fournit (
	id_produit INT(11) id_client INT(11) NOT NULL 
		PRIMARY KEY (id_produit,id_client),
		FOREIGN KEY (id_produit) REFERENCES produit (id_produit),
 		FOREIGN KEY (id_client) REFERENCES client (id_client));

CREATE TABLE envoie___recoit (
	id_message INT(11),
	id_gestionnaire INT(11) NOT NULL,
	id_client INT(11) NOT NULL 
		PRIMARY KEY (id_message,id_gestionnaire,id_client),
		FOREIGN KEY (id_message) REFERENCES message (id_message),
		FOREIGN KEY (id_gestionnaire) REFERENCES gestionnaire (id_gestionnaire),
		FOREIGN KEY (id_client) REFERENCES client (id_client));
