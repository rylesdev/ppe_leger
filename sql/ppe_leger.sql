-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 09 mai 2025 à 23:26
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
-- Base de données : `ppe_leger`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pInsertLivre` (IN `in_nomLivre` VARCHAR(255), IN `in_auteurLivre` VARCHAR(255), IN `in_imageLivre` VARCHAR(255), IN `in_exemplaireLivre` INT, IN `in_prixLivre` DECIMAL(10,2), IN `in_nomCategorie` VARCHAR(255), IN `in_nomMaisonEdition` VARCHAR(255), IN `in_nomPromotion` VARCHAR(255))   BEGIN
    DECLARE p_idCategorie INT;
    DECLARE p_idMaisonEdition INT;
    DECLARE p_idPromotion INT;

    SELECT idCategorie INTO p_idCategorie
    FROM categorie
    WHERE nomCategorie = in_nomCategorie;
    
    SELECT idMaisonEdition INTO p_idMaisonEdition
    FROM maisonEdition
    WHERE nomMaisonEdition = in_nomMaisonEdition;
    
    SELECT idPromotion INTO p_idPromotion
    FROM promotion
    WHERE nomPromotion = in_nomPromotion;

    INSERT INTO livre (idLivre, nomLivre, auteurLivre, imageLivre, exemplaireLivre, prixLivre, idCategorie, idMaisonEdition, idPromotion)
    VALUES (null, in_nomLivre, in_auteurLivre, in_imageLivre, in_exemplaireLivre, in_prixLivre, p_idCategorie, p_idMaisonEdition, p_idPromotion);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pInsertOrUpdateLigneCommande` (IN `in_idCommande` INT, IN `in_idLivre` INT, IN `in_quantiteLigneCommande` INT)   BEGIN
    DECLARE p_idLigneCommande INT;
    DECLARE p_quantiteLigneCommande INT;

    SELECT lc.idLigneCommande, lc.quantiteLigneCommande
    INTO p_idLigneCommande, p_quantiteLigneCommande
    FROM ligneCommande lc
    INNER JOIN commande c ON lc.idCommande = c.idCommande
    WHERE lc.idLivre = in_idLivre
      AND c.idUser = (SELECT idUser FROM commande WHERE idCommande = in_idCommande LIMIT 1)
      AND c.statutCommande = 'en attente'
    LIMIT 1;

    IF p_idLigneCommande IS NOT NULL THEN
        UPDATE ligneCommande
        SET quantiteLigneCommande = quantiteLigneCommande + in_quantiteLigneCommande
        WHERE idLigneCommande = p_idLigneCommande;

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'TEXT 1';
    ELSE
        INSERT INTO ligneCommande
        VALUES (null, in_idCommande, in_idLivre, in_quantiteLigneCommande);

         SIGNAL SQLSTATE '45000'
         SET MESSAGE_TEXT = 'TEXT 2';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pOffrirLivre` (IN `in_idUser` INT, IN `in_chiffre` INT)   proc: BEGIN
    DECLARE p_idCommande INT;
    DECLARE p_idLivre INT;
    DECLARE p_abonne_valide BOOLEAN DEFAULT FALSE;

    SELECT COUNT(*) > 0 INTO p_abonne_valide
    FROM abonnement
    WHERE idUser = in_idUser AND dateFinAbonnement > CURDATE();

    IF NOT p_abonne_valide THEN
        LEAVE proc;
    END IF;

    IF in_chiffre = 5 THEN
        SELECT idCommande INTO p_idCommande
        FROM commande
        WHERE idUser = in_idUser AND statutCommande = 'en attente'
        ORDER BY dateCommande DESC
        LIMIT 1;

        IF p_idCommande IS NOT NULL THEN
            SELECT idLivre INTO p_idLivre
            FROM (
                SELECT 9 AS idLivre UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
            ) AS livres
            ORDER BY RAND()
            LIMIT 1;

            INSERT INTO ligneCommande
            VALUES (null, p_idCommande, p_idLivre, 1);

            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Un livre vous a été offert dans votre panier actuel !';
        END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `idAbonnement` int NOT NULL,
  `idUser` int NOT NULL,
  `dateDebutAbonnement` date NOT NULL,
  `dateFinAbonnement` date DEFAULT NULL,
  `pointAbonnement` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`idAbonnement`, `idUser`, `dateDebutAbonnement`, `dateFinAbonnement`, `pointAbonnement`) VALUES
(1, 2, '2025-01-01', '2025-12-31', 0),
(3, 23, '2025-01-25', '2025-04-25', 0),
(4, 15, '2025-01-26', '2025-02-28', 80),
(26, 37, '2025-04-25', '2025-05-10', 130),
(31, 37, '2025-05-07', '2025-05-10', -10),
(32, 37, '2025-05-07', '2025-05-10', -10),
(33, 37, '2025-05-07', '2025-05-10', -10),
(34, 37, '2025-05-07', '2025-05-10', -10),
(35, 37, '2025-05-07', '2025-05-10', -10),
(36, 37, '2025-05-07', '2025-05-10', -10),
(37, 37, '2025-05-07', '2025-05-10', -10),
(38, 37, '2025-05-07', '2025-05-10', -10),
(39, 37, '2025-05-07', '2025-05-10', -10),
(40, 37, '2025-05-07', '2025-05-10', -10),
(41, 37, '2025-05-07', '2025-05-10', -10),
(42, 37, '2025-05-07', '2025-05-10', -10),
(43, 37, '2025-05-07', '2025-05-10', -10),
(44, 37, '2025-05-07', '2025-05-10', -10),
(45, 37, '2025-05-07', '2025-05-10', -10),
(46, 37, '2025-05-07', '2025-05-10', -10),
(47, 37, '2025-05-07', '2025-05-10', -10),
(48, 37, '2025-05-08', '2025-05-10', -10),
(49, 37, '2025-05-09', '2025-05-10', -10),
(50, 37, '2025-05-10', '2025-05-10', 80),
(51, 37, '2025-05-10', '2025-05-10', 50),
(52, 37, '2025-05-10', '2025-05-10', 50),
(53, 37, '2025-05-10', '2025-05-10', 50),
(54, 37, '2025-05-10', '2025-05-10', 50),
(55, 37, '2025-05-10', '2025-05-10', 50),
(56, 37, '2025-05-10', '2025-05-10', 50),
(57, 37, '2025-05-10', '2025-05-10', 50),
(58, 37, '2025-05-10', '2025-05-10', 50),
(59, 37, '2025-05-10', '2025-06-10', 30),
(60, 37, '2025-05-10', '2025-06-10', 30);

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int NOT NULL,
  `idUser` int DEFAULT NULL,
  `niveauAdmin` enum('principal','junior','superadmin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `idUser`, `niveauAdmin`) VALUES
(1, 1, 'principal');

-- --------------------------------------------------------

--
-- Structure de la table `archiveCommande`
--

CREATE TABLE `archiveCommande` (
  `idCommande` int NOT NULL,
  `dateCommande` datetime DEFAULT NULL,
  `statutCommande` varchar(50) DEFAULT NULL,
  `dateLivraisonCommande` datetime DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  `date_archivage` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Archive des commandes';

--
-- Déchargement des données de la table `archiveCommande`
--

INSERT INTO `archiveCommande` (`idCommande`, `dateCommande`, `statutCommande`, `dateLivraisonCommande`, `idUser`, `date_archivage`) VALUES
(457, '2002-09-09 00:00:00', 'en attente', '2002-12-12 00:00:00', 40, '2025-04-19 20:10:36'),
(516, '2025-05-03 00:00:00', 'expédiée', '2025-05-10 00:00:00', 63, '2025-05-03 16:27:39'),
(517, '2025-05-03 00:00:00', 'expédiée', '2025-05-10 00:00:00', 68, '2025-05-03 17:29:44'),
(518, '2025-05-03 00:00:00', 'expédiée', '2025-05-10 00:00:00', 68, '2025-05-03 17:29:44'),
(527, '2025-05-10 00:00:00', 'expédiée', '2025-05-17 00:00:00', 64, '2025-05-10 00:43:45');

-- --------------------------------------------------------

--
-- Structure de la table `archivelignecommande`
--

CREATE TABLE `archivelignecommande` (
  `idLigneCommande` int NOT NULL,
  `idCommande` int DEFAULT NULL,
  `idLivre` int DEFAULT NULL,
  `quantiteLigneCommande` int DEFAULT NULL,
  `date_archivage` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Archive des lignes de commande';

--
-- Déchargement des données de la table `archivelignecommande`
--

INSERT INTO `archivelignecommande` (`idLigneCommande`, `idCommande`, `idLivre`, `quantiteLigneCommande`, `date_archivage`) VALUES
(724, 457, 3, 10, '2025-04-19 20:10:36'),
(725, 457, 5, 20, '2025-04-19 20:10:36'),
(726, 457, 4, 30, '2025-04-19 20:10:36'),
(891, 516, 17, 20, '2025-05-03 16:27:39'),
(892, 516, 45, 3, '2025-05-03 16:27:39'),
(893, 516, 49, 40, '2025-05-03 16:27:39'),
(894, 517, 2, 27, '2025-05-03 17:29:44'),
(895, 518, 13, 30, '2025-05-03 17:29:44'),
(913, 527, 45, 14, '2025-05-10 00:43:45'),
(914, 527, 48, 41, '2025-05-10 00:43:45');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `idAvis` int NOT NULL,
  `idLivre` int NOT NULL,
  `idUser` int NOT NULL,
  `commentaireAvis` text NOT NULL,
  `noteAvis` tinyint NOT NULL,
  `dateAvis` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `idLivre`, `idUser`, `commentaireAvis`, `noteAvis`, `dateAvis`) VALUES
(1, 1, 2, 'Excellent livre, très captivant !', 5, '2025-01-25'),
(2, 4, 23, 'Un peu long à démarrer, mais intéressant.', 3, '2025-01-25'),
(3, 1, 1, 'bien', 1, '2025-01-29'),
(7, 2, 15, 'df', 2, '2025-01-29'),
(8, 4, 15, 'azd', 4, '2025-01-29'),
(14, 6, 15, 'dfg', 1, '2025-01-29'),
(15, 2, 15, 'dfg', 1, '2025-01-29'),
(16, 2, 15, 'qsd', 3, '2025-01-29'),
(17, 6, 15, 'tibo', 4, '2025-01-29'),
(18, 8, 15, 'très bon livre', 5, '2025-01-29'),
(19, 3, 15, 'bof', 3, '2025-01-29'),
(20, 3, 15, 'bof', 3, '2025-01-29'),
(21, 3, 15, 'bof', 3, '2025-01-29'),
(22, 3, 15, 'bof', 3, '2025-01-29'),
(23, 3, 15, 'bof', 3, '2025-01-29'),
(24, 2, 15, 'surcôté', 3, '2025-01-29'),
(25, 2, 15, 'surcôté', 3, '2025-01-29'),
(26, 8, 15, 'masterclass', 5, '2025-02-02'),
(27, 8, 15, 'masterclass', 5, '2025-02-02'),
(28, 2, 15, '3 étoiles', 3, '2025-02-02'),
(29, 3, 15, 'test l\'', 4, '2025-02-02'),
(30, 2, 15, 'cube information', 4, '2025-02-02'),
(31, 3, 15, 'test \'', 5, '2025-02-03'),
(32, 45, 37, 'super', 4, '2025-05-01'),
(33, 10, 37, 'excellent', 1, '2025-05-01'),
(34, 10, 37, 'nul', 5, '2025-05-01'),
(35, 3, 37, 'test avis 16h07', 4, '2025-05-01'),
(36, 1, 37, 'test5', 4, '2025-05-03'),
(38, 2, 37, 'test7', 2, '2025-05-03'),
(39, 2, 37, 'top', 4, '2025-05-07'),
(40, 9, 37, 'cool', 3, '2025-05-07'),
(41, 13, 37, 'pestoyeusement bon', 2, '2025-05-10');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `idCategorie` int NOT NULL,
  `nomCategorie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `nomCategorie`) VALUES
