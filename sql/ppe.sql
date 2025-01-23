-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 12 jan. 2025 à 20:25
-- Version du serveur : 8.0.35
-- Version de PHP : 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ppe`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pHashMdpUser` (IN `p_idUser` INT(10), IN `p_nomUser` VARCHAR(50), IN `p_prenomUser` VARCHAR(50), IN `p_emailUser` VARCHAR(50), IN `p_mdpUser` VARCHAR(255), IN `p_adresseUser` VARCHAR(50), IN `p_roleUser` ENUM('admin','client'), IN `p_dateInscriptionUser` DATE)   BEGIN
                            INSERT INTO user (idUser, nomUser, prenomUser, emailUser, mdpUser, adresseUser, roleUser, dateInscriptionUser)
                            VALUES (
                                p_idUser,
                                p_nomUser,
                                p_prenomUser,
                                p_emailUser,
                                SHA1(p_mdpUser), 
                                p_adresseUser,
                                p_roleUser,
                                p_dateInscriptionUser
                            );
                        END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pVerifierDisponibiliteLivre` (IN `idLivre` INT, IN `quantiteDemandee` INT, OUT `disponible` BOOLEAN)   begin
                            declare stock int;
                            select exemplaireLivre
                            into stock
                            from livre
                            WHERE idLivre = idLivre;
                            if stock >= quantiteDemandee then
                                set disponible = TRUE;
                            else
                                set disponible = FALSE;
                            end if;
                        end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCommande` int NOT NULL,
  `dateCommande` date DEFAULT NULL,
  `statutCommande` enum('en attente','annulée','expédiée','arrivée') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dateLivraisonCommande` date DEFAULT NULL,
  `idUser` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `dateCommande`, `statutCommande`, `dateLivraisonCommande`, `idUser`) VALUES
(153, '2025-01-10', 'en attente', '2025-01-17', 2),
(158, '2025-01-11', 'en attente', '2025-01-18', 2),
(159, '2025-01-11', 'en attente', '2025-01-18', 2),
(160, '2025-01-11', 'en attente', '2025-01-18', 2),
(161, '2025-01-11', 'en attente', '2025-01-18', 2),
(164, '2025-01-12', 'en attente', '2025-01-19', 2),
(165, '2025-01-12', 'en attente', '2025-01-19', 2),
(166, '2025-01-12', 'en attente', '2025-01-19', 2),
(167, '2025-01-12', 'en attente', '2025-01-19', 2),
(168, '2025-01-12', 'en attente', '2025-01-19', 2),
(169, '2025-01-12', 'en attente', '2025-01-19', 2),
(170, '2025-01-12', 'en attente', '2025-01-19', 2),
(171, '2025-01-12', 'en attente', '2025-01-19', 2),
(172, '2025-01-12', 'en attente', '2025-01-19', 2),
(173, '2025-01-12', 'en attente', '2025-01-19', 2),
(174, '2025-01-12', 'en attente', '2025-01-19', 2),
(175, '2025-01-12', 'en attente', '2025-01-19', 2),
(176, '2025-01-12', 'en attente', '2025-01-19', 2),
(177, '2025-01-12', 'en attente', '2025-01-19', 2),
(178, '2025-01-12', 'en attente', '2025-01-19', 2),
(179, '2025-01-12', 'en attente', '2025-01-19', 2),
(180, '2025-01-12', 'en attente', '2025-01-19', 2),
(181, '2025-01-12', 'en attente', '2025-01-19', 2),
(182, '2025-01-12', 'en attente', '2025-01-19', 2),
(183, '2025-01-12', 'en attente', '2025-01-19', 2),
(184, '2025-01-12', 'en attente', '2025-01-19', 2),
(185, '2025-01-12', 'en attente', '2025-01-19', 2),
(186, '2025-01-12', 'en attente', '2025-01-19', 2),
(187, '2025-01-12', 'en attente', '2025-01-19', 2),
(188, '2025-01-12', 'en attente', '2025-01-19', 2),
(189, '2025-01-12', 'en attente', '2025-01-19', 2),
(190, '2025-01-12', 'en attente', '2025-01-19', 2),
(191, '2025-01-12', 'en attente', '2025-01-19', 2),
(192, '2025-01-12', 'en attente', '2025-01-19', 2),
(193, '2025-01-12', 'en attente', '2025-01-19', 2),
(194, '2025-01-12', 'en attente', '2025-01-19', 2),
(195, '2025-01-12', 'en attente', '2025-01-19', 2),
(196, '2025-01-12', 'en attente', '2025-01-19', 2),
(197, '2025-01-12', 'en attente', '2025-01-19', 2),
(198, '2025-01-12', 'en attente', '2025-01-19', 2),
(199, '2025-01-12', 'en attente', '2025-01-19', 2),
(200, '2025-01-12', 'en attente', '2025-01-19', 2),
(201, '2025-01-12', 'en attente', '2025-01-19', 2),
(202, '2025-01-12', 'en attente', '2025-01-19', 2),
(203, '2025-01-12', 'en attente', '2025-01-19', 2),
(204, '2025-01-12', 'en attente', '2025-01-19', 2),
(205, '2025-01-12', 'en attente', '2025-01-19', 2),
(206, '2025-01-12', 'en attente', '2025-01-19', 2),
(207, '2025-01-12', 'en attente', '2025-01-19', 2),
(210, '2025-01-12', 'en attente', '2025-01-19', 2),
(211, '2025-01-12', 'en attente', '2025-01-19', 2),
(212, '2025-01-12', 'en attente', '2025-01-19', 2),
(213, '2025-01-12', 'en attente', '2025-01-19', 2),
(214, '2025-01-12', 'en attente', '2025-01-19', 2),
(215, '2025-01-12', 'en attente', '2025-01-19', 2),
(216, '2025-01-12', 'en attente', '2025-01-19', 2),
(217, '2025-01-12', 'en attente', '2025-01-19', 2),
(218, '2025-01-12', 'en attente', '2025-01-19', 2),
(219, '2025-01-12', 'en attente', '2025-01-19', 2),
(220, '2025-01-12', 'en attente', '2025-01-19', 2),
(221, '2025-01-12', 'en attente', '2025-01-19', 2),
(222, '2025-01-12', 'en attente', '2025-01-19', 2),
(223, '2025-01-12', 'en attente', '2025-01-19', 2),
(224, '2025-01-12', 'en attente', '2025-01-19', 2),
(225, '2025-01-12', 'en attente', '2025-01-19', 2),
(226, '2025-01-12', 'en attente', '2025-01-19', 2),
(227, '2025-01-12', 'en attente', '2025-01-19', 2),
(228, '2025-01-12', 'en attente', '2025-01-19', 2),
(229, '2025-01-12', 'en attente', '2025-01-19', 2),
(230, '2025-01-12', 'en attente', '2025-01-19', 2),
(231, '2025-01-12', 'en attente', '2025-01-19', 2),
(232, '2025-01-12', 'en attente', '2025-01-19', 2),
(233, '2025-01-12', 'en attente', '2025-01-19', 2),
(234, '2025-01-12', 'en attente', '2025-01-19', 2),
(235, '2025-01-12', 'en attente', '2025-01-19', 2),
(236, '2025-01-12', 'en attente', '2025-01-19', 2),
(237, '2025-01-12', 'en attente', '2025-01-19', 2),
(238, '2025-01-12', 'en attente', '2025-01-19', 2),
(239, '2025-01-12', 'en attente', '2025-01-19', 2),
(240, '2025-01-12', 'en attente', '2025-01-19', 2),
(241, '2025-01-12', 'en attente', '2025-01-19', 2),
(242, '2025-01-12', 'en attente', '2025-01-19', 2),
(243, '2025-01-12', 'en attente', '2025-01-19', 2),
(244, '2025-01-12', 'en attente', '2025-01-19', 2),
(245, '2025-01-12', 'en attente', '2025-01-19', 2),
(246, '2025-01-12', 'en attente', '2025-01-19', 2),
(247, '2025-01-12', 'en attente', '2025-01-19', 2),
(248, '2025-01-12', 'en attente', '2025-01-19', 2),
(249, '2025-01-12', 'en attente', '2025-01-19', 2),
(250, '2025-01-12', 'en attente', '2025-01-19', 2),
(251, '2025-01-12', 'en attente', '2025-01-19', 2),
(252, '2025-01-12', 'en attente', '2025-01-19', 2),
(253, '2025-01-12', 'en attente', '2025-01-19', 2),
(254, '2025-01-12', 'en attente', '2025-01-19', 2),
(255, '2025-01-12', 'en attente', '2025-01-19', 2),
(256, '2025-01-12', 'en attente', '2025-01-19', 2),
(257, '2025-01-12', 'en attente', '2025-01-19', 2),
(258, '2025-01-12', 'en attente', '2025-01-19', 2),
(259, '2025-01-12', 'en attente', '2025-01-19', 2),
(260, '2025-01-12', 'en attente', '2025-01-19', 2),
(261, '2025-01-12', 'en attente', '2025-01-19', 2),
(262, '2025-01-12', 'en attente', '2025-01-19', 2),
(263, '2025-01-12', 'en attente', '2025-01-19', 2),
(264, '2025-01-12', 'en attente', '2025-01-19', 2);

--
-- Déclencheurs `commande`
--
DELIMITER $$
CREATE TRIGGER `tExemplaireLivreCommande` BEFORE INSERT ON `commande` FOR EACH ROW begin
    declare compteurCommande int;
    declare compteurLigneCommande int;

    select count(idCommande)
    into compteurCommande
    from commande
    where idCommande = NEW.idCommande;

    select count(idCommande)
    into compteurLigneCommande
    from ligneCommande
    where idCommande = NEW.idCommande;

    if compteurCommande >= compteurLigneCommande then
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Erreur : Livre déjà dans le panier. Veuillez modifier le nombre d'exemplaire.";
    end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tInsertNotification` AFTER INSERT ON `commande` FOR EACH ROW begin
