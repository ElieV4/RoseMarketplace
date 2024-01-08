

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo_produit` int NOT NULL AUTO_INCREMENT,
  `file_photo_produit` varchar(250) DEFAULT NULL,
  `id_produit` int DEFAULT NULL,
  `image` longblob,
  `image_type` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_photo_produit`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


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

