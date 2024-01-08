CREATE DATABASE IF NOT EXISTS rosemarketplace;

USE rosemarketplace;

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
	prixht_produit decimal(9,2), 
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
    montant_total DECIMAL(9,2),
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


--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id_gestionnaire`, `email_gestionnaire`, `password_gestionnaire`) VALUES
('G1', 'gestionnaire@rose.com', '$2y$10$TZGpwqpIMWSEBrOjQpxws.igOiu/GIvZNlnrOG.NFx7FD9fgZpzGO');
--
--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `email_client`, `type_client`, `raisonsociale_client`, `siren_client`, `nom_client`, `prenom_client`, `password_client`, `numtel_client`, `datedenaissance_client`, `date_creation`, `id_gestionnaire`, `statut_pro`) VALUES
(1, 'roseshop@rose.com', 0x31, 'ROSE', '809809809', 'Elie', 'Vincent', '$2y$10$j0Kb1rJd4ITjkg3BJbTbXe8/XaJRptUXbfxcQlrz.6e3eZJnjpRIa', 7825146464, '2000-07-29', '2023-12-16 02:53:21', 'G1', 'validé'),
(2, 'jeandupond@rose.com', 0x30, '', '', 'Dupond', 'Jean', '$2y$10$AoMXTl9B9R39ABbKSLMa5eBcsTJFPUKitZWYNRiYq0reTRnD52UQG', 0732414343, '2000-08-29', '2023-12-16 02:54:09', 'G1', 'validé'),
(3, 'benjamin@boom.com', 0x31, 'BOOM', '812345678', 'Vincent', 'Benjamin', '$2y$10$mZUMJpFmaLvXcL3Adqq.DeaRhL4PR3/sJPk51UAL9Yn7lIGSWX1Kq', 0607080910, '2004-10-26', '2023-12-24 23:22:24', 'G1', 'validé'),
(4, 'bricodepot@brico.com', 0x31, 'BricoDepot', '803453452', 'Patrice', 'Alexandre', '$2y$10$jOiAFYQLryq9dCR1k.K6NukwbkP5uu6p/LnnIr6S.T7LmEs1.u92G', 0123456732, '1980-10-31', '2023-12-25 18:16:21', 'G1', 'refusé'),
(5, 'vendeur@castorama.fr', 0x31, 'Castorama', '739489988', 'Lamotte', 'Jules', '$2y$10$u979W2rbyVwQTVuUysLsUeBF0nR0mZkTSzu5ygskhO92NZFzCU7Cq', 0876546765, '1976-04-21', '2023-12-25 18:22:31', 'G1', 'validé'),
(6, 'elsalar@google.de', 0x30, '', '', 'Lariviere', 'Elsa', '$2y$10$DruZQIQYel9bpq763E1.weUbaKw//CMpfKdgsUQB4Atby3mQWmTWm', 0734728473, '1976-09-21', '2023-12-25 18:53:18', 'G1', 'en attente'),
(7, 'germainv@gmail.com', 0x30, '', '', 'Vincent', 'Germain', '$2y$10$sFO1wMJ7/dKTC9q/aq5JW.ltH2p2FX3DepLwG3brvQSn7rQWIzgzm', 0622743324, '1999-01-09', '2023-12-26 22:12:16', 'G1', 'en attente'),
(8, 'juliettejestaz@noel.fr', 0x30, '', '', 'Jestaz', 'Julie', '$2y$10$QvR7FG4pnLD6WadXm0x0HOKT0.L3x10t1/CidpoV85PypJ1yJVuiG', 0763097845, '1969-09-04', '2023-12-28 16:14:51', 'G1', 'en attente'),
(9, 'jfvincent@bib.com', 0x30, '', '', 'Adelia', 'Vincent', '$2y$10$qclpBv/ULLlFIy0QjRRBbOfaWwcq/ubSLV6uRUtweTh2P0HjxDIim', 0716348293, '1963-10-18', '2024-01-02 13:31:19', 'G1', 'en attente'),
(10, 'baba@beb.fr', 0x30, '', '', 'Lesage', 'Baba', '$2y$10$p5SPGTDhZd42jQ8PUlQlD.BdOBYp355FSQmRlKPe1SgXFOagUlOk2', 0123456789, '2000-08-29', '2024-01-02 15:45:51', 'G1', 'en attente'),
(11, 'bebe@beb.fr', 0x30, '', '', 'Lesage', 'Bebe', '$2y$10$fELDoEd5Tg54jtxWNXtJ0eS7aIE/NiSEfo2GHETrl.LXhu5GMLs.q', 0123456789, '1977-09-30', '2024-01-02 15:47:24', 'G1', 'en attente'),
(12, 'bibi@beb.fr', 0x30, '', '', 'Lesage', 'Bibi', '$2y$10$CPMesyuRvCc6niD1pA4/JePwZ3iXuc372E6tQ6P.JKURidWh4Ta0C', 0123456789, '1989-09-30', '2024-01-02 15:49:38', 'G1', 'en attente'),
(14, 'bobo@beb.fr', 0x30, '', '', 'Lesage', 'Bobo', '$2y$10$pn3mbIu8PUtFxERog3CSAeHuColqoD9dNQgtp7Xhngp8hjZ59Ddae', 0123456789, '1965-10-13', '2024-01-02 16:43:18', 'G1', 'en attente'),
(15, 'ernestker@gmail.com', 0x30, '', '', 'Kernel', 'Ernest', '$2y$10$q1crPNPmqpUvbI/nE.lLKO/PO5hP2OkYUYPrhCMKlnXKOizKRRGQ2', 0123456789, '2000-07-29', '2024-01-06 13:08:31', 'G1', 'en attente');

--
-- Déchargement des données de la table `adresse`
--
-- 

INSERT INTO `adresse` (`id_adresse`, `numetrue_adresse`, `codepostal_adresse`, `villeadresse_adresse`, `type_adresse`, `id_client`) VALUES
(1, '31 rue du fauteuil rose', 75003, 'Paris', 'facturation', 1),
(2, '32, rue du café', 75002, 'Paris', 'facturation', 2),
(3, '45, avenue des bases de données', 91230, 'Paris', 'facturation', 3),
(4, '1 rue moyenne', 13010, 'Marseille', 'facturation', 4),
(5, '25 allée des bois', 46887, 'Mulhouse', 'facturation', 5),
(6, '500 rue des pommiers roses', 13000, 'Marseille', 'facturation', 6),
(9, '6 rue des nerds', 91230, 'Montgeron', 'facturation', 7),
(49, '31 rue du fauteuil rose', 75003, 'Paris', 'livraison', 1),
(17, '6 rue d\'Escalibes d\'Hust', 75003, 'Marseille', 'livraison', 3),
(45, '1 rue moyenne', 13010, 'Marseille', 'livraison', 4),
(23, '4 rue Albert Muche', 34780, 'Bordeaux', 'facturation', 9),
(24, 'Rue primaire', 75001, 'Paris', 'facturation', 10),
(25, 'Rue primaire', 75001, 'Paris', 'facturation', 11),
(26, 'Rue primaire', 75001, 'Paris', 'facturation', 12),
(27, 'Rue primaire', 75001, 'Paris', 'livraison', 12),
(28, 'Rue primaire', 75001, 'Paris', 'facturation', 14),
(29, '6 rue d\'Escalibes d\'Hust', 91230, 'Montgeron', 'livraison', 6),
(46, '6 rue des nerds', 91230, 'Montgeron', 'livraison', 4),
(52, '32, rue du café', 75002, 'Paris', 'livraison', 2),
(48, '287 rue des motos', 13010, 'Marseille', 'facturation', 15),
(54, 'Rue primaire', 75001, 'Paris', 'livraison', 10),
(55, 'Rue primaire', 75001, 'Paris', 'livraison', 11),
(56, '4 rue Albert Muche', 34780, 'Bordeaux', 'livraison', 9),
(57, '6 rue des nerds', 91230, 'Montgeron', 'livraison', 7);

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_fournisseur`, `nom_produit`, `prixht_produit`, `quantitestock_produit`, `description_produit`, `categorie_produit`, `marque_produit`, `date_ajout_produit`, `statut_produit`) VALUES
(14, 1, 'Boite à outils rose', 100, 46, 'Boîte à outils d\un goût rare', 'peinture_droguerie', 'Nike', '2023-12-31 11:57:09', 'disponible'),
(25, 4, 'Gants de bricoleur', 15, 435, 'Beaux gants rose bien rembourrés', 'quincaillerie', 'Y-3', '2023-12-25 18:17:34', 'désactivé'),
(19, 1, 'Portes', 12, 65842, 'Jolies portes en bois massif peint', 'menuiserie_bois', 'Doors', '2023-12-31 11:57:16', 'désactivé'),
(20, 1, 'Caddie B52', 56, 500, 'Caddie rose bonbon', 'quincaillerie', 'MarioKart', '2023-12-31 11:57:19', 'supprimé'),
(21, 1, 'Scie à métaux', 345, 186, 'Superbe scie à métaux créée par Kanye West', 'outillerie', 'Yeezy', '2023-12-24 15:10:17', 'disponible'),
(22, 1, 'Perceuse2ouf', 300, 193, 'Vraiment une très belle perceuse', 'outillerie', 'BOOM', '2024-01-06 13:17:46', 'disponible'),
(23, 3, 'Robinet', 50, 1998, 'Robinet de qualité', 'chauffage_plomberie', 'Carrefour', '2023-12-24 23:25:27', 'disponible'),
(24, 1, 'Loupe', 300, 4961, 'Belle loupe en bronze', 'outillerie', 'Jacquemus', '2024-01-04 14:10:08', 'disponible'),
(26, 4, 'Marteau court', 12, 4900, 'Vrai marteau de chantier datant du 14e siècle utilisé par Charlemagne', 'outillerie', 'Castorama', '2023-12-25 18:18:41', 'désactivé'),
(27, 4, 'Pince coupante', 5, 9479, 'Attention ça coupe les fils électriques', 'outillerie', 'MacDonalds', '2023-12-25 18:19:34', 'désactivé'),
(28, 4, 'Tournevis', 45, 800, 'Pour les petites et les grandes vis, tournevis de qualité supérieure et franchement pas mal pour être honnête', 'outillerie', 'Puget', '2023-12-25 18:20:56', 'supprimé'),
(29, 5, 'Tondeuse électrique X3500', 1200, 525, 'Tondeuse qui tue. Plus dherbe plus de problème', 'jardin', 'BMW', '2023-12-25 18:24:25', 'disponible'),
(30, 5, 'Robot tondeuse ULTRAGORE', 5000, 41, 'Super robot, 20 000 tours par minute, 50 chevaux sous le capot et des brouettes', 'jardin', 'Stussy', '2023-12-25 18:25:48', 'disponible'),
(31, 5, 'Pelle Hightech', 450, 9998, 'Pelle rose qui donne lheure', 'jardin', 'Apple', '2023-12-25 18:26:49', 'disponible'),
(32, 5, 'Pelle fluo', 552, 42, 'Pelle rose fluorescente, édition limitée crée par Tim Cook lui même', 'jardin', 'Microsoft', '2023-12-25 18:27:41', 'disponible'),
(33, 4, 'Enclume fashion', 400, 4994, 'Magnifique enclume en fer forgé rose (ça existe)', 'quincaillerie', 'H&M', '2023-12-25 18:55:00', 'désactivé'),
(34, 1, 'Eponge absorbante X876', 2, 4468, 'Eponge qui aspire même les mauvaises intentions', 'peinture_droguerie', 'Mercedes', '2023-12-25 21:47:37', 'disponible');