insert into notification
values (null, CONCAT('Nouvelle commande. ID : ', NEW.idCommande), curdate());
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `ligneCommande`
--

CREATE TABLE `ligneCommande` (
  `idLigneCommande` int auto_increment not null,
  `idCommande` int NOT NULL,
  `idLivre` int NOT NULL,
  `quantiteLigneCommande` int NOT NULL DEFAULT '1',
  primary key (idLigneCommande),
  FOREIGN KEY (idCommande) REFERENCES commande(idCommande) ON DELETE CASCADE,
  FOREIGN KEY (idLivre) REFERENCES livre(idLivre) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ligneCommande`
--

INSERT INTO `ligneCommande` (`idCommande`, `idLivre`, `quantiteLigneCommande`) VALUES
(158, 1, 5),
(159, 1, 1),
(160, 4, 1),
(161, 2, 1),
(190, 1, 1),
(191, 1, 1),
(192, 1, 1),
(198, 8, 3),
(213, 1, 1),
(214, 1, 1),
(215, 1, 1),
(216, 1, 1),
(217, 1, 1),
(218, 1, 1),
(219, 1, 1),
(220, 2, 1),
(223, 1, 1),
(227, 1, 1),
(228, 1, 1),
(235, 1, 1),
(236, 1, 1),
(238, 1, 1),
(239, 1, 1),
(240, 1, 1),
(246, 1, 1),
(247, 1, 1);

--
-- Déclencheurs `ligneCommande`
--
DELIMITER $$
CREATE TRIGGER `tExemplaireLivreLigneCommande` BEFORE INSERT ON `ligneCommande` FOR EACH ROW begin
declare existingQuantity int;
select quantiteLigneCommande
into existingQuantity
from ligneCommande
where idLivre = NEW.idLivre
LIMIT 1;
if existingQuantity is not null then
    SIGNAL SQLSTATE "45000"
    set MESSAGE_TEXT = "Erreur : Livre déjà dans le panier. Veuillez modifier le nombre d'exemplaire.";
end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tInsertStockCommande` AFTER INSERT ON `ligneCommande` FOR EACH ROW BEGIN
                        UPDATE livre
                        SET exemplaireLivre = exemplaireLivre - NEW.quantiteLigneCommande
                        WHERE idLivre = NEW.idLivre;
                        END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tStockNull` BEFORE INSERT ON `ligneCommande` FOR EACH ROW begin 
declare exemplaireLivreDisponible INT;
select exemplaireLivre into exemplaireLivreDisponible from livre where idLivre = NEW.idLivre;
if exemplaireLivreDisponible < NEW.quantiteLigneCommande then
                        SIGNAL SQLSTATE '45000'
                        set MESSAGE_TEXT = 'Erreur : Stock insuffisant pour le livre.';
end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tUpdateStockCommande` AFTER UPDATE ON `ligneCommande` FOR EACH ROW BEGIN
    UPDATE livre
    SET exemplaireLivre = exemplaireLivre - (NEW.quantiteLigneCommande - OLD.quantiteLigneCommande)
    WHERE idLivre = NEW.idLivre;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `idLivre` int NOT NULL,
  `nomLivre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `categorieLivre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `auteurLivre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `imageLivre` varchar(50) NOT NULL,
  `exemplaireLivre` int DEFAULT NULL,
  `prixLivre` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`idLivre`, `nomLivre`, `categorieLivre`, `auteurLivre`, `imageLivre`, `exemplaireLivre`, `prixLivre`) VALUES
(1, 'Alcools', 'Recueil de Poèmes', 'Apollinaire', 'alcools.jpg', 41, 12.50),
(2, 'Crime et Chatiment', 'Roman', 'Dostoïevski', 'crime_et_chatiment.jpg', 39, 15.00),
(3, 'l\'Etranger', 'Roman', 'Camus', 'l_etranger.jpg', 0, 10.00),
(4, 'L\'Odyssée', 'Histoire', 'Homère', 'l_odyssee.jpg', 0, 13.50),
(5, 'Les Fleurs du Mal', 'Recueil de Poèmes', 'Baudelaire', 'les_fleurs_du_mal.jpg', 75, 14.00),
(6, 'PHP et MySQL pour les nuls', 'Programmation', 'Valade', 'php_et_mysql_pour_les_nuls.jpg', 0, 22.00),
(7, 'Programmer en Java', 'Programmation', 'Delannoy', 'programmer_en_java.jpg', 0, 25.00),
(8, 'SPQR', 'Histoire', 'Beard', 'spqr.jpg', 28, 18.00);

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `idNotification` int NOT NULL,
  `messageNotification` text,
  `dateNotification` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`idNotification`, `messageNotification`, `dateNotification`) VALUES
(1, 'Nouvelle commande ajoutée avec ID : 210', '2025-01-11 23:00:00'),
(2, 'Nouvelle commande ajoutée avec ID : 211', '2025-01-11 23:00:00'),
(3, 'Nouvelle commande. ID : 212', '2025-01-11 23:00:00'),
(4, 'Nouvelle commande. ID : 213', '2025-01-11 23:00:00'),
(5, 'Nouvelle commande. ID : 214', '2025-01-11 23:00:00'),
(6, 'Nouvelle commande. ID : 215', '2025-01-11 23:00:00'),
(7, 'Nouvelle commande. ID : 216', '2025-01-11 23:00:00'),
(8, 'Nouvelle commande. ID : 217', '2025-01-11 23:00:00'),
(9, 'Nouvelle commande. ID : 218', '2025-01-11 23:00:00'),
(10, 'Nouvelle commande. ID : 219', '2025-01-11 23:00:00'),
(11, 'Nouvelle commande. ID : 220', '2025-01-11 23:00:00'),
(12, 'Nouvelle commande. ID : 221', '2025-01-11 23:00:00'),
(13, 'Nouvelle commande. ID : 222', '2025-01-11 23:00:00'),
(14, 'Nouvelle commande. ID : 223', '2025-01-11 23:00:00'),
(15, 'Nouvelle commande. ID : 224', '2025-01-11 23:00:00'),
(16, 'Nouvelle commande. ID : 225', '2025-01-11 23:00:00'),
(17, 'Nouvelle commande. ID : 226', '2025-01-11 23:00:00'),
(18, 'Nouvelle commande. ID : 227', '2025-01-11 23:00:00'),
(19, 'Nouvelle commande. ID : 228', '2025-01-11 23:00:00'),
(20, 'Nouvelle commande. ID : 229', '2025-01-11 23:00:00'),
(21, 'Nouvelle commande. ID : 230', '2025-01-11 23:00:00'),
(22, 'Nouvelle commande. ID : 231', '2025-01-11 23:00:00'),
(23, 'Nouvelle commande. ID : 232', '2025-01-11 23:00:00'),
(24, 'Nouvelle commande. ID : 233', '2025-01-11 23:00:00'),
(25, 'Nouvelle commande. ID : 234', '2025-01-11 23:00:00'),
(26, 'Nouvelle commande. ID : 235', '2025-01-11 23:00:00'),
(27, 'Nouvelle commande. ID : 236', '2025-01-11 23:00:00'),
(28, 'Nouvelle commande. ID : 237', '2025-01-11 23:00:00'),
(29, 'Nouvelle commande. ID : 238', '2025-01-11 23:00:00'),
(30, 'Nouvelle commande. ID : 239', '2025-01-11 23:00:00'),
(31, 'Nouvelle commande. ID : 240', '2025-01-11 23:00:00'),
(32, 'Nouvelle commande. ID : 241', '2025-01-11 23:00:00'),
(33, 'Nouvelle commande. ID : 242', '2025-01-11 23:00:00'),
(34, 'Nouvelle commande. ID : 243', '2025-01-11 23:00:00'),
(35, 'Nouvelle commande. ID : 244', '2025-01-11 23:00:00'),
(36, 'Nouvelle commande. ID : 245', '2025-01-11 23:00:00'),
(37, 'Nouvelle commande. ID : 246', '2025-01-11 23:00:00'),
(38, 'Nouvelle commande. ID : 247', '2025-01-11 23:00:00'),
(39, 'Nouvelle commande. ID : 248', '2025-01-11 23:00:00'),
(40, 'Nouvelle commande. ID : 249', '2025-01-11 23:00:00'),
(41, 'Nouvelle commande. ID : 250', '2025-01-11 23:00:00'),
(42, 'Nouvelle commande. ID : 251', '2025-01-11 23:00:00'),
(43, 'Nouvelle commande. ID : 252', '2025-01-11 23:00:00'),
(44, 'Nouvelle commande. ID : 253', '2025-01-11 23:00:00'),
(45, 'Nouvelle commande. ID : 254', '2025-01-11 23:00:00'),
(46, 'Nouvelle commande. ID : 255', '2025-01-11 23:00:00'),
(47, 'Nouvelle commande. ID : 256', '2025-01-11 23:00:00'),
(48, 'Nouvelle commande. ID : 257', '2025-01-11 23:00:00'),
(49, 'Nouvelle commande. ID : 258', '2025-01-11 23:00:00'),
(50, 'Nouvelle commande. ID : 259', '2025-01-11 23:00:00'),
(51, 'Nouvelle commande. ID : 260', '2025-01-11 23:00:00'),
(52, 'Nouvelle commande. ID : 261', '2025-01-11 23:00:00'),
(53, 'Nouvelle commande. ID : 262', '2025-01-11 23:00:00'),
(54, 'Nouvelle commande. ID : 263', '2025-01-11 23:00:00'),
(55, 'Nouvelle commande. ID : 264', '2025-01-11 23:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `nomUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prenomUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `emailUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mdpUser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `adresseUser` varchar(50) NOT NULL,
  `roleUser` enum('admin','client') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dateInscriptionUser` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `nomUser`, `prenomUser`, `emailUser`, `mdpUser`, `adresseUser`, `roleUser`, `dateInscriptionUser`) VALUES