(1, 'Roman'),
(2, 'Histoire'),
(3, 'Recueil de Poèmes'),
(4, 'Programmation');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCommande` int NOT NULL,
  `dateCommande` date DEFAULT NULL,
  `statutCommande` enum('en attente','expédiée','arrivée') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dateLivraisonCommande` date DEFAULT NULL,
  `idUser` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `dateCommande`, `statutCommande`, `dateLivraisonCommande`, `idUser`) VALUES
(351, '2025-01-29', 'arrivée', '2025-02-05', 2),
(352, '2025-01-29', 'arrivée', '2025-02-05', 2),
(353, '2025-01-29', 'arrivée', '2025-02-05', 2),
(354, '2025-01-29', 'arrivée', '2025-02-05', 2),
(355, '2025-01-29', 'arrivée', '2025-02-05', 2),
(356, '2025-01-29', 'arrivée', '2025-02-05', 2),
(357, '2025-01-29', 'arrivée', '2025-02-05', 2),
(358, '2025-01-29', 'arrivée', '2025-02-05', 2),
(359, '2025-01-29', 'arrivée', '2025-02-05', 2),
(360, '2025-01-29', 'arrivée', '2025-02-05', 2),
(361, '2025-01-29', 'arrivée', '2025-02-05', 2),
(362, '2025-01-29', 'arrivée', '2025-02-05', 2),
(363, '2025-01-29', 'arrivée', '2025-02-05', 2),
(364, '2025-01-29', 'arrivée', '2025-02-05', 15),
(365, '2025-01-29', 'arrivée', '2025-02-05', 15),
(366, '2025-01-29', 'arrivée', '2025-02-05', 15),
(367, '2025-01-29', 'arrivée', '2025-02-05', 15),
(368, '2025-01-29', 'arrivée', '2025-02-05', 15),
(369, '2025-01-29', 'arrivée', '2025-02-05', 15),
(370, '2025-01-29', 'arrivée', '2025-02-05', 15),
(371, '2025-01-29', 'arrivée', '2025-02-05', 15),
(372, '2025-01-29', 'arrivée', '2025-02-05', 2),
(373, '2025-01-29', 'arrivée', '2025-02-05', 2),
(374, '2025-01-29', 'arrivée', '2025-02-05', 2),
(375, '2025-01-29', 'arrivée', '2025-02-05', 15),
(376, '2025-01-29', 'arrivée', '2025-02-05', 15),
(377, '2025-01-29', 'arrivée', '2025-02-05', 15),
(378, '2025-01-29', 'arrivée', '2025-02-05', 15),
(379, '2025-01-29', 'arrivée', '2025-02-05', 15),
(380, '2025-01-29', 'arrivée', '2025-02-05', 15),
(381, '2025-01-29', 'arrivée', '2025-02-05', 15),
(382, '2025-01-29', 'arrivée', '2025-02-05', 15),
(383, '2025-01-29', 'arrivée', '2025-02-05', 24),
(384, '2025-01-29', 'arrivée', '2025-02-05', 24),
(385, '2025-01-29', 'arrivée', '2025-02-05', 24),
(386, '2025-01-29', 'arrivée', '2025-02-05', 24),
(387, '2025-01-29', 'arrivée', '2025-02-05', 24),
(388, '2025-01-29', 'arrivée', '2025-02-05', 24),
(389, '2025-01-29', 'arrivée', '2025-02-05', 24),
(390, '2025-01-29', 'arrivée', '2025-02-05', 24),
(391, '2025-01-29', 'arrivée', '2025-02-05', 24),
(392, '2025-01-29', 'arrivée', '2025-02-05', 24),
(393, '2025-01-29', 'arrivée', '2025-02-05', 24),
(395, '2025-01-29', 'arrivée', '2025-02-05', 24),
(396, '2025-01-29', 'arrivée', '2025-02-05', 24),
(397, '2025-01-29', 'arrivée', '2025-02-05', 24),
(398, '2025-01-29', 'arrivée', '2025-02-05', 24),
(399, '2025-01-29', 'arrivée', '2025-02-05', 24),
(400, '2025-01-29', 'arrivée', '2025-02-05', 24),
(401, '2025-01-29', 'arrivée', '2025-02-05', 24),
(402, '2025-01-29', 'arrivée', '2025-02-05', 24),
(403, '2025-01-29', 'arrivée', '2025-02-05', 24),
(404, '2025-01-29', 'arrivée', '2025-02-05', 24),
(405, '2025-01-29', 'arrivée', '2025-02-05', 24),
(406, '2025-01-30', 'arrivée', '2025-02-06', 2),
(407, '2025-01-30', 'arrivée', '2025-02-06', 2),
(408, '2025-01-30', 'arrivée', '2025-02-06', 2),
(409, '2025-01-30', 'arrivée', '2025-02-06', 15),
(410, '2025-01-30', 'arrivée', '2025-02-06', 15),
(411, '2025-01-30', 'arrivée', '2025-02-06', 15),
(412, '2025-01-30', 'arrivée', '2025-02-06', 15),
(413, '2025-01-30', 'arrivée', '2025-02-06', 15),
(414, '2025-01-30', 'arrivée', '2025-02-06', 15),
(415, '2025-01-30', 'arrivée', '2025-02-06', 15),
(417, '2025-02-02', 'arrivée', '2025-02-09', 15),
(418, '2025-02-02', 'arrivée', '2025-02-09', 15),
(419, '2025-02-02', 'arrivée', '2025-02-09', 15),
(420, '2025-02-02', 'arrivée', '2025-02-09', 15),
(421, '2025-02-02', 'arrivée', '2025-02-09', 15),
(422, '2025-02-02', 'arrivée', '2025-02-09', 15),
(423, '2025-02-02', 'arrivée', '2025-02-09', 15),
(424, '2025-02-02', 'arrivée', '2025-02-09', 15),
(425, '2025-02-02', 'arrivée', '2025-02-09', 24),
(426, '2025-02-02', 'arrivée', '2025-02-09', 24),
(427, '2025-02-02', 'arrivée', '2025-02-09', 2),
(428, '2025-02-02', 'arrivée', '2025-02-09', 15),
(429, '2025-02-02', 'arrivée', '2025-02-09', 15),
(430, '2025-02-02', 'arrivée', '2025-02-09', 15),
(431, '2025-02-02', 'arrivée', '2025-02-09', 15),
(432, '2025-02-02', 'arrivée', '2025-02-09', 15),
(433, '2025-02-02', 'arrivée', '2025-02-09', 15),
(434, '2025-02-02', 'arrivée', '2025-02-09', 15),
(435, '2025-02-02', 'arrivée', '2025-02-09', 15),
(436, '2025-02-02', 'arrivée', '2025-02-09', 15),
(437, '2025-02-02', 'arrivée', '2025-02-09', 15),
(438, '2025-02-02', 'arrivée', '2025-02-09', 15),
(439, '2025-02-02', 'arrivée', '2025-02-09', 15),
(440, '2025-02-02', 'arrivée', '2025-02-09', 15),
(441, '2025-02-02', 'arrivée', '2025-02-09', 15),
(442, '2025-02-02', 'arrivée', '2025-02-09', 15),
(443, '2025-02-02', 'arrivée', '2025-02-09', 15),
(444, '2025-02-02', 'arrivée', '2025-02-09', 15),
(445, '2025-02-02', 'arrivée', '2025-02-09', 15),
(446, '2025-02-02', 'arrivée', '2025-02-09', 15),
(447, '2025-02-02', 'arrivée', '2025-02-09', 15),
(448, '2025-02-02', 'arrivée', '2025-02-09', 15),
(449, '2025-02-02', 'arrivée', '2025-02-09', 15),
(450, '2025-02-02', 'arrivée', '2025-02-09', 15),
(452, '2025-02-03', 'arrivée', '2025-02-10', 15),
(453, '2025-02-03', 'arrivée', '2025-02-10', 15),
(454, '2025-02-03', 'arrivée', '2025-02-10', 15),
(455, '2025-02-03', 'arrivée', '2025-02-10', 15),
(462, '2025-04-24', 'arrivée', '2025-05-01', 37),
(463, '2025-04-25', 'en attente', '2025-05-02', 2),
(465, '2025-04-26', 'arrivée', '2025-05-03', 37),
(466, '2025-04-26', 'arrivée', '2025-05-03', 37),
(467, '2025-04-26', 'arrivée', '2025-05-03', 37),
(468, '2025-04-26', 'arrivée', '2025-05-03', 37),
(469, '2025-04-26', 'arrivée', '2025-05-03', 37),
(470, '2025-04-26', 'arrivée', '2025-05-03', 37),
(471, '2025-04-26', 'arrivée', '2025-05-03', 37),
(472, '2025-04-26', 'arrivée', '2025-05-03', 37),
(473, '2025-04-26', 'arrivée', '2025-05-03', 37),
(474, '2025-04-26', 'arrivée', '2025-05-03', 37),
(475, '2025-04-26', 'arrivée', '2025-05-03', 37),
(476, '2025-04-26', 'arrivée', '2025-05-03', 37),
(477, '2025-04-26', 'arrivée', '2025-05-03', 37),
(478, '2025-04-26', 'arrivée', '2025-05-03', 37),
(479, '2025-04-26', 'arrivée', '2025-05-03', 37),
(480, '2025-04-26', 'arrivée', '2025-05-03', 37),
(481, '2025-04-26', 'arrivée', '2025-05-03', 37),
(482, '2025-04-26', 'arrivée', '2025-05-03', 37),
(483, '2025-04-26', 'arrivée', '2025-05-03', 37),
(484, '2025-04-27', 'arrivée', '2025-05-04', 37),
(485, '2025-05-01', 'arrivée', '2025-05-08', 37),
(486, '2025-05-01', 'arrivée', '2025-05-08', 37),
(487, '2025-05-01', 'arrivée', '2025-05-08', 37),
(488, '2025-05-01', 'arrivée', '2025-05-08', 37),
(489, '2025-05-01', 'arrivée', '2025-05-08', 37),
(490, '2025-05-01', 'arrivée', '2025-05-08', 37),
(491, '2025-05-01', 'arrivée', '2025-05-08', 37),
(492, '2025-05-01', 'arrivée', '2025-05-08', 37),
(493, '2025-05-01', 'arrivée', '2025-05-08', 37),
(494, '2025-05-01', 'arrivée', '2025-05-08', 37),
(495, '2025-05-01', 'arrivée', '2025-05-08', 37),
(496, '2025-05-01', 'arrivée', '2025-05-08', 37),
(497, '2025-05-01', 'arrivée', '2025-05-08', 37),
(498, '2025-05-01', 'arrivée', '2025-05-08', 37),
(499, '2025-05-01', 'arrivée', '2025-05-08', 37),
(500, '2025-05-01', 'arrivée', '2025-05-08', 37),
(501, '2025-05-01', 'arrivée', '2025-05-08', 37),
(502, '2025-05-01', 'arrivée', '2025-05-08', 37),
(503, '2025-05-01', 'arrivée', '2025-05-08', 37),
(504, '2025-05-01', 'arrivée', '2025-05-08', 37),
(505, '2025-05-01', 'arrivée', '2025-05-08', 37),
(506, '2025-05-01', 'arrivée', '2025-05-08', 37),
(507, '2025-05-01', 'arrivée', '2025-05-08', 37),
(508, '2025-05-01', 'arrivée', '2025-05-08', 37),
(509, '2025-05-01', 'arrivée', '2025-05-08', 37),
(510, '2025-05-01', 'arrivée', '2025-05-08', 37),
(511, '2025-05-01', 'arrivée', '2025-05-08', 37),
(512, '2025-05-01', 'arrivée', '2025-05-08', 37),
(513, '2025-05-01', 'arrivée', '2025-05-08', 37),
(514, '2025-05-01', 'arrivée', '2025-05-08', 37),
(515, '2025-05-03', 'expédiée', '2025-05-10', 37),
(519, '2025-05-03', 'expédiée', '2025-05-10', 37),
(520, '2025-05-03', 'expédiée', '2025-05-10', 37),
(522, '2025-05-09', 'expédiée', '2025-05-16', 37),
(523, '2025-05-09', 'expédiée', '2025-05-16', 37),
(524, '2025-05-09', 'expédiée', '2025-05-16', 37),
(525, '2025-05-09', 'expédiée', '2025-05-16', 37),
(526, '2025-05-09', 'expédiée', '2025-05-16', 37),
(528, '2025-05-10', 'expédiée', '2025-05-17', 37),
(529, '2025-05-10', 'expédiée', '2025-05-17', 37),
(530, '2025-05-10', 'expédiée', '2025-05-17', 37),
(531, '2025-05-10', 'expédiée', '2025-05-17', 37),
(532, '2025-05-10', 'expédiée', '2025-05-17', 37),
(533, '2025-05-10', 'expédiée', '2025-05-17', 37);