--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `iban`, `bic`, `numcb`, `expirationcb`, `cryptogrammecb`, `type_paiement`, `titulaire`, `id_client`, `banquecb`) VALUES
(14, 'FR IBNKNPPVPPU', 'BICBICBICBI', NULL, NULL, NULL, 'iban', 'BOOM', 3, ''),
(18, NULL, NULL, 4970809022348062, '2024-02', 999, 'cb', 'BIBI LESAGE', 12, 'Crédit Coopératif'),
(16, NULL, NULL, 3456789056784567, '2024-11', 999, 'cb', 'ROSE', 1, 'LCL'),
(17, 'FR IBAN IBAN IBAN IBAN', 'BICBIC BACB', NULL, NULL, NULL, 'iban', 'ROSE', 1, ''),
(19, NULL, NULL, 4897340922783498, '2024-12', 999, 'cb', 'Elsa Lariviere', 6, 'Crédit Coopératif'),
(20, NULL, NULL, 4000300050004000, '2024-12', 999, 'cb', 'Elie VINCENT', 4, 'Société Générale'),
(21, NULL, NULL, 4000300020005000, '2024-06', 999, 'cb', 'JEAN DUPOND', 2, 'Lydia'),
(27, 'FR76 0897 9709 09878 A312', 'BICXLYDXXBI', NULL, NULL, NULL, 'iban', 'JEAN DUPOND', 2, ''),
(28, NULL, NULL, 4300300020005050, '2024-12', 999, 'cb', 'BABA LESAGE', 10, 'BNP'),
(29, NULL, NULL, 4000600080003000, '2025-08', 999, 'cb', 'BEBE LESAGE', 11, 'BNP'),
(30, 'FR89 3900 4990 B111', 'BXXCCILI678', NULL, NULL, NULL, 'iban', 'VINCENT ADELIA', 9, ''),
(31, NULL, NULL, 4500690930005000, '2027-12', 999, 'cb', 'GERMAIN VINCENT', 7, 'LCL');

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `date_commande`, `date_preparation`, `date_envoi`, `date_livraison`, `date_livree`, `idclient_commande`, `etat_commande`, `id_produit`, `quantité_produit`, `montant_total`, `id_commande_produit`, `id_adresse`, `id_paiement`, `id_fournisseur`) VALUES
(674349964, '2024-01-08 11:34:12', '2024-01-08 11:36:44', '2024-01-08 11:36:47', '2024-01-08 11:36:48', '2024-01-08 11:36:49', 10, 'validée', 24, 3, 1125.00, '674349964-24', 54, 28, 1),
(1996430484, '2024-01-08 11:32:37', '2024-01-08 12:24:06', NULL, NULL, NULL, 2, 'en préparation', 30, 1, 6250.00, '1996430484-30', 52, 27, 5),
(2102662334, '2024-01-08 11:30:55', '2024-01-08 11:47:54', '2024-01-08 11:47:56', '2024-01-08 11:47:57', '2024-01-08 11:47:59', 3, 'validée', 25, 10, 187.50, '2102662334-25', 17, 14, 4),
(873344382, '2024-01-08 11:23:07', '2024-01-08 11:36:51', NULL, NULL, NULL, 3, 'en préparation', 33, 1, 500.00, '873344382-33', 17, 14, 4),
(873344382, '2024-01-08 11:23:07', '2024-01-08 11:36:51', NULL, NULL, NULL, 3, 'en préparation', 14, 1, 125.00, '873344382-14', 17, 14, 1),
(674349964, '2024-01-08 11:34:12', '2024-01-08 11:36:44', '2024-01-08 11:36:47', '2024-01-08 11:36:48', '2024-01-08 11:36:49', 10, 'validée', 22, 1, 375.00, '674349964-22', 54, 28, 1),
(454253242, '2024-01-08 11:34:43', NULL, NULL, NULL, NULL, 10, 'à valider', 23, 1, 62.50, '454253242-23', 54, 28, 3),
(621323292, '2024-01-08 11:36:07', '2024-01-08 11:48:02', '2024-01-08 11:48:04', '2024-01-08 11:48:07', NULL, 1, 'en cours de livraison', 27, 20, 125.00, '621323292-27', 49, 16, 4),
(621323292, '2024-01-08 11:36:07', '2024-01-08 11:48:02', '2024-01-08 11:48:04', '2024-01-08 11:48:07', NULL, 1, 'en cours de livraison', 26, 100, 1500.00, '621323292-26', 49, 16, 4),
(621323292, '2024-01-08 11:36:07', '2024-01-08 11:48:02', '2024-01-08 11:48:04', '2024-01-08 11:48:07', NULL, 1, 'en cours de livraison', 28, 200, 11250.00, '621323292-28', 49, 16, 4),
(2133000130, '2024-01-08 11:38:14', '2024-01-08 11:54:34', '2024-01-08 11:54:35', '2024-01-08 11:54:37', '2024-01-08 11:54:39', 11, 'validée', 14, 1, 125.00, '2133000130-14', 55, 29, 1),
(141956983, '2024-01-08 11:38:44', '2024-01-08 11:54:46', '2024-01-08 11:54:48', '2024-01-08 11:54:50', '2024-01-08 11:54:52', 11, 'validée', 34, 10, 25.00, '141956983-34', 55, 29, 1),
(1557878176, '2024-01-08 11:39:07', '2024-01-08 12:23:58', '2024-01-08 12:23:59', '2024-01-08 12:23:59', '2024-01-08 12:24:01', 11, 'validée', 29, 1, 1500.00, '1557878176-29', 55, 29, 5),
(1983725337, '2024-01-08 11:41:13', '2024-01-08 12:23:52', '2024-01-08 12:23:53', NULL, NULL, 9, 'en cours d\'envoi', 32, 1, 690.00, '1983725337-32', 56, 30, 5),
(1983725337, '2024-01-08 11:41:13', '2024-01-08 12:23:52', '2024-01-08 12:23:53', NULL, NULL, 9, 'en cours d\'envoi', 31, 1, 562.50, '1983725337-31', 56, 30, 5),
(389480934, '2024-01-08 11:42:57', '2024-01-08 11:44:45', '2024-01-08 11:44:47', '2024-01-08 11:44:48', '2024-01-08 11:44:52', 7, 'refusée', 14, 1, 125.00, '389480934-14', 57, 31, 1),
(389480934, '2024-01-08 11:42:57', '2024-01-08 11:44:45', '2024-01-08 11:44:47', '2024-01-08 11:44:48', '2024-01-08 11:44:52', 7, 'refusée', 22, 1, 375.00, '389480934-22', 57, 31, 1),
(1774483157, '2024-01-08 11:45:35', '2024-01-08 12:23:48', '2024-01-08 12:23:49', '2024-01-08 12:23:50', '2024-01-08 12:23:51', 1, 'validée', 29, 50, 75000.00, '1774483157-29', 49, 17, 5),
(1777672364, '2024-01-08 11:48:40', NULL, NULL, NULL, NULL, 4, 'à valider', 19, 150, 2250.00, '1777672364-19', 45, 20, 1);