(1, 'AIT-MOHAMMED', 'Ryles', 'ryles@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'admin', NULL),
(2, 'DUBOIS', 'Jean', 'jean@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '17 rue des pommes', 'client', NULL),
(12, 'marie', 'm', 'm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', NULL),
(13, 'jean', 'valjean', 'klza', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', NULL),
(14, 'poi', 'poi', 'poi', '8abcda2dba9a5c5c674e659333828582122c5f56', '', 'client', '2025-01-08'),
(15, 'insert', 'insert', 'i', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', '2025-01-09'),
(16, 'insert', 'insert', 'i', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', '2025-01-09');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vcommandesenattente`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vcommandesenattente` (
`nbCommandeEnAttente` bigint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vlivresenstock`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vlivresenstock` (
`exemplaireLivre` int
,`idLivre` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vmeilleuresventes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vmeilleuresventes` (
`idLivre` int
,`nomLivre` varchar(50)
,`totalVendu` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vnbmaxlivre`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vnbmaxlivre` (
`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vnbminlivre`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vnbminlivre` (
`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vnommaxlivre`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vnommaxlivre` (
`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vnomminlivre`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vnomminlivre` (
`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vtotalcommande`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotalcommande` (
`idUser` int
,`totalCommande` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vtotallivre`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotallivre` (
`idCommande` int
,`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(22,2)
);

-- --------------------------------------------------------

--
-- Structure de la vue `vcommandesenattente`
--
DROP TABLE IF EXISTS `vcommandesenattente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vcommandesenattente`  AS SELECT count(`c`.`idCommande`) AS `nbCommandeEnAttente` FROM `commande` AS `c` WHERE (`c`.`statutCommande` = 'en attente') ;

-- --------------------------------------------------------

--
-- Structure de la vue `vlivresenstock`
--
DROP TABLE IF EXISTS `vlivresenstock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vlivresenstock`  AS SELECT `l`.`idLivre` AS `idLivre`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `l`.`exemplaireLivre` AS `exemplaireLivre` FROM `livre` AS `l` WHERE (`l`.`exemplaireLivre` <= 5) ;

-- --------------------------------------------------------

--
-- Structure de la vue `vmeilleuresventes`
--
DROP TABLE IF EXISTS `vmeilleuresventes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vmeilleuresventes`  AS SELECT `l`.`idLivre` AS `idLivre`, `l`.`nomLivre` AS `nomLivre`, sum(`li`.`quantiteLigneCommande`) AS `totalVendu` FROM (`lignecommande` `li` join `livre` `l` on((`li`.`idLivre` = `l`.`idLivre`))) GROUP BY `l`.`idLivre`, `l`.`nomLivre` ORDER BY `totalVendu` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vnbmaxlivre`
--
DROP TABLE IF EXISTS `vnbmaxlivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vnbmaxlivre`  AS SELECT `vtotallivre`.`idUser` AS `idUser`, `vtotallivre`.`nomLivre` AS `nomLivre`, `vtotallivre`.`prixLivre` AS `prixLivre`, `vtotallivre`.`quantiteLigneCommande` AS `quantiteLigneCommande`, `vtotallivre`.`totalLivre` AS `totalLivre` FROM `vtotallivre` ORDER BY `vtotallivre`.`totalLivre` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vnbminlivre`
--
DROP TABLE IF EXISTS `vnbminlivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vnbminlivre`  AS SELECT `vtotallivre`.`idUser` AS `idUser`, `vtotallivre`.`nomLivre` AS `nomLivre`, `vtotallivre`.`prixLivre` AS `prixLivre`, `vtotallivre`.`quantiteLigneCommande` AS `quantiteLigneCommande`, `vtotallivre`.`totalLivre` AS `totalLivre` FROM `vtotallivre` ORDER BY `vtotallivre`.`totalLivre` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vnommaxlivre`
--
DROP TABLE IF EXISTS `vnommaxlivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vnommaxlivre`  AS SELECT `vtotallivre`.`idUser` AS `idUser`, `vtotallivre`.`nomLivre` AS `nomLivre`, `vtotallivre`.`prixLivre` AS `prixLivre`, `vtotallivre`.`quantiteLigneCommande` AS `quantiteLigneCommande`, `vtotallivre`.`totalLivre` AS `totalLivre` FROM `vtotallivre` ORDER BY `vtotallivre`.`nomLivre` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vnomminlivre`
--
DROP TABLE IF EXISTS `vnomminlivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vnomminlivre`  AS SELECT `vtotallivre`.`idUser` AS `idUser`, `vtotallivre`.`nomLivre` AS `nomLivre`, `vtotallivre`.`prixLivre` AS `prixLivre`, `vtotallivre`.`quantiteLigneCommande` AS `quantiteLigneCommande`, `vtotallivre`.`totalLivre` AS `totalLivre` FROM `vtotallivre` ORDER BY `vtotallivre`.`nomLivre` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotalcommande`
--
DROP TABLE IF EXISTS `vtotalcommande`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotalcommande`  AS SELECT `v`.`idUser` AS `idUser`, sum(`v`.`totalLivre`) AS `totalCommande` FROM `vtotallivre` AS `v` GROUP BY `v`.`idUser` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotallivre`
--
DROP TABLE IF EXISTS `vtotallivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotallivre`  AS SELECT `li`.`idCommande` AS `idCommande`, `c`.`idUser` AS `idUser`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `li`.`quantiteLigneCommande` AS `quantiteLigneCommande`, (`l`.`prixLivre` * `li`.`quantiteLigneCommande`) AS `totalLivre` FROM ((`livre` `l` join `lignecommande` `li` on((`l`.`idLivre` = `li`.`idLivre`))) join `commande` `c` on((`c`.`idCommande` = `li`.`idCommande`))) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `fk_c` (`idUser`);

--
-- Index pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  ADD PRIMARY KEY (`idCommande`,`idLivre`),
  ADD KEY `idLivre` (`idLivre`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`idLivre`),
  ADD UNIQUE KEY `idlivre` (`idLivre`),
  ADD KEY `idLivre_2` (`idLivre`),
  ADD KEY `idLivre_3` (`idLivre`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`idNotification`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `idLivre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `idNotification` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_c` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  ADD CONSTRAINT `lignecommande_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`),
  ADD CONSTRAINT `lignecommande_ibfk_2` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