--
-- Déclencheurs `commande`
--
DELIMITER $$
CREATE TRIGGER `tUpdateStockCommande` AFTER UPDATE ON `commande` FOR EACH ROW BEGIN
    IF OLD.statutCommande = 'en attente' AND NEW.statutCommande = 'expédiée' THEN
        UPDATE livre l
        JOIN ligneCommande lc ON l.idLivre = lc.idLivre
        SET l.exemplaireLivre = l.exemplaireLivre - lc.quantiteLigneCommande
        WHERE lc.idCommande = NEW.idCommande;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `idUser` int NOT NULL,
  `siretUser` bigint DEFAULT NULL,
  `raisonSocialeUser` varchar(255) DEFAULT NULL,
  `capitalSocialUser` float(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`idUser`, `siretUser`, `raisonSocialeUser`, `capitalSocialUser`) VALUES
(26, 123456789, 'Entreprise SARL', 10000.00),
(27, 123456789, 'Entreprise SARL', 10000.00),
(30, 123123123, 'yasser', 123123120.00),
(31, 1298371892, '123', NULL),
(32, 123, '123', 123124.00),
(33, 123123, '123123', 123123.00),
(34, 987987, '987987', 987987.00),
(59, 12312312312312, 'partent', 1000000.12),
(61, 12312312312312, 'azd', 1231.10),
(65, 13123123123, 'okppl', 123123120.00),
(70, 12312312312312, '00h45', 123123120.00);