--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id_message`, `date_message`, `contenu_message`, `sens`, `idclient_message`, `idgestionnaire_message`, `type_message`) VALUES
(274, '2024-01-08 11:44:47', 'Votre commande N°389480934 est en cours d\'envoi.', 0x31, 7, 'G1', 'notification'),
(315, '2024-01-08 12:29:39', 'Votre commande N°1557878176 a été validée par le client.', 0x31, 0, '', 'notification'),
(314, '2024-01-08 12:29:38', 'Votre commande N°141956983 a été validée par le client.', 0x31, 0, '', 'notification'),
(313, '2024-01-08 12:29:32', 'Votre commande N°2133000130 a été validée par le client.', 0x31, 0, '', 'notification'),
(312, '2024-01-08 12:25:07', 'Votre commande N°1774483157 a été validée par le client.', 0x31, 0, '', 'notification'),
(311, '2024-01-08 12:24:06', 'Votre commande N°1996430484 est en cours de préparation.', 0x31, 2, 'G1', 'notification'),
(310, '2024-01-08 12:24:01', 'Votre commande N°1557878176 a été livrée.', 0x31, 11, 'G1', 'notification'),
(309, '2024-01-08 12:23:59', 'Votre commande N°1557878176 est en cours de livraison.', 0x31, 11, 'G1', 'notification'),
(308, '2024-01-08 12:23:59', 'Votre commande N°1557878176 est en cours d\'envoi.', 0x31, 11, 'G1', 'notification'),
(307, '2024-01-08 12:23:58', 'Votre commande N°1557878176 est en cours de préparation.', 0x31, 11, 'G1', 'notification'),
(306, '2024-01-08 12:23:53', 'Votre commande N°1983725337 est en cours d\'envoi.', 0x31, 9, 'G1', 'notification'),
(305, '2024-01-08 12:23:52', 'Votre commande N°1983725337 est en cours de préparation.', 0x31, 9, 'G1', 'notification'),
(304, '2024-01-08 12:23:51', 'Votre commande N°1774483157 a été livrée.', 0x31, 1, 'G1', 'notification'),
(303, '2024-01-08 12:23:50', 'Votre commande N°1774483157 est en cours de livraison.', 0x31, 1, 'G1', 'notification'),
(302, '2024-01-08 12:23:49', 'Votre commande N°1774483157 est en cours d\'envoi.', 0x31, 1, 'G1', 'notification'),
(301, '2024-01-08 12:23:48', 'Votre commande N°1774483157 est en cours de préparation.', 0x31, 1, 'G1', 'notification'),
(300, '2024-01-08 12:23:08', 'Votre compte professionnel a été validé par votre gestionnaire ROSE.', 0x31, 5, 'G1', 'notification'),
(299, '2024-01-08 11:54:52', 'Votre commande N°141956983 a été livrée.', 0x31, 11, 'G1', 'notification'),
(298, '2024-01-08 11:54:50', 'Votre commande N°141956983 est en cours de livraison.', 0x31, 11, 'G1', 'notification'),
(297, '2024-01-08 11:54:48', 'Votre commande N°141956983 est en cours d\'envoi.', 0x31, 11, 'G1', 'notification'),
(296, '2024-01-08 11:54:46', 'Votre commande N°141956983 est en cours de préparation.', 0x31, 11, 'G1', 'notification'),
(295, '2024-01-08 11:54:39', 'Votre commande N°2133000130 a été livrée.', 0x31, 11, 'G1', 'notification'),
(294, '2024-01-08 11:54:37', 'Votre commande N°2133000130 est en cours de livraison.', 0x31, 11, 'G1', 'notification'),
(293, '2024-01-08 11:54:35', 'Votre commande N°2133000130 est en cours d\'envoi.', 0x31, 11, 'G1', 'notification'),
(292, '2024-01-08 11:54:34', 'Votre commande N°2133000130 est en cours de préparation.', 0x31, 11, 'G1', 'notification'),
(291, '2024-01-08 11:53:57', 'bonjour, je vais contacter le fournisseur afin de régler la situation et reviens vers vous', 0x31, 7, 'G1', 'message'),
(290, '2024-01-08 11:53:06', 'Votre annoncePortes a été désactivée par votre gestionnaire ROSE.', 0x31, 19, 'G1', 'notification'),
(289, '2024-01-08 11:52:08', 'Votre compte professionnel a été bloqué par votre gestionnaire ROSE. Contactez-le pour régulariser votre situation.', 0x31, 4, 'G1', 'notification'),
(288, '2024-01-08 11:52:05', 'Votre compte professionnel a été validé par votre gestionnaire ROSE.', 0x31, 3, 'G1', 'notification'),
(287, '2024-01-08 11:49:29', 'Votre commande N°2102662334 a été validée par le client.', 0x31, 0, '', 'notification'),
(286, '2024-01-08 11:48:07', 'Votre commande N°621323292 est en cours de livraison.', 0x31, 1, 'G1', 'notification'),
(285, '2024-01-08 11:48:04', 'Votre commande N°621323292 est en cours d\'envoi.', 0x31, 1, 'G1', 'notification'),
(283, '2024-01-08 11:47:59', 'Votre commande N°2102662334 a été livrée.', 0x31, 3, 'G1', 'notification'),
(284, '2024-01-08 11:48:02', 'Votre commande N°621323292 est en cours de préparation.', 0x31, 1, 'G1', 'notification'),
(282, '2024-01-08 11:47:57', 'Votre commande N°2102662334 est en cours de livraison.', 0x31, 3, 'G1', 'notification'),
(281, '2024-01-08 11:47:56', 'Votre commande N°2102662334 est en cours d\'envoi.', 0x31, 3, 'G1', 'notification'),
(280, '2024-01-08 11:47:54', 'Votre commande N°2102662334 est en cours de préparation.', 0x31, 3, 'G1', 'notification'),
(279, '2024-01-08 11:47:13', 'bonjour, j\'ai refusé ma commande car la boite est endommagée que dois-je faire', 0x30, 7, 'G1', 'message'),
(278, '2024-01-08 11:46:34', 'Votre commande N°389480934 a été refusée par le client. Contactez votre gestionnaire.', 0x31, 0, '', 'notification'),
(277, '2024-01-08 11:46:05', 'Votre commande N°674349964 a été validée par le client.', 0x31, 0, '', 'notification'),
(276, '2024-01-08 11:44:52', 'Votre commande N°389480934 a été livrée.', 0x31, 7, 'G1', 'notification'),
(275, '2024-01-08 11:44:48', 'Votre commande N°389480934 est en cours de livraison.', 0x31, 7, 'G1', 'notification'),
(272, '2024-01-08 11:36:51', 'Votre commande N°873344382 est en cours de préparation.', 0x31, 3, 'G1', 'notification'),
(273, '2024-01-08 11:44:45', 'Votre commande N°389480934 est en cours de préparation.', 0x31, 7, 'G1', 'notification'),
(271, '2024-01-08 11:36:49', 'Votre commande N°674349964 a été livrée.', 0x31, 10, 'G1', 'notification'),
(270, '2024-01-08 11:36:48', 'Votre commande N°674349964 est en cours de livraison.', 0x31, 10, 'G1', 'notification'),
(268, '2024-01-08 11:36:44', 'Votre commande N°674349964 est en cours de préparation.', 0x31, 10, 'G1', 'notification'),
(269, '2024-01-08 11:36:47', 'Votre commande N°674349964 est en cours d\'envoi.', 0x31, 10, 'G1', 'notification');


--table photo avec photos vides-- 

INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(9, 'product1.png', 14, './images/product1.png' , 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(35, 'pincecoupante2.png', 27, './images/pincecoupante2.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(31, 'gantsbricolage2.png', 25, './images/gantsbricolage2.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(32, 'marteau.png', 26, './images/marteau.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(33, 'marteau2.png', 26, './images/marteau2.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(34, 'pincecoupante.png', 27, './images/pincecoupante.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(30, 'gantsbricolage.png', 25, './images/gantsbricolage.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(24, 'logout3.png', 19, './images/logout3.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(22, 'logout1.png', 19, './images/logout1.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(23, 'logout2.png', 19, './images/logout2.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(25, 'cart.png', 20, './images/cart.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(26, 'product3.png', 21, './images/product3.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(27, 'product4.png', 22, './images/product4.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(28, 'robinet.png', 23, './images/robinet.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(36, 'tournevis (2).png', 28, './images/tournevis (2).png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(37, 'tournevis.png', 28, './images/tournevis.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(38, 'tondeusejardin.png', 29, './images/tondeusejardin.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(39, 'tondeusejardin2.png', 29,'./images/tondeusejardin2.png' , 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(40, 'tondeusejardin3.png', 29, './images/tondeusejardin3.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(41, 'tondeuserobot.png', 30, './images/tondeuserobot.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(42, 'pelle.png', 31, './images/pelle.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(43, 'pelle2.png', 31, './images/pelle2.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(44, 'pelle3.png', 31, './images/pelle3.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(45, 'pellefluo.png', 32, './images/pellefluo.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(46, 'enclume.png', 33, './images/enclume.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(47, 'eponge.png', 34, './images/eponge.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(49, 'search.png', 24, './images/search.png', 'image/png');
INSERT INTO `photo` (`id_photo_produit`, `file_photo_produit`, `id_produit`, `image`, `image_type`) VALUES
(50, 'arrosoir.png', 35, './images/arrosoir.png', 'image/png');