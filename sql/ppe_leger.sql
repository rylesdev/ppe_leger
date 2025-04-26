-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : sam. 26 avr. 2025 à 23:09
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `pInsertLivre` (IN `p_nomLivre` VARCHAR(255), IN `p_auteurLivre` VARCHAR(255), IN `p_imageLivre` VARCHAR(255), IN `p_exemplaireLivre` INT, IN `p_prixLivre` DECIMAL(10,2), IN `p_nomCategorie` VARCHAR(255), IN `p_nomMaisonEdition` VARCHAR(255))   BEGIN
    DECLARE v_idCategorie INT;
    DECLARE v_idMaisonEdition INT;
    SELECT idCategorie INTO v_idCategorie
    FROM categorie
    WHERE nomCategorie = p_nomCategorie;
    SELECT idMaisonEdition INTO v_idMaisonEdition
    FROM maisonEdition
    WHERE nomMaisonEdition = p_nomMaisonEdition;
    INSERT INTO livre (nomLivre, auteurLivre, imageLivre, exemplaireLivre, prixLivre, idCategorie, idMaisonEdition)
    VALUES (p_nomLivre, p_auteurLivre, p_imageLivre, p_exemplaireLivre, p_prixLivre, v_idCategorie, v_idMaisonEdition);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pInsertOrUpdatePromotion` (IN `p_nomLivre` VARCHAR(255), IN `p_prixPromotion` DECIMAL(10,2), IN `p_dateFinPromotion` DATE)   BEGIN
    DECLARE v_idLivre INT;
    SELECT idLivre INTO v_idLivre FROM livre WHERE nomLivre = p_nomLivre LIMIT 1;
    IF v_idLivre IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le livre spécifié n\'existe pas.';
    END IF;
    IF EXISTS (SELECT 1 FROM promotion WHERE idLivre = v_idLivre) THEN
        UPDATE promotion
        SET prixPromotion = p_prixPromotion
        WHERE idLivre = v_idLivre;
    ELSE
        INSERT INTO promotion (idPromotion, idLivre, dateDebutPromotion, dateFinPromotion, prixPromotion)
        VALUES (null, v_idLivre, curdate(), p_dateFinPromotion, p_prixPromotion);
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `pQuantiteLigneCommande` (IN `in_idCommande` INT, IN `in_idLivre` INT, IN `in_quantiteLigneCommande` INT)   BEGIN
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
    ELSE
        INSERT INTO ligneCommande (idCommande, idLivre, quantiteLigneCommande)
        VALUES (in_idCommande, in_idLivre, in_quantiteLigneCommande);
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
(26, 37, '2025-04-25', '2025-05-25', 730);

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
(457, '2002-09-09 00:00:00', 'en attente', '2002-12-12 00:00:00', 40, '2025-04-19 20:10:36');

-- --------------------------------------------------------

--
-- Structure de la table `archiveLigneCommande`
--

CREATE TABLE `archiveLigneCommande` (
  `idLigneCommande` int NOT NULL,
  `idCommande` int DEFAULT NULL,
  `idLivre` int DEFAULT NULL,
  `quantiteLigneCommande` int DEFAULT NULL,
  `date_archivage` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Archive des lignes de commande';

--
-- Déchargement des données de la table `archiveLigneCommande`
--

INSERT INTO `archiveLigneCommande` (`idLigneCommande`, `idCommande`, `idLivre`, `quantiteLigneCommande`, `date_archivage`) VALUES
(724, 457, 3, 10, '2025-04-19 20:10:36'),
(725, 457, 5, 20, '2025-04-19 20:10:36'),
(726, 457, 4, 30, '2025-04-19 20:10:36');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `idAvis` int NOT NULL,
  `idLivre` int NOT NULL,
  `nomLivre` varchar(50) NOT NULL,
  `idUser` int NOT NULL,
  `commentaireAvis` text NOT NULL,
  `noteAvis` tinyint NOT NULL,
  `dateAvis` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `idLivre`, `nomLivre`, `idUser`, `commentaireAvis`, `noteAvis`, `dateAvis`) VALUES
(1, 1, '', 2, 'Excellent livre, très captivant !', 5, '2025-01-25'),
(2, 4, '', 23, 'Un peu long à démarrer, mais intéressant.', 3, '2025-01-25'),
(3, 1, '', 1, 'bien', 1, '2025-01-29'),
(7, 2, '', 15, 'df', 2, '2025-01-29'),
(8, 4, '', 15, 'azd', 4, '2025-01-29'),
(14, 6, '', 15, 'dfg', 1, '2025-01-29'),
(15, 2, '', 15, 'dfg', 1, '2025-01-29'),
(16, 2, '', 15, 'qsd', 3, '2025-01-29'),
(17, 6, '', 15, 'tibo', 4, '2025-01-29'),
(18, 8, '', 15, 'très bon livre', 5, '2025-01-29'),
(19, 3, 'L', 15, 'bof', 3, '2025-01-29'),
(20, 3, 'L', 15, 'bof', 3, '2025-01-29'),
(21, 3, 'L', 15, 'bof', 3, '2025-01-29'),
(22, 3, 'L', 15, 'bof', 3, '2025-01-29'),
(23, 3, 'L', 15, 'bof', 3, '2025-01-29'),
(24, 2, 'Crime et Chatiment', 15, 'surcôté', 3, '2025-01-29'),
(25, 2, 'Crime et Chatiment', 15, 'surcôté', 3, '2025-01-29'),
(26, 8, 'SPQR', 15, 'masterclass', 5, '2025-02-02'),
(27, 8, 'SPQR', 15, 'masterclass', 5, '2025-02-02'),
(28, 2, 'Crime et Chatiment', 15, '3 étoiles', 3, '2025-02-02'),
(29, 3, 'L', 15, 'test l\'', 4, '2025-02-02'),
(30, 2, 'Crime et Chatiment', 15, 'cube information', 4, '2025-02-02'),
(31, 3, 'L`Etranger', 15, 'test \'', 5, '2025-02-03');

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
  `statutCommande` enum('en attente','annulée','expédiée','arrivée') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dateLivraisonCommande` date DEFAULT NULL,
  `idUser` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `dateCommande`, `statutCommande`, `dateLivraisonCommande`, `idUser`) VALUES
(351, '2025-01-29', 'expédiée', '2025-02-05', 2),
(352, '2025-01-29', 'expédiée', '2025-02-05', 2),
(353, '2025-01-29', 'expédiée', '2025-02-05', 2),
(354, '2025-01-29', 'expédiée', '2025-02-05', 2),
(355, '2025-01-29', 'expédiée', '2025-02-05', 2),
(356, '2025-01-29', 'expédiée', '2025-02-05', 2),
(357, '2025-01-29', 'expédiée', '2025-02-05', 2),
(358, '2025-01-29', 'expédiée', '2025-02-05', 2),
(359, '2025-01-29', 'expédiée', '2025-02-05', 2),
(360, '2025-01-29', 'expédiée', '2025-02-05', 2),
(361, '2025-01-29', 'expédiée', '2025-02-05', 2),
(362, '2025-01-29', 'expédiée', '2025-02-05', 2),
(363, '2025-01-29', 'expédiée', '2025-02-05', 2),
(364, '2025-01-29', 'expédiée', '2025-02-05', 15),
(365, '2025-01-29', 'expédiée', '2025-02-05', 15),
(366, '2025-01-29', 'expédiée', '2025-02-05', 15),
(367, '2025-01-29', 'expédiée', '2025-02-05', 15),
(368, '2025-01-29', 'expédiée', '2025-02-05', 15),
(369, '2025-01-29', 'expédiée', '2025-02-05', 15),
(370, '2025-01-29', 'expédiée', '2025-02-05', 15),
(371, '2025-01-29', 'expédiée', '2025-02-05', 15),
(372, '2025-01-29', 'expédiée', '2025-02-05', 2),
(373, '2025-01-29', 'expédiée', '2025-02-05', 2),
(374, '2025-01-29', 'expédiée', '2025-02-05', 2),
(375, '2025-01-29', 'expédiée', '2025-02-05', 15),
(376, '2025-01-29', 'expédiée', '2025-02-05', 15),
(377, '2025-01-29', 'expédiée', '2025-02-05', 15),
(378, '2025-01-29', 'expédiée', '2025-02-05', 15),
(379, '2025-01-29', 'expédiée', '2025-02-05', 15),
(380, '2025-01-29', 'expédiée', '2025-02-05', 15),
(381, '2025-01-29', 'expédiée', '2025-02-05', 15),
(382, '2025-01-29', 'expédiée', '2025-02-05', 15),
(383, '2025-01-29', 'expédiée', '2025-02-05', 24),
(384, '2025-01-29', 'expédiée', '2025-02-05', 24),
(385, '2025-01-29', 'expédiée', '2025-02-05', 24),
(386, '2025-01-29', 'expédiée', '2025-02-05', 24),
(387, '2025-01-29', 'expédiée', '2025-02-05', 24),
(388, '2025-01-29', 'expédiée', '2025-02-05', 24),
(389, '2025-01-29', 'expédiée', '2025-02-05', 24),
(390, '2025-01-29', 'expédiée', '2025-02-05', 24),
(391, '2025-01-29', 'expédiée', '2025-02-05', 24),
(392, '2025-01-29', 'expédiée', '2025-02-05', 24),
(393, '2025-01-29', 'expédiée', '2025-02-05', 24),
(395, '2025-01-29', 'expédiée', '2025-02-05', 24),
(396, '2025-01-29', 'expédiée', '2025-02-05', 24),
(397, '2025-01-29', 'expédiée', '2025-02-05', 24),
(398, '2025-01-29', 'expédiée', '2025-02-05', 24),
(399, '2025-01-29', 'expédiée', '2025-02-05', 24),
(400, '2025-01-29', 'expédiée', '2025-02-05', 24),
(401, '2025-01-29', 'expédiée', '2025-02-05', 24),
(402, '2025-01-29', 'expédiée', '2025-02-05', 24),
(403, '2025-01-29', 'expédiée', '2025-02-05', 24),
(404, '2025-01-29', 'expédiée', '2025-02-05', 24),
(405, '2025-01-29', 'expédiée', '2025-02-05', 24),
(406, '2025-01-30', 'expédiée', '2025-02-06', 2),
(407, '2025-01-30', 'expédiée', '2025-02-06', 2),
(408, '2025-01-30', 'expédiée', '2025-02-06', 2),
(409, '2025-01-30', 'expédiée', '2025-02-06', 15),
(410, '2025-01-30', 'expédiée', '2025-02-06', 15),
(411, '2025-01-30', 'expédiée', '2025-02-06', 15),
(412, '2025-01-30', 'expédiée', '2025-02-06', 15),
(413, '2025-01-30', 'expédiée', '2025-02-06', 15),
(414, '2025-01-30', 'expédiée', '2025-02-06', 15),
(415, '2025-01-30', 'expédiée', '2025-02-06', 15),
(417, '2025-02-02', 'expédiée', '2025-02-09', 15),
(418, '2025-02-02', 'expédiée', '2025-02-09', 15),
(419, '2025-02-02', 'expédiée', '2025-02-09', 15),
(420, '2025-02-02', 'expédiée', '2025-02-09', 15),
(421, '2025-02-02', 'expédiée', '2025-02-09', 15),
(422, '2025-02-02', 'expédiée', '2025-02-09', 15),
(423, '2025-02-02', 'expédiée', '2025-02-09', 15),
(424, '2025-02-02', 'expédiée', '2025-02-09', 15),
(425, '2025-02-02', 'expédiée', '2025-02-09', 24),
(426, '2025-02-02', 'expédiée', '2025-02-09', 24),
(427, '2025-02-02', 'expédiée', '2025-02-09', 2),
(428, '2025-02-02', 'expédiée', '2025-02-09', 15),
(429, '2025-02-02', 'expédiée', '2025-02-09', 15),
(430, '2025-02-02', 'expédiée', '2025-02-09', 15),
(431, '2025-02-02', 'expédiée', '2025-02-09', 15),
(432, '2025-02-02', 'expédiée', '2025-02-09', 15),
(433, '2025-02-02', 'expédiée', '2025-02-09', 15),
(434, '2025-02-02', 'expédiée', '2025-02-09', 15),
(435, '2025-02-02', 'expédiée', '2025-02-09', 15),
(436, '2025-02-02', 'expédiée', '2025-02-09', 15),
(437, '2025-02-02', 'expédiée', '2025-02-09', 15),
(438, '2025-02-02', 'expédiée', '2025-02-09', 15),
(439, '2025-02-02', 'expédiée', '2025-02-09', 15),
(440, '2025-02-02', 'expédiée', '2025-02-09', 15),
(441, '2025-02-02', 'expédiée', '2025-02-09', 15),
(442, '2025-02-02', 'expédiée', '2025-02-09', 15),
(443, '2025-02-02', 'expédiée', '2025-02-09', 15),
(444, '2025-02-02', 'expédiée', '2025-02-09', 15),
(445, '2025-02-02', 'expédiée', '2025-02-09', 15),
(446, '2025-02-02', 'expédiée', '2025-02-09', 15),
(447, '2025-02-02', 'expédiée', '2025-02-09', 15),
(448, '2025-02-02', 'expédiée', '2025-02-09', 15),
(449, '2025-02-02', 'expédiée', '2025-02-09', 15),
(450, '2025-02-02', 'expédiée', '2025-02-09', 15),
(452, '2025-02-03', 'expédiée', '2025-02-10', 15),
(453, '2025-02-03', 'expédiée', '2025-02-10', 15),
(454, '2025-02-03', 'expédiée', '2025-02-10', 15),
(455, '2025-02-03', 'expédiée', '2025-02-10', 15),
(462, '2025-04-24', 'expédiée', '2025-05-01', 37),
(463, '2025-04-25', 'en attente', '2025-05-02', 2),
(465, '2025-04-26', 'expédiée', '2025-05-03', 37),
(466, '2025-04-26', 'expédiée', '2025-05-03', 37),
(467, '2025-04-26', 'expédiée', '2025-05-03', 37),
(468, '2025-04-26', 'expédiée', '2025-05-03', 37),
(469, '2025-04-26', 'expédiée', '2025-05-03', 37),
(470, '2025-04-26', 'expédiée', '2025-05-03', 37),
(471, '2025-04-26', 'expédiée', '2025-05-03', 37),
(472, '2025-04-26', 'expédiée', '2025-05-03', 37),
(473, '2025-04-26', 'expédiée', '2025-05-03', 37),
(474, '2025-04-26', 'expédiée', '2025-05-03', 37),
(475, '2025-04-26', 'expédiée', '2025-05-03', 37),
(476, '2025-04-26', 'expédiée', '2025-05-03', 37),
(477, '2025-04-26', 'expédiée', '2025-05-03', 37),
(478, '2025-04-26', 'expédiée', '2025-05-03', 37),
(479, '2025-04-26', 'expédiée', '2025-05-03', 37),
(480, '2025-04-26', 'expédiée', '2025-05-03', 37),
(481, '2025-04-26', 'expédiée', '2025-05-03', 37),
(482, '2025-04-26', 'expédiée', '2025-05-03', 37),
(483, '2025-04-26', 'expédiée', '2025-05-03', 37),
(484, '2025-04-27', 'expédiée', '2025-05-04', 37);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `idUser` int NOT NULL,
  `siretUser` varchar(14) DEFAULT NULL,
  `raisonSocialeUser` varchar(255) DEFAULT NULL,
  `capitalSocialUser` float(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`idUser`, `siretUser`, `raisonSocialeUser`, `capitalSocialUser`) VALUES
(26, '123456789', 'Entreprise SARL', 10000.00),
(27, '123456789', 'Entreprise SARL', 10000.00),
(30, '123123123', 'yasser', 123123120.00),
(31, '1298371892', '123', NULL),
(32, '123', '123', 123124.00),
(33, '123123', '123123', 123123.00),
(34, '987987', '987987', 987987.00),
(43, 'entauth', 'entauth', 12312.93);

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
(832, 484, 10, 1);

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
(1, 'Alcools', 'Apollinaire', 'alcools.png', 99, 12.50, 3, 1, 1),
(2, 'Crime et Chatiment', 'Dostoïevski', 'crime_et_chatiment.png', 84, 15.00, 1, 2, NULL),
(3, 'L`Etranger', 'Camus', 'l_etranger.png', 55, 10.00, 1, 3, NULL),
(4, 'L`Odyssée', 'Homère', 'l_odyssee.png', 89, 13.50, 2, 4, NULL),
(5, 'Les Fleurs du Mal', 'Baudelaire', 'les_fleurs_du_mal.png', 100, 14.00, 3, 5, NULL),
(6, 'PHP et MySQL pour les nuls', 'Valade', 'php_et_mysql_pour_les_nuls.png', 79, 22.00, 4, 6, NULL),
(7, 'Programmer en Java', 'Delannoy', 'programmer_en_java.png', 100, 25.00, 4, 7, NULL),
(8, 'SPQR', 'Beard', 'spqr.png', 99, 18.00, 2, 8, NULL),
(9, 'À la recherche du temps perdu', 'Proust', 'a_la_recherche_du_temps_perdu.png', 96, 0.00, 1, 1, NULL),
(10, 'Les Misérables', 'Hugo', 'les_miserables_I.png', 99, 0.00, 1, 2, NULL),
(11, '1984', 'Orwell', '1984.png', 95, 0.00, 1, 3, NULL),
(12, 'L`Art d\'aimer', 'Ovide', 'l_art_d_aimer', 92, 0.00, 1, 4, NULL),
(13, 'La Peste', 'Camus', 'la_peste.png', -13, 15.99, 1, 1, NULL),
(14, 'Les Mémoires d\'Hadrien', 'Yourcenar', 'les_memoires_d_hadrien.png', 94, 12.99, 1, 1, NULL),
(15, 'La Condition humaine', 'Malraux', 'la_condition_humaine.png', 100, 14.99, 1, 1, NULL),
(16, 'Le Comte de Monte-Cristo', 'Dumas', 'le_comte_de_monte_cristo.png', 100, 9.99, 1, 2, NULL),
(17, 'Orgueil et Préjugés', 'Austen', 'orgueil_et_prejuges.png', 100, 8.99, 1, 2, NULL),
(18, 'Shining', 'King', 'shining.png', 100, 10.99, 1, 2, NULL),
(19, 'Bel-Ami', 'Maupassant', 'bel_ami.png', 99, 11.99, 1, 3, NULL),
(20, 'Fahrenheit 451', 'Bradbury', 'fahrenheit_451.png', 100, 9.99, 1, 3, NULL),
(21, 'La Nuit des temps', 'Barjavel', 'la_nuit_des_temps.png', 100, 12.99, 1, 3, NULL),
(22, 'L`Énéide', 'Virgile', 'l_eneide.png', 100, 19.99, 3, 4, NULL),
(23, 'Les Pensées', 'Aurèle', 'les_pensees.png', 100, 18.99, 3, 4, NULL),
(24, 'Les Métamorphoses', 'Ovide', 'les_metamorphoses.png', 100, 20.99, 3, 4, NULL),
(25, 'Le Petit Livre des citations latines', 'Delamaire', 'le_petit_livre_des_citations_latines.png', 100, 7.99, 3, 6, NULL),
(43, 'Le Petit Livre des grandes coïncidences', 'Chiflet', 'le_petit_livre_des_grandes_coincidences.png', 100, 7.99, 3, 6, NULL),
(44, 'Le Petit Livre des gros mensonges', 'Chiflet', 'le_petit_livre_des_gros_mensonges.png', 100, 7.99, 3, 6, NULL),
(45, 'L`Art de la guerre', 'Sun', 'l_art_de_la_guerre.png', 100, 12.99, 2, 7, NULL),
(46, 'Apprendre à dessiner', 'Edwards', 'apprendre_a_dessiner.png', 100, 14.99, 4, 7, NULL),
(47, 'Le Lean Startup', 'Ries', 'le_lean_startup.png', 100, 16.99, 4, 7, NULL),
(48, 'Les Templiers', 'Demurger', 'les_templiers.png', 100, 18.99, 2, 8, NULL),
(49, 'La Seconde Guerre mondiale', 'Beevor', 'la_seconde_guerre_mondiale.png', 100, 19.99, 2, 8, NULL),
(50, 'Napoléon : Une ambition française', 'Tulard', 'napoleon_une_ambition_francaise.png', 100, 20.99, 2, 8, NULL),
(51, 'dimanche', 'dimanche', 'dimanche.png', 180, 7.20, NULL, 3, NULL),
(52, 'cate', 'cate', 'cate.png', 12, 12.00, NULL, 3, NULL),
(53, 'fin', 'fin', 'fin.png', 360, 14.00, 4, 8, NULL);

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
(37, 'part', 'part', '2009-12-12', 'M'),
(38, 'uy', 'uy', '2003-03-12', 'M'),
(42, 'partauth', 'partauth', '2005-02-20', 'M');

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
(1, '10%', '2025-01-05', '2025-01-20', 10),
(2, '20%', '2025-02-01', '2025-02-15', 20),
(3, '30%', '2025-03-01', '2025-03-10', 30),
(4, '40%', '2025-03-26', '2026-12-12', 40),
(5, '50%', '2025-03-26', '2026-12-12', 50),
(6, '60%', '2025-02-02', '2025-02-10', 60),
(7, '70%', '2025-03-26', '2026-12-12', 70),
(8, '80%', '2025-03-26', '2026-12-12', 80),
(9, '90%', '2025-01-05', '2025-01-20', 90),
(10, 'Aucune promotion', '2025-01-05', '2025-01-20', 0);

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
(37, 'part@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'part', 'particulier'),
(38, 'uy@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uy', 'particulier'),
(42, 'partauth@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'partauth', 'particulier'),
(43, 'entauth@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'entauth', 'entreprise');

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
-- Index pour la table `archiveLigneCommande`
--
ALTER TABLE `archiveLigneCommande`
  ADD PRIMARY KEY (`idLigneCommande`,`date_archivage`),
  ADD KEY `idx_commande` (`idCommande`),
  ADD KEY `idx_date_archivage` (`date_archivage`);

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
  MODIFY `idAbonnement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `idAvis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  MODIFY `idLigneCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=833;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `idLivre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `maisonEdition`
--
ALTER TABLE `maisonEdition`
  MODIFY `idMaisonEdition` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `particulier`
--
ALTER TABLE `particulier`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `idPromotion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