-- --------------------------------------------------------

--
-- Structure de la table `ligneCommande`
--

CREATE TABLE `ligneCommande` (
  `idLigneCommande` int NOT NULL,
  `idCommande` int NOT NULL,
  `idLivre` int NOT NULL,
  `quantiteLigneCommande` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ligneCommande`
--

INSERT INTO `ligneCommande` (`idLigneCommande`, `idCommande`, `idLivre`, `quantiteLigneCommande`) VALUES
(620, 364, 2, 2),
(621, 365, 6, 1),
(622, 366, 2, 2),
(623, 367, 6, 1),
(624, 368, 2, 1),
(625, 369, 6, 1),
(626, 370, 2, 1),
(627, 371, 2, 1),
(628, 372, 6, 1),
(629, 373, 6, 1),
(630, 374, 6, 1),
(631, 375, 6, 1),
(632, 376, 2, 1),
(633, 377, 6, 1),
(634, 378, 2, 1),
(635, 379, 6, 1),
(636, 380, 2, 1),
(637, 381, 2, 10),
(638, 382, 2, 10),
(639, 383, 2, 10),
(640, 384, 2, 10),
(641, 385, 2, 5),
(642, 386, 6, 1),
(643, 387, 3, 10),
(644, 388, 3, 10),
(645, 389, 4, 10),
(646, 390, 2, 4),
(647, 391, 6, 1),
(648, 392, 2, 10),
(649, 393, 6, 1),
(651, 395, 2, 1),
(652, 396, 2, 1),
(653, 397, 2, 1),
(654, 398, 2, 1),
(655, 399, 6, 1),
(656, 400, 2, 1),
(657, 401, 2, 1),
(658, 402, 2, 1),
(659, 403, 6, 1),
(660, 404, 2, 1),
(661, 405, 6, 1),
(662, 406, 6, 1),
(663, 407, 6, 1),
(664, 408, 6, 1),
(665, 409, 3, 1),
(666, 409, 9, 1),
(667, 410, 6, 1),
(668, 411, 2, 1),
(669, 412, 3, 1),
(670, 413, 3, 1),
(671, 414, 3, 1),
(672, 415, 9, 1),
(674, 417, 2, 1),
(675, 418, 12, 1),
(676, 419, 2, 1),
(677, 420, 12, 1),
(678, 421, 2, 1),
(679, 422, 9, 1),
(680, 423, 3, 1),
(681, 424, 12, 1),
(682, 425, 2, 1),
(683, 426, 3, 1),
(684, 427, 10, 1),
(685, 428, 2, 1),
(686, 429, 11, 1),
(687, 430, 2, 1),
(688, 431, 2, 1),
(689, 432, 11, 1),
(690, 433, 2, 1),
(691, 434, 11, 1),
(692, 435, 2, 1),
(693, 436, 12, 1),
(694, 437, 2, 1),
(695, 438, 12, 1),
(696, 439, 2, 1),
(697, 440, 11, 1),
(698, 441, 2, 1),
(699, 442, 12, 1),
(700, 443, 2, 1),
(701, 444, 12, 1),
(702, 445, 2, 1),
(703, 446, 12, 1),
(704, 447, 2, 1),
(705, 448, 9, 1),
(706, 449, 2, 1),
(707, 450, 11, 1),
(712, 452, 3, 1),
(713, 452, 2, 2),
(714, 452, 14, 5),
(715, 453, 2, 5),
(716, 454, 2, 1),
(717, 454, 3, 1),
(718, 454, 13, 1),
(720, 455, 2, 1),
(728, 462, 3, 1),
(759, 462, 14, 1),
(768, 462, 19, 1),
(780, 462, 2, 1),
(781, 462, 2, 1),
(782, 462, 2, 1),
(783, 462, 2, 1),
(785, 462, 2, 1),
(786, 462, 13, 31),
(788, 463, 2, 1),
(790, 463, 2, 1),
(791, 463, 2, 31),
(792, 462, 13, 80),
(795, 465, 45, 1),
(797, 465, 3, 10),
(798, 466, 2, 1),
(799, 466, 3, 12),
(800, 467, 11, 1),
(801, 468, 9, 1),
(802, 469, 2, 1),
(803, 469, 12, 1),
(804, 470, 2, 1),
(805, 470, 11, 1),
(806, 471, 2, 1),
(807, 471, 11, 1),
(808, 472, 2, 1),
(809, 472, 12, 1),
(810, 473, 2, 1),
(811, 473, 11, 1),
(812, 474, 2, 5),
(813, 474, 9, 1),
(814, 475, 3, 2),
(815, 475, 12, 1),
(816, 476, 3, 12),
(817, 477, 2, 11),
(818, 477, 10, 1),
(819, 478, 2, 1),
(820, 478, 11, 1),
(821, 479, 2, 1),
(822, 479, 12, 1),
(823, 480, 2, 1),
(824, 480, 9, 1),
(825, 481, 2, 1),
(826, 481, 10, 1),
(827, 482, 2, 1),
(828, 482, 10, 1),
(829, 483, 2, 1),
(830, 483, 12, 1),
(831, 484, 2, 1),
(832, 484, 10, 1),
(834, 485, 2, 84),
(835, 485, 3, 2),
(836, 485, 13, 2),
(837, 485, 14, 1),
(838, 485, 9, 1),
(839, 486, 2, 1),
(840, 486, 9, 1),
(841, 487, 2, 1),
(842, 487, 9, 1),
(843, 488, 2, 4),
(844, 488, 9, 1),
(845, 489, 2, 4),
(846, 489, 3, 14),
(847, 489, 10, 1),
(849, 490, 2, 90),
(850, 490, 12, 1),
(851, 491, 2, 1),
(852, 491, 9, 1),
(853, 492, 2, 1),
(854, 492, 12, 1),
(855, 493, 2, 1),
(856, 493, 1, 5),
(857, 493, 10, 1),
(858, 494, 2, 1),
(859, 494, 9, 1),
(860, 495, 2, 1),
(861, 495, 11, 1),
(862, 496, 2, 1),
(863, 496, 9, 1),
(864, 497, 2, 1),
(865, 497, 11, 1),
(866, 498, 2, 1),
(867, 498, 9, 1),
(868, 499, 2, 1),
(869, 499, 10, 1),
(870, 500, 2, 1),
(871, 500, 12, 1),
(872, 501, 2, 1),
(873, 501, 12, 1),
(874, 502, 2, 1),
(875, 503, 2, 1),
(876, 504, 2, 1),
(877, 505, 2, 1),
(878, 505, 3, 3),
(879, 505, 13, 4),
(880, 506, 2, 1),
(881, 507, 2, 1),
(882, 508, 2, 1),
(883, 509, 2, 1),
(884, 510, 2, 1),
(885, 511, 2, 1),
(886, 512, 3, 1),
(887, 513, 2, 1),
(888, 514, 3, 1),
(889, 515, 2, 1),
(890, 515, 3, 1),
(896, 519, 2, 1),
(897, 519, 12, 1),
(898, 520, 2, 1),
(899, 520, 9, 1),
(902, 522, 2, 1),
(907, 522, 13, 3),
(908, 522, 1, 2),
(909, 523, 1, 1),
(910, 524, 1, 1),
(911, 525, 2, 2),
(912, 526, 2, 1),
(915, 528, 1, 2),
(916, 529, 1, 1),
(917, 530, 1, 1),
(918, 531, 1, 1),
(919, 532, 1, 1),
(920, 533, 1, 2),
(921, 533, 11, 1);

--
-- Déclencheurs `ligneCommande`
--
DELIMITER $$
CREATE TRIGGER `tStockLivre` BEFORE UPDATE ON `ligneCommande` FOR EACH ROW BEGIN
    DECLARE t_totalQuantite INT;
    DECLARE t_exemplaireLivre INT;
    DECLARE t_idUser INT;

    SELECT idUser
    INTO t_idUser
    FROM commande
    WHERE idCommande = NEW.idCommande;

    SELECT SUM(lc.quantiteLigneCommande)
    INTO t_totalQuantite
    FROM ligneCommande lc
    INNER JOIN commande c ON lc.idCommande = c.idCommande
    WHERE lc.idLivre = NEW.idLivre
    AND c.idUser = t_idUser
    and c.statutCommande = 'en attente';

    SET t_totalQuantite = IFNULL(t_totalQuantite, 0) - OLD.quantiteLigneCommande + NEW.quantiteLigneCommande;

    SELECT exemplaireLivre
    INTO t_exemplaireLivre
    FROM livre
    WHERE idLivre = NEW.idLivre;

    IF t_totalQuantite > t_exemplaireLivre THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La quantité totale dépasse le nombre d'exemplaires disponibles pour ce livre.';
    END IF;
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
  `auteurLivre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `imageLivre` varchar(50) NOT NULL,
  `exemplaireLivre` int DEFAULT NULL,
  `prixLivre` float(10,2) NOT NULL,
  `idCategorie` int DEFAULT NULL,
  `idMaisonEdition` int DEFAULT NULL,
  `idPromotion` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`idLivre`, `nomLivre`, `auteurLivre`, `imageLivre`, `exemplaireLivre`, `prixLivre`, `idCategorie`, `idMaisonEdition`, `idPromotion`) VALUES
(1, 'Alcools', 'Apollinaire', 'alcools.png', 82, 12.50, 1, 1, 7),
(2, 'Crime et Chatiment', 'Dostoïevski', 'crime_et_chatiment.png', 44, 15.00, 2, 2, NULL),
(3, 'L`Etranger', 'Camus', 'l_etranger.png', 79, 10.00, 1, 3, NULL),
(4, 'L`Odyssée', 'Homère', 'l_odyssee.png', 89, 13.50, 2, 4, NULL),
(5, 'Les Fleurs du Mal', 'Baudelaire', 'les_fleurs_du_mal.png', 100, 14.00, 3, 5, 3),
(6, 'PHP et MySQL pour les nuls', 'Valade', 'php_et_mysql_pour_les_nuls.png', 79, 22.00, 4, 6, NULL),
(7, 'Programmer en Java', 'Delannoy', 'programmer_en_java.png', 100, 25.00, 4, 7, NULL),
(8, 'SPQR', 'Beard', 'spqr.png', 99, 18.00, 2, 8, 4),
(9, 'À la recherche du temps perdu', 'Proust', 'a_la_recherche_du_temps_perdu.png', 92, 0.00, 1, 1, 5),
(10, 'Les Misérables', 'Hugo', 'les_miserables_I.png', 96, 0.00, 1, 2, NULL),
(11, '1984', 'Orwell', '1984.png', 92, 0.00, 1, 3, NULL),
(12, 'L`Art d\'aimer', 'Ovide', 'l_art_d_aimer', 87, 0.00, 1, 4, NULL),
(13, 'La Peste', 'Camus', 'la_peste.png', 62, 15.99, 1, 1, 1),
(14, 'Les Mémoires d\'Hadrien', 'Yourcenar', 'les_memoires_d_hadrien.png', 99, 12.99, 1, 1, 7),
(15, 'La Condition humaine', 'Malraux', 'la_condition_humaine.png', 100, 14.99, 2, 1, NULL),
(16, 'Le Comte de Monte-Cristo', 'Dumas', 'le_comte_de_monte_cristo.png', 100, 9.99, 1, 2, NULL),
(17, 'Orgueil et Préjugés', 'Austen', 'orgueil_et_prejuges.png', 80, 8.99, 1, 2, NULL),
(18, 'Shining', 'King', 'shining.png', 100, 10.99, 1, 2, NULL),
(19, 'Bel-Ami', 'Maupassant', 'bel_ami.png', 99, 11.99, 1, 3, 10),
(20, 'Fahrenheit 451', 'Bradbury', 'fahrenheit_451.png', 100, 9.99, 1, 3, NULL),
(21, 'La Nuit des temps', 'Barjavel', 'la_nuit_des_temps.png', 100, 12.99, 1, 3, 11),
(22, 'L`Énéide', 'Virgile', 'l_eneide.png', 100, 19.99, 3, 4, NULL),
(23, 'Les Pensées', 'Aurèle', 'les_pensees.png', 100, 18.99, 3, 4, 12),
(24, 'Les Métamorphoses', 'Ovide', 'les_metamorphoses.png', 100, 20.99, 3, 4, NULL),
(25, 'Le Petit Livre des citations latines', 'Delamaire', 'le_petit_livre_des_citations_latines.png', 100, 7.99, 3, 6, 13),
(43, 'Le Petit Livre des grandes coïncidences', 'Chiflet', 'le_petit_livre_des_grandes_coincidences.png', 100, 7.99, 3, 6, NULL),
(44, 'Le Petit Livre des gros mensonges', 'Chiflet', 'le_petit_livre_des_gros_mensonges.png', 100, 7.99, 3, 6, NULL),
(45, 'L`Art de la guerre', 'Sun', 'l_art_de_la_guerre.png', 83, 12.99, 2, 7, NULL),
(46, 'Apprendre à dessiner', 'Edwards', 'apprendre_a_dessiner.png', 100, 14.99, 4, 7, NULL),
(48, 'Les Templiers', 'Demurger', 'les_templiers.png', 59, 18.99, 2, 8, NULL),
(49, 'La Seconde Guerre mondiale', 'Beevor', 'la_seconde_guerre_mondiale.png', 60, 19.99, 2, 8, NULL),
(50, 'Napoléon : Une ambition française', 'Tulard', 'napoleon_une_ambition_francaise.png', 100, 20.99, 2, 8, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `maisonEdition`
--

CREATE TABLE `maisonEdition` (
  `idMaisonEdition` int NOT NULL,
  `nomMaisonEdition` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `maisonEdition`
--

INSERT INTO `maisonEdition` (`idMaisonEdition`, `nomMaisonEdition`) VALUES
(1, 'Gallimard'),
(2, 'Livre de Poche'),
(3, 'Folio'),
(4, 'Les Belles Lettres'),
(5, 'Le Livre de Poche'),
(6, 'First Interactive'),
(7, 'Eyrolles'),
(8, 'Perrin');

-- --------------------------------------------------------

--
-- Structure de la table `particulier`
--

CREATE TABLE `particulier` (
  `idUser` int NOT NULL,
  `nomUser` varchar(255) DEFAULT NULL,
  `prenomUser` varchar(255) DEFAULT NULL,
  `dateNaissanceUser` date DEFAULT NULL,
  `sexeUser` enum('M','F') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `particulier`
--

INSERT INTO `particulier` (`idUser`, `nomUser`, `prenomUser`, `dateNaissanceUser`, `sexeUser`) VALUES
(28, 'Arch', 'Michael', '2001-12-12', 'M'),
(29, 'ryles', 'ryles', '2022-12-12', 'M'),
(35, 'yasser', 'yasser', '2010-12-21', 'M'),
(36, 'yasser', 'yasser', '2010-12-21', 'M'),
(37, 'parto', 'parto', '2004-12-12', 'M'),
(38, 'uy', 'uy', '2003-03-12', 'M'),
(42, 'partauth', 'partauth', '2005-02-20', 'M'),
(46, '16h48', '16h48', '2025-05-20', 'F'),
(54, 'intpart', 'intpart', '2012-12-12', 'M'),
(60, '17h35', '17h35', '2005-12-16', 'F'),
(62, 'bil', 'orebil', '2018-09-12', 'M'),
(69, '00h44', '00h44', '2005-12-12', 'M');

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `idPromotion` int NOT NULL,
  `nomPromotion` varchar(50) NOT NULL,
  `dateDebutPromotion` date NOT NULL,
  `dateFinPromotion` date NOT NULL,
  `reductionPromotion` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`idPromotion`, `nomPromotion`, `dateDebutPromotion`, `dateFinPromotion`, `reductionPromotion`) VALUES
(1, 'Promo Été 2023', '2023-07-02', '2023-07-31', 10),
(2, 'Promo Hiver 2023', '2023-12-01', '2023-12-31', 15),
(3, 'Promo Printemps 2024', '2024-04-01', '2024-04-30', 20),
(4, 'Promo Automne 2024', '2024-10-01', '2024-10-31', 25),
(5, 'Promo Spéciale Été', '2023-08-01', '2023-08-31', 30),
(6, 'Promo Spéciale Hiver', '2023-11-01', '2023-11-30', 35),
(7, 'Promo Spéciale Printemps', '2024-05-01', '2024-05-31', 40),
(8, 'Promo Spéciale Automne', '2024-09-01', '2024-09-30', 45),
(9, 'Promo Été Flash', '2023-07-15', '2023-07-20', 50),
(10, 'Promo Hiver Flash', '2023-12-15', '2023-12-20', 55),
(11, 'Promo Printemps Flash', '2024-04-15', '2024-04-20', 60),
(12, 'Promo Automne Flash', '2024-10-15', '2024-10-21', 65),
(13, 'Promo Été Mega', '2023-07-01', '2023-07-10', 10),
(14, 'Promo Hiver Mega', '2023-12-01', '2023-12-10', 15),
(15, 'Promo Printemps Mega', '2024-04-01', '2024-04-10', 20),
(16, 'Promo Automne Mega', '2024-10-01', '2024-10-10', 25),
(17, 'Promo Été Super', '2023-07-20', '2023-07-31', 30),
(18, 'Promo Hiver Super', '2023-12-20', '2023-12-31', 35),
(19, 'Promo Printemps Super', '2024-04-20', '2024-04-30', 40),
(20, 'Promo Automne Super', '2024-10-20', '2024-10-31', 45),
(85, '15h07', '2025-05-09', '2025-09-12', 14),
(86, '15h08', '2025-05-09', '2025-05-12', 12),
(87, '15h09', '2025-12-09', '2025-12-12', 1),
(88, '15h10', '2025-02-20', '2025-02-21', 31);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `emailUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mdpUser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `adresseUser` varchar(50) NOT NULL,
  `roleUser` enum('admin','particulier','entreprise') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `emailUser`, `mdpUser`, `adresseUser`, `roleUser`) VALUES
(1, 'ryles@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123 Rue des Lilas', 'admin'),
(2, 'jean@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '45 Avenue de la République', 'particulier'),
(12, 'm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '78 Boulevard Haussmann', 'particulier'),
(13, 'klza', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '9 Place de la Liberté', 'particulier'),
(14, 'poi', '123', '56 Rue Victor Hugo', 'particulier'),
(15, 'i', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '34 Avenue Montaigne', 'particulier'),
(23, 'chouaki@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '101 Rue Lafayette', 'particulier'),
(24, 'bo@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '202 Avenue des Champs-Élysées', 'particulier'),
(26, 'entreprise@gmail.com', 'motdepasse', '404 Rue de Rivoli', 'entreprise'),
(27, 'entreprise@gmail.com', 'motdepasse', '505 Quai de la Tournelle', 'entreprise'),
(28, 'michael@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '89 Impasse des Cerisiers', 'particulier'),
(29, 'ryles', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '102 Rue du Moulin', 'particulier'),
(30, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '76 Avenue des Rosiers', 'particulier'),
(31, 'entreprise@gmail.com', '123', '58 Chemin des Vignes', 'entreprise'),
(32, 'test@gmail.com', '123', '21 Boulevard de la Liberté', 'particulier'),
(33, 'yass@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '34 Rue des Érables', 'particulier'),
(34, '987987@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '47 Allée des Chênes', 'particulier'),
(35, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '15 Route du Soleil', 'particulier'),
(36, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '63 Place du Marché', 'particulier'),
(37, 'parto@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '17 rue des moulins', 'particulier'),
(38, 'uy@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uy', 'particulier'),
(42, 'partauth@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'partauth', 'particulier'),
(43, 'entauth@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'entauth', 'entreprise'),
(45, '16h47@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '16h47', 'entreprise'),
(46, '16h48@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '16h48', 'particulier'),
(54, 'intpart@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'intpart', 'particulier'),
(59, 'partent@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'partent', 'entreprise'),
(60, '17h35@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '17h35', 'particulier'),
(61, 'azd@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'azd', 'entreprise'),
(62, 'azd@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'azd', 'particulier'),
(65, 'aiozjdazij@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'okppl', 'entreprise'),
(69, '00h44@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '00h44', 'particulier'),
(70, '00h45@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '00h45', 'entreprise');

--
-- Déclencheurs `user`
--
DELIMITER $$
CREATE TRIGGER `tInsertArchive` BEFORE DELETE ON `user` FOR EACH ROW BEGIN
    INSERT INTO archiveCommande
    SELECT c.idCommande, c.dateCommande, c.statutCommande, c.dateLivraisonCommande, c.idUser, NOW()
    FROM commande c
    WHERE c.idUser = OLD.idUser;

    INSERT INTO archiveLigneCommande
    SELECT lc.idLigneCommande, lc.idCommande, lc.idLivre, lc.quantiteLigneCommande, NOW()
    FROM ligneCommande lc
    JOIN commande c ON lc.idCommande = c.idCommande
    WHERE c.idUser = OLD.idUser;

    DELETE FROM ligneCommande
    WHERE idCommande IN (SELECT idCommande FROM commande WHERE idUser = OLD.idUser);

    DELETE FROM commande
    WHERE idUser = OLD.idUser;
END
$$
DELIMITER ;

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
`idLivre` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`exemplaireLivre` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vlivresmieuxnotes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vlivresmieuxnotes` (
`idLivre` int
,`nomLivre` varchar(50)
,`noteMoyenne` decimal(6,2)
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
-- Doublure de structure pour la vue `vmeilleursavis`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vmeilleursavis` (
`idLivre` int
,`nomLivre` varchar(50)
,`moyenneNote` decimal(7,4)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vnblivreacheteuser`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vnblivreacheteuser` (
`emailUser` varchar(50)
,`nbLivreAchete` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vtotalcommandeenattente`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotalcommandeenattente` (
`idUser` int
,`totalCommande` double(19,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vtotalcommandeexpediee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotalcommandeexpediee` (
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
-- Doublure de structure pour la vue `vtotallivreenattente`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotallivreenattente` (
`idLivre` int
,`idCommande` int
,`idLigneCommande` int
,`idUser` int
,`nomLivre` varchar(50)
,`prixLivre` float(10,2)
,`quantiteLigneCommande` int
,`totalLivre` double(22,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vtotallivreexpediee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vtotallivreexpediee` (
`idCommande` int
,`idUser` int
,`idLivre` int
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
-- Structure de la vue `vlivresmieuxnotes`
--
DROP TABLE IF EXISTS `vlivresmieuxnotes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vlivresmieuxnotes`  AS SELECT `a`.`idLivre` AS `idLivre`, max(`l`.`nomLivre`) AS `nomLivre`, round(avg(`a`.`noteAvis`),2) AS `noteMoyenne` FROM (`avis` `a` join `livre` `l` on((`a`.`idLivre` = `l`.`idLivre`))) GROUP BY `a`.`idLivre` ORDER BY `noteMoyenne` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vmeilleuresventes`
--
DROP TABLE IF EXISTS `vmeilleuresventes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vmeilleuresventes`  AS SELECT `l`.`idLivre` AS `idLivre`, `l`.`nomLivre` AS `nomLivre`, sum(`li`.`quantiteLigneCommande`) AS `totalVendu` FROM (`lignecommande` `li` join `livre` `l` on((`li`.`idLivre` = `l`.`idLivre`))) GROUP BY `l`.`idLivre`, `l`.`nomLivre` ORDER BY `totalVendu` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vmeilleursavis`
--
DROP TABLE IF EXISTS `vmeilleursavis`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vmeilleursavis`  AS SELECT `l`.`idLivre` AS `idLivre`, `l`.`nomLivre` AS `nomLivre`, avg(`a`.`noteAvis`) AS `moyenneNote` FROM (`avis` `a` join `livre` `l` on((`a`.`idLivre` = `l`.`idLivre`))) GROUP BY `l`.`idLivre`, `l`.`nomLivre` ORDER BY `moyenneNote` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vnblivreacheteuser`
--
DROP TABLE IF EXISTS `vnblivreacheteuser`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vnblivreacheteuser`  AS SELECT `u`.`emailUser` AS `emailUser`, sum(`l`.`quantiteLigneCommande`) AS `nbLivreAchete` FROM ((`lignecommande` `l` join `commande` `c` on((`l`.`idCommande` = `c`.`idCommande`))) join `user` `u` on((`c`.`idUser` = `u`.`idUser`))) WHERE (`c`.`statutCommande` = 'expédiée') GROUP BY `u`.`emailUser` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotalcommandeenattente`
--
DROP TABLE IF EXISTS `vtotalcommandeenattente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotalcommandeenattente`  AS SELECT `c`.`idUser` AS `idUser`, sum((`l`.`prixLivre` * `li`.`quantiteLigneCommande`)) AS `totalCommande` FROM ((`commande` `c` join `lignecommande` `li` on((`c`.`idCommande` = `li`.`idCommande`))) join `livre` `l` on((`li`.`idLivre` = `l`.`idLivre`))) WHERE (`c`.`statutCommande` = 'en attente') GROUP BY `c`.`idUser` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotalcommandeexpediee`
--
DROP TABLE IF EXISTS `vtotalcommandeexpediee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotalcommandeexpediee`  AS SELECT `c`.`idUser` AS `idUser`, sum((`l`.`prixLivre` * `li`.`quantiteLigneCommande`)) AS `totalCommande` FROM ((`commande` `c` join `lignecommande` `li` on((`c`.`idCommande` = `li`.`idCommande`))) join `livre` `l` on((`li`.`idLivre` = `l`.`idLivre`))) WHERE (`c`.`statutCommande` = 'expédiée') GROUP BY `c`.`idUser` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotallivre`
--
DROP TABLE IF EXISTS `vtotallivre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotallivre`  AS SELECT `li`.`idCommande` AS `idCommande`, `c`.`idUser` AS `idUser`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `li`.`quantiteLigneCommande` AS `quantiteLigneCommande`, (`l`.`prixLivre` * `li`.`quantiteLigneCommande`) AS `totalLivre` FROM ((`livre` `l` join `lignecommande` `li` on((`l`.`idLivre` = `li`.`idLivre`))) join `commande` `c` on((`c`.`idCommande` = `li`.`idCommande`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotallivreenattente`
--
DROP TABLE IF EXISTS `vtotallivreenattente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotallivreenattente`  AS SELECT `li`.`idLivre` AS `idLivre`, `li`.`idCommande` AS `idCommande`, `li`.`idLigneCommande` AS `idLigneCommande`, `c`.`idUser` AS `idUser`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `li`.`quantiteLigneCommande` AS `quantiteLigneCommande`, (`l`.`prixLivre` * `li`.`quantiteLigneCommande`) AS `totalLivre` FROM ((`livre` `l` join `lignecommande` `li` on((`l`.`idLivre` = `li`.`idLivre`))) join `commande` `c` on((`c`.`idCommande` = `li`.`idCommande`))) WHERE (`c`.`statutCommande` = 'en attente') ;

-- --------------------------------------------------------

--
-- Structure de la vue `vtotallivreexpediee`
--
DROP TABLE IF EXISTS `vtotallivreexpediee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotallivreexpediee`  AS SELECT `li`.`idCommande` AS `idCommande`, `c`.`idUser` AS `idUser`, `l`.`idLivre` AS `idLivre`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `li`.`quantiteLigneCommande` AS `quantiteLigneCommande`, (`l`.`prixLivre` * `li`.`quantiteLigneCommande`) AS `totalLivre` FROM ((`livre` `l` join `lignecommande` `li` on((`l`.`idLivre` = `li`.`idLivre`))) join `commande` `c` on((`c`.`idCommande` = `li`.`idCommande`))) WHERE (`c`.`statutCommande` = 'expédiée') ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`idAbonnement`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `archiveCommande`
--
ALTER TABLE `archiveCommande`
  ADD PRIMARY KEY (`idCommande`,`date_archivage`),
  ADD KEY `idx_user` (`idUser`),
  ADD KEY `idx_date_archivage` (`date_archivage`);

--
-- Index pour la table `archivelignecommande`
--
ALTER TABLE `archivelignecommande`
  ADD PRIMARY KEY (`idLigneCommande`,`date_archivage`),
  ADD KEY `idx_commande` (`idCommande`),
  ADD KEY `idx_date_archivage` (`date_archivage`),
  ADD KEY `fk_idLivre_archivelignecommande` (`idLivre`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`idAvis`),
  ADD KEY `idLivre` (`idLivre`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `fk_c` (`idUser`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`idUser`);

--
-- Index pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  ADD PRIMARY KEY (`idLigneCommande`),
  ADD KEY `idCommande` (`idCommande`),
  ADD KEY `idLivre` (`idLivre`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`idLivre`),
  ADD KEY `idCategorie` (`idCategorie`),
  ADD KEY `idMaisonEdition` (`idMaisonEdition`),
  ADD KEY `fk_idPromotion` (`idPromotion`);

--
-- Index pour la table `maisonEdition`
--
ALTER TABLE `maisonEdition`
  ADD PRIMARY KEY (`idMaisonEdition`);

--
-- Index pour la table `particulier`
--
ALTER TABLE `particulier`
  ADD PRIMARY KEY (`idUser`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`idPromotion`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `idAbonnement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `idAvis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=534;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  MODIFY `idLigneCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=922;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `idLivre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `maisonEdition`
--
ALTER TABLE `maisonEdition`
  MODIFY `idMaisonEdition` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `particulier`
--
ALTER TABLE `particulier`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `idPromotion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `archivelignecommande`
--
ALTER TABLE `archivelignecommande`
  ADD CONSTRAINT `fk_idLivre_archivelignecommande` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`);

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_c` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD CONSTRAINT `entreprise_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  ADD CONSTRAINT `lignecommande_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignecommande_ibfk_2` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`) ON DELETE CASCADE;

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `fk_idPromotion` FOREIGN KEY (`idPromotion`) REFERENCES `promotion` (`idPromotion`) ON DELETE SET NULL,
  ADD CONSTRAINT `livre_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`idCategorie`) ON DELETE SET NULL,
  ADD CONSTRAINT `livre_ibfk_2` FOREIGN KEY (`idMaisonEdition`) REFERENCES `maisonEdition` (`idMaisonEdition`) ON DELETE SET NULL;

--
-- Contraintes pour la table `particulier`
--
ALTER TABLE `particulier`
  ADD CONSTRAINT `particulier_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

DELIMITER $$
--
-- Évènements
--
CREATE DEFINER=`root`@`localhost` EVENT `eUpdateStatutCommande` ON SCHEDULE EVERY 1 DAY STARTS '2025-05-04 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE commande
    SET statutCommande = 'arrivée'
    WHERE statutCommande = 'expédiée'
    AND dateLivraisonCommande < CURDATE();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
