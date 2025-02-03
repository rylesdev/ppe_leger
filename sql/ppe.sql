-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 03 fév. 2025 à 01:50
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `pOffrirLivre` (IN `p_idUser` INT, IN `p_chiffre` INT)   BEGIN
    DECLARE newIdCommande INT;
    DECLARE randomLivreId INT;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Vous devez être abonné pour bénéficier de cette offre.';
    END IF;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser AND dateFinAbonnement > CURDATE()
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Votre abonnement a expiré. Vous ne pouvez pas bénéficier de cette offre.';
    END IF;
    IF p_chiffre = 5 THEN
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        SELECT idLivre
        INTO randomLivreId
        FROM (
            SELECT 9 AS idLivre UNION ALL
            SELECT 10 UNION ALL
            SELECT 11 UNION ALL
            SELECT 12
        ) AS livres
        ORDER BY RAND()
        LIMIT 1;
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, randomLivreId, 1);
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un livre vous a été offert et va vous être envoyé directement chez vous !';
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
(4, 15, '2025-01-26', '2025-02-28', 80);

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
) ;

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
(153, '2025-01-10', 'expédiée', '2025-01-17', 2),
(158, '2025-01-11', 'expédiée', '2025-01-18', 2),
(159, '2025-01-11', 'expédiée', '2025-01-18', 2),
(160, '2025-01-11', 'expédiée', '2025-01-18', 2),
(161, '2025-01-11', 'expédiée', '2025-01-18', 2),
(164, '2025-01-12', 'expédiée', '2025-01-19', 2),
(165, '2025-01-12', 'expédiée', '2025-01-19', 2),
(166, '2025-01-12', 'expédiée', '2025-01-19', 2),
(167, '2025-01-12', 'expédiée', '2025-01-19', 2),
(168, '2025-01-12', 'expédiée', '2025-01-19', 2),
(169, '2025-01-12', 'expédiée', '2025-01-19', 2),
(170, '2025-01-12', 'expédiée', '2025-01-19', 2),
(171, '2025-01-12', 'expédiée', '2025-01-19', 2),
(172, '2025-01-12', 'expédiée', '2025-01-19', 2),
(173, '2025-01-12', 'expédiée', '2025-01-19', 2),
(174, '2025-01-12', 'expédiée', '2025-01-19', 2),
(175, '2025-01-12', 'expédiée', '2025-01-19', 2),
(176, '2025-01-12', 'expédiée', '2025-01-19', 2),
(177, '2025-01-12', 'expédiée', '2025-01-19', 2),
(178, '2025-01-12', 'expédiée', '2025-01-19', 2),
(179, '2025-01-12', 'expédiée', '2025-01-19', 2),
(180, '2025-01-12', 'expédiée', '2025-01-19', 2),
(181, '2025-01-12', 'expédiée', '2025-01-19', 2),
(182, '2025-01-12', 'expédiée', '2025-01-19', 2),
(183, '2025-01-12', 'expédiée', '2025-01-19', 2),
(184, '2025-01-12', 'expédiée', '2025-01-19', 2),
(185, '2025-01-12', 'expédiée', '2025-01-19', 2),
(186, '2025-01-12', 'expédiée', '2025-01-19', 2),
(187, '2025-01-12', 'expédiée', '2025-01-19', 2),
(188, '2025-01-12', 'expédiée', '2025-01-19', 2),
(189, '2025-01-12', 'expédiée', '2025-01-19', 2),
(190, '2025-01-12', 'expédiée', '2025-01-19', 2),
(191, '2025-01-12', 'expédiée', '2025-01-19', 2),
(192, '2025-01-12', 'expédiée', '2025-01-19', 2),
(193, '2025-01-12', 'expédiée', '2025-01-19', 2),
(194, '2025-01-12', 'expédiée', '2025-01-19', 2),
(195, '2025-01-12', 'expédiée', '2025-01-19', 2),
(196, '2025-01-12', 'expédiée', '2025-01-19', 2),
(197, '2025-01-12', 'expédiée', '2025-01-19', 2),
(198, '2025-01-12', 'expédiée', '2025-01-19', 2),
(199, '2025-01-12', 'expédiée', '2025-01-19', 2),
(200, '2025-01-12', 'expédiée', '2025-01-19', 2),
(201, '2025-01-12', 'expédiée', '2025-01-19', 2),
(202, '2025-01-12', 'expédiée', '2025-01-19', 2),
(203, '2025-01-12', 'expédiée', '2025-01-19', 2),
(204, '2025-01-12', 'expédiée', '2025-01-19', 2),
(205, '2025-01-12', 'expédiée', '2025-01-19', 2),
(206, '2025-01-12', 'expédiée', '2025-01-19', 2),
(207, '2025-01-12', 'expédiée', '2025-01-19', 2),
(210, '2025-01-12', 'expédiée', '2025-01-19', 2),
(211, '2025-01-12', 'expédiée', '2025-01-19', 2),
(212, '2025-01-12', 'expédiée', '2025-01-19', 2),
(213, '2025-01-12', 'expédiée', '2025-01-19', 2),
(214, '2025-01-12', 'expédiée', '2025-01-24', 2),
(215, '2025-01-24', 'expédiée', '2025-01-31', 2),
(217, '2025-01-24', 'expédiée', '2025-01-31', 2),
(218, '2025-01-24', 'expédiée', '2025-01-31', 2),
(219, '2025-01-28', 'expédiée', '2025-02-04', 2),
(220, '2025-01-29', 'expédiée', '2025-02-05', 2),
(221, '2025-01-29', 'expédiée', '2025-02-05', 2),
(222, '2025-01-29', 'expédiée', '2025-02-05', 2),
(223, '2025-01-29', 'expédiée', '2025-02-05', 2),
(224, '2025-01-29', 'expédiée', '2025-02-05', 2),
(225, '2025-01-29', 'expédiée', '2025-02-05', 2),
(226, '2025-01-29', 'expédiée', '2025-02-05', 2),
(227, '2025-01-29', 'expédiée', '2025-02-05', 2),
(228, '2025-01-29', 'expédiée', '2025-02-05', 2),
(229, '2025-01-29', 'expédiée', '2025-02-05', 2),
(230, '2025-01-29', 'expédiée', '2025-02-05', 2),
(231, '2025-01-29', 'expédiée', '2025-02-05', 2),
(232, '2025-01-29', 'expédiée', '2025-02-05', 2),
(233, '2025-01-29', 'expédiée', '2025-02-05', 2),
(234, '2025-01-29', 'expédiée', '2025-02-05', 2),
(235, '2025-01-29', 'expédiée', '2025-02-05', 2),
(236, '2025-01-29', 'expédiée', '2025-02-05', 2),
(237, '2025-01-29', 'expédiée', '2025-02-05', 2),
(238, '2025-01-29', 'expédiée', '2025-02-05', 2),
(239, '2025-01-29', 'expédiée', '2025-02-05', 2),
(240, '2025-01-29', 'expédiée', '2025-02-05', 2),
(241, '2025-01-29', 'expédiée', '2025-02-05', 2),
(242, '2025-01-29', 'expédiée', '2025-02-05', 2),
(243, '2025-01-29', 'expédiée', '2025-02-05', 2),
(244, '2025-01-29', 'expédiée', '2025-02-05', 2),
(245, '2025-01-30', 'expédiée', '2025-02-06', 2),
(246, '2025-01-30', 'expédiée', '2025-02-06', 2),
(247, '2025-01-30', 'expédiée', '2025-02-06', 2),
(248, '2025-02-02', 'expédiée', '2025-02-09', 2),
(249, '2025-02-02', 'expédiée', '2025-02-09', 2),
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
(264, '2025-01-12', 'en attente', '2025-01-19', 2),
(271, '2025-01-24', 'expédiée', '2025-01-31', 23),
(272, '2025-01-24', 'expédiée', '2025-01-31', 23),
(273, '2025-01-24', 'expédiée', '2025-01-31', 23),
(274, '2025-01-24', 'expédiée', '2025-01-31', 23),
(275, '2025-01-24', 'expédiée', '2025-01-31', 23),
(276, '2025-01-24', 'expédiée', '2025-01-31', 23),
(277, '2025-01-26', 'expédiée', '2025-02-02', 23),
(278, '2025-01-26', 'expédiée', '2025-02-02', 23),
(279, '2025-01-23', 'en attente', '2025-01-30', 23),
(280, '2025-01-23', 'en attente', '2025-01-30', 23),
(281, '2025-01-23', 'en attente', '2025-01-30', 23),
(282, '2025-01-23', 'en attente', '2025-01-30', 23),
(283, '2025-01-23', 'en attente', '2025-01-30', 23),
(284, '2025-01-23', 'en attente', '2025-01-30', 23),
(286, '2025-01-24', 'en attente', '2025-01-31', 2),
(289, '2025-01-24', 'en attente', '2025-01-31', 2),
(290, '2025-01-24', 'en attente', '2025-01-31', 2),
(291, '2025-01-24', 'en attente', '2025-01-31', 2),
(292, '2025-01-24', 'en attente', '2025-01-31', 2),
(293, '2025-01-24', 'en attente', '2025-01-31', 2),
(294, '2025-01-24', 'en attente', '2025-01-31', 2),
(295, '2025-01-24', 'en attente', '2025-01-31', 2),
(296, '2025-01-24', 'en attente', '2025-01-31', 2),
(297, '2025-01-24', 'en attente', '2025-01-31', 2),
(298, '2025-01-24', 'en attente', '2025-01-31', 2),
(299, '2025-01-24', 'en attente', '2025-01-31', 2),
(300, '2025-01-24', 'en attente', '2025-01-31', 2),
(301, '2025-01-24', 'en attente', '2025-01-31', 2),
(302, '2025-01-24', 'en attente', '2025-01-31', 2),
(303, '2025-01-24', 'en attente', '2025-01-31', 2),
(304, '2025-01-24', 'en attente', '2025-01-31', 2),
(305, '2025-01-24', 'en attente', '2025-01-31', 2),
(306, '2025-01-24', 'en attente', '2025-01-31', 2),
(307, '2025-01-24', 'en attente', '2025-01-31', 2),
(308, '2025-01-24', 'en attente', '2025-01-31', 2),
(309, '2025-01-24', 'en attente', '2025-01-31', 2),
(310, '2025-01-24', 'en attente', '2025-01-31', 2),
(311, '2025-01-24', 'en attente', '2025-01-31', 2),
(312, '2025-01-24', 'en attente', '2025-01-31', 2),
(313, '2025-01-24', 'en attente', '2025-01-31', 2),
(314, '2025-01-24', 'en attente', '2025-01-31', 2),
(315, '2025-01-24', 'en attente', '2025-01-31', 2),
(316, '2025-01-24', 'en attente', '2025-01-31', 2),
(317, '2025-01-24', 'en attente', '2025-01-31', 2),
(318, '2025-01-24', 'en attente', '2025-01-31', 2),
(319, '2025-01-24', 'en attente', '2025-01-31', 2),
(320, '2025-01-24', 'en attente', '2025-01-31', 2),
(321, '2025-01-24', 'en attente', '2025-01-31', 2),
(322, '2025-01-24', 'en attente', '2025-01-31', 2),
(323, '2025-01-24', 'en attente', '2025-01-31', 2),
(324, '2025-01-24', 'en attente', '2025-01-31', 2),
(325, '2025-01-24', 'en attente', '2025-01-31', 2),
(326, '2025-01-24', 'en attente', '2025-01-31', 2),
(327, '2025-01-24', 'en attente', '2025-01-31', 2),
(328, '2025-01-24', 'en attente', '2025-01-31', 2),
(329, '2025-01-24', 'en attente', '2025-01-31', 2),
(330, '2025-01-24', 'en attente', '2025-01-31', 2),
(331, '2025-01-24', 'en attente', '2025-01-31', 2),
(332, '2025-01-24', 'en attente', '2025-01-31', 2),
(333, '2025-01-24', 'en attente', NULL, 2),
(337, '2025-01-27', 'expédiée', '2025-02-03', 15),
(338, '2025-01-27', 'expédiée', '2025-02-03', 2),
(339, '2025-01-28', 'expédiée', '2025-02-04', 15),
(340, '2025-01-28', 'expédiée', '2025-02-04', 15),
(341, '2025-01-28', 'expédiée', '2025-02-04', 2),
(342, '2025-01-29', 'expédiée', '2025-02-05', 2),
(343, '2025-01-29', 'expédiée', '2025-02-05', 2),
(344, '2025-01-29', 'expédiée', '2025-02-05', 2),
(345, '2025-01-29', 'expédiée', '2025-02-05', 2),
(346, '2025-01-29', 'expédiée', '2025-02-05', 2),
(347, '2025-01-29', 'expédiée', '2025-02-05', 2),
(348, '2025-01-29', 'expédiée', '2025-02-05', 2),
(349, '2025-01-29', 'expédiée', '2025-02-05', 2),
(350, '2025-01-29', 'expédiée', '2025-02-05', 2),
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
(455, '2025-02-03', 'expédiée', '2025-02-10', 15);

--
-- Déclencheurs `commande`
--
DELIMITER $$
CREATE TRIGGER `tUpdateStockCommandeExpediee` AFTER UPDATE ON `commande` FOR EACH ROW begin
    if OLD.statutCommande = 'en attente' and NEW.statutCommande = 'expédiée' then
        update livre
        set exemplaireLivre = exemplaireLivre - (
            select sum(quantiteLigneCommande)
            from ligneCommande
            where idCommande = NEW.idCommande
            and ligneCommande.idLivre = livre.idLivre
        )
        where idLivre in (select idLivre from ligneCommande where idCommande = NEW.idCommande);
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `idUser` int NOT NULL,
  `emailUser` varchar(255) NOT NULL,
  `mdpUser` varchar(255) NOT NULL,
  `siretUser` varchar(14) DEFAULT NULL,
  `raisonSocialeUser` varchar(255) DEFAULT NULL,
  `capitalSocialUser` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`idUser`, `emailUser`, `mdpUser`, `siretUser`, `raisonSocialeUser`, `capitalSocialUser`) VALUES
(26, 'entreprise@gmail.com', 'motdepasse', '123456789', 'Entreprise SARL', 10000.00),
(27, 'entreprise@gmail.com', 'motdepasse', '123456789', 'Entreprise SARL', 10000.00),
(30, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123123123', 'yasser', 123123123.00),
(31, 'entreprise@gmail.com', '123', '1298371892', '123', NULL),
(32, 'test@gmail.com', '123', '123', '123', 123124.00),
(33, 'yass@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123123', '123123', 123123.00),
(34, '987987@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '987987', '987987', 987987.00);

--
-- Déclencheurs `entreprise`
--
DELIMITER $$
CREATE TRIGGER `tInsertEntreprise` BEFORE INSERT ON `entreprise` FOR EACH ROW BEGIN
    IF NEW.idUser IS NULL OR NEW.idUser NOT IN (SELECT idUser FROM user) THEN
        SET NEW.idUser = IFNULL((SELECT MAX(idUser) FROM user), 0) + 1;
    END IF;
    INSERT INTO user (idUser, emailUser, mdpUser, adresseUser, roleUser)
    VALUES (NEW.idUser, NEW.emailUser, NEW.mdpUser, NULL, 'entreprise');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tUpdateEntreprise` BEFORE UPDATE ON `entreprise` FOR EACH ROW BEGIN
    UPDATE user
    SET emailUser = NEW.emailUser, mdpUser = NEW.mdpUser
    WHERE idUser = OLD.idUser;
END
$$
DELIMITER ;

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
(521, 289, 1, 33),
(533, 290, 1, 3),
(534, 291, 1, 5),
(535, 292, 1, 4),
(536, 293, 1, 2),
(537, 294, 1, 2),
(538, 295, 1, 3),
(539, 296, 1, 3),
(540, 297, 1, 3),
(543, 298, 1, 3),
(544, 299, 1, 6),
(545, 300, 1, 6),
(546, 301, 1, 3),
(547, 302, 2, 3),
(548, 303, 3, 3),
(549, 304, 3, 3),
(550, 305, 3, 3),
(551, 306, 1, 3),
(552, 307, 2, 3),
(553, 308, 2, 3),
(554, 309, 1, 2),
(555, 310, 2, 2),
(556, 311, 3, 2),
(557, 312, 1, 3),
(558, 313, 1, 2),
(559, 314, 1, 2),
(560, 315, 1, 3),
(561, 316, 1, 3),
(562, 317, 1, 3),
(563, 318, 1, 3),
(564, 319, 1, 3),
(565, 320, 1, 3),
(566, 321, 1, 3),
(567, 322, 1, 3),
(568, 323, 1, 3),
(569, 324, 1, 3),
(570, 325, 1, 3),
(571, 330, 1, 3),
(572, 331, 1, 3),
(573, 332, 1, 3),
(574, 153, 1, 3),
(575, 153, 1, 3),
(576, 153, 1, 3),
(577, 153, 1, 3),
(578, 153, 1, 5),
(579, 159, 1, 5),
(607, 271, 1, 5),
(608, 271, 5, 5),
(609, 274, 8, 3),
(610, 219, 4, 1),
(615, 337, 2, 10),
(616, 338, 6, 1),
(617, 337, 3, 10),
(618, 339, 8, 1),
(619, 340, 6, 1),
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
(720, 455, 2, 1);

--
-- Déclencheurs `ligneCommande`
--
DELIMITER $$
CREATE TRIGGER `tExemplaireLivreLigneCommande` BEFORE INSERT ON `ligneCommande` FOR EACH ROW begin
    declare existingQuantity int;
    select lc.quantiteLigneCommande
    into existingQuantity
    from ligneCommande lc
    inner join commande c on lc.idCommande = c.idCommande
    where lc.idLivre = NEW.idLivre
      and c.idUser = (select idUser from commande where idCommande = NEW.idCommande LIMIT 1)
      and c.statutCommande = 'en attente'
    LIMIT 1;
    if existingQuantity is not null then
        SIGNAL SQLSTATE "45000"
        SET MESSAGE_TEXT = "Erreur : Livre déjà dans le panier de cet utilisateur. Veuillez modifier le nombre d'exemplaires.";
    end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tStockNull` BEFORE INSERT ON `ligneCommande` FOR EACH ROW begin
declare exemplaireLivreDisponible int;
select exemplaireLivre
into exemplaireLivreDisponible
from livre
where idLivre = NEW.idLivre;
if exemplaireLivreDisponible < NEW.quantiteLigneCommande then
    SIGNAL SQLSTATE '45000'
    set MESSAGE_TEXT = 'Erreur : Stock insuffisant pour le livre.';
end IF;
end
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
  `idMaisonEdition` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`idLivre`, `nomLivre`, `auteurLivre`, `imageLivre`, `exemplaireLivre`, `prixLivre`, `idCategorie`, `idMaisonEdition`) VALUES
(1, 'Alcools', 'Apollinaire', 'alcools.png', 99, 12.50, 3, 1),
(2, 'Crime et Chatiment', 'Dostoïevski', 'crime_et_chatiment.png', 91, 15.00, 1, 2),
(3, 'L`Etranger', 'Camus', 'l_etranger.png', 56, 10.00, 1, 3),
(4, 'L`Odyssée', 'Homère', 'l_odyssee.png', 89, 13.50, 2, 4),
(5, 'Les Fleurs du Mal', 'Baudelaire', 'les_fleurs_du_mal.png', 100, 14.00, 3, 5),
(6, 'PHP et MySQL pour les nuls', 'Valade', 'php_et_mysql_pour_les_nuls.png', 79, 22.00, 4, 6),
(7, 'Programmer en Java', 'Delannoy', 'programmer_en_java.png', 100, 25.00, 4, 7),
(8, 'SPQR', 'Beard', 'spqr.png', 99, 18.00, 2, 8),
(9, 'À la recherche du temps perdu', 'Proust', 'a_la_recherche_du_temps_perdu.png', 96, 0.00, 1, 1),
(10, 'Les Misérables', 'Hugo', 'les_miserables_I.png', 99, 0.00, 1, 2),
(11, '1984', 'Orwell', '1984.png', 95, 0.00, 1, 3),
(12, 'L`Art d\'aimer', 'Ovide', 'l_art_d_aimer', 92, 0.00, 1, 4),
(13, 'La Peste', 'Camus', 'la_peste.png', 98, 15.99, 1, 1),
(14, 'Les Mémoires d\'Hadrien', 'Yourcenar', 'les_memoires_d_hadrien.png', 95, 12.99, 1, 1),
(15, 'La Condition humaine', 'Malraux', 'la_condition_humaine.png', 100, 14.99, 1, 1),
(16, 'Le Comte de Monte-Cristo', 'Dumas', 'le_comte_de_monte_cristo.png', 100, 9.99, 1, 2),
(17, 'Orgueil et Préjugés', 'Austen', 'orgueil_et_prejuges.png', 100, 8.99, 1, 2),
(18, 'Shining', 'King', 'shining.png', 100, 10.99, 1, 2),
(19, 'Bel-Ami', 'Maupassant', 'bel_ami.png', 100, 11.99, 1, 3),
(20, 'Fahrenheit 451', 'Bradbury', 'fahrenheit_451.png', 100, 9.99, 1, 3),
(21, 'La Nuit des temps', 'Barjavel', 'la_nuit_des_temps.png', 100, 12.99, 1, 3),
(22, 'L`Énéide', 'Virgile', 'l_eneide.png', 100, 19.99, 3, 4),
(23, 'Les Pensées', 'Aurèle', 'les_pensees.png', 100, 18.99, 3, 4),
(24, 'Les Métamorphoses', 'Ovide', 'les_metamorphoses.png', 100, 20.99, 3, 4),
(25, 'Le Petit Livre des citations latines', 'Delamaire', 'le_petit_livre_des_citations_latines.png', 100, 7.99, 3, 6),
(43, 'Le Petit Livre des grandes coïncidences', 'Chiflet', 'le_petit_livre_des_grandes_coincidences.png', 100, 7.99, 3, 6),
(44, 'Le Petit Livre des gros mensonges', 'Chiflet', 'le_petit_livre_des_gros_mensonges.png', 100, 7.99, 3, 6),
(45, 'L`Art de la guerre', 'Sun', 'l_art_de_la_guerre.png', 100, 12.99, 2, 7),
(46, 'Apprendre à dessiner', 'Edwards', 'apprendre_a_dessiner.png', 100, 14.99, 4, 7),
(47, 'Le Lean Startup', 'Ries', 'le_lean_startup.png', 100, 16.99, 4, 7),
(48, 'Les Templiers', 'Demurger', 'les_templiers.png', 100, 18.99, 2, 8),
(49, 'La Seconde Guerre mondiale', 'Beevor', 'la_seconde_guerre_mondiale.png', 100, 19.99, 2, 8),
(50, 'Napoléon : Une ambition française', 'Tulard', 'napoleon_une_ambition_francaise.png', 100, 20.99, 2, 8),
(51, 'dimanche', 'dimanche', 'dimanche.png', 180, 7.20, NULL, 3),
(52, 'cate', 'cate', 'cate.png', 12, 12.00, NULL, 3),
(53, 'fin', 'fin', 'fin.png', 360, 14.00, 4, 8);

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
  `emailUser` varchar(255) NOT NULL,
  `mdpUser` varchar(255) NOT NULL,
  `nomUser` varchar(255) DEFAULT NULL,
  `prenomUser` varchar(255) DEFAULT NULL,
  `adresseUser` varchar(255) DEFAULT 'adresse_inconnue',
  `dateNaissanceUser` date DEFAULT NULL,
  `sexeUser` enum('M','F') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `particulier`
--

INSERT INTO `particulier` (`idUser`, `emailUser`, `mdpUser`, `nomUser`, `prenomUser`, `adresseUser`, `dateNaissanceUser`, `sexeUser`) VALUES
(28, 'michael@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Arch', 'Michael', '23 rue de la Paix', '2001-12-12', 'M'),
(29, 'ryles', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ryles', 'ryles', '23 rue de la Paix', '2022-12-12', 'M'),
(35, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'yasser', 'yasser', '23 rue de la Paix', '2010-12-21', 'M'),
(36, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'yasser', 'yasser', '23 rue de la Paix', '2010-12-21', 'M'),
(37, 'part@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'part', 'part', 'part', '2009-12-12', 'M'),
(38, 'uy@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uy', 'uy', 'uy', '2003-03-12', 'M');

--
-- Déclencheurs `particulier`
--
DELIMITER $$
CREATE TRIGGER `tInsertParticulier` BEFORE INSERT ON `particulier` FOR EACH ROW BEGIN
    IF NEW.idUser IS NULL OR NEW.idUser NOT IN (SELECT idUser FROM user) THEN
        SET NEW.idUser = IFNULL((SELECT MAX(idUser) FROM user), 0) + 1;
    END IF;
    INSERT INTO user (idUser, emailUser, mdpUser, adresseUser, roleUser)
    VALUES (NEW.idUser, NEW.emailUser, NEW.mdpUser, NEW.adresseUser, 'client');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tUpdateParticulier` BEFORE UPDATE ON `particulier` FOR EACH ROW BEGIN
    UPDATE user
    SET emailUser = NEW.emailUser,
        mdpUser = NEW.mdpUser,
        adresseUser = NEW.adresseUser
    WHERE idUser = NEW.idUser;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `idPromotion` int NOT NULL,
  `idLivre` int NOT NULL,
  `dateDebutPromotion` date NOT NULL,
  `dateFinPromotion` date NOT NULL,
  `prixPromotion` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`idPromotion`, `idLivre`, `dateDebutPromotion`, `dateFinPromotion`, `prixPromotion`) VALUES
(1, 3, '2025-01-05', '2025-01-20', 12.00),
(2, 6, '2025-02-01', '2025-02-15', 19.80),
(3, 1, '2025-03-01', '2025-03-10', 6.00),
(6, 2, '2025-02-02', '2025-02-10', 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `emailUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mdpUser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `adresseUser` varchar(50) NOT NULL,
  `roleUser` enum('admin','client') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `emailUser`, `mdpUser`, `adresseUser`, `roleUser`) VALUES
(1, 'ryles@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123 Rue des Lilas', 'admin'),
(2, 'jean@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '45 Avenue de la République', 'client'),
(12, 'm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '78 Boulevard Haussmann', 'client'),
(13, 'klza', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '9 Place de la Liberté', 'client'),
(14, 'poi', '123', '56 Rue Victor Hugo', 'client'),
(15, 'i', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '34 Avenue Montaigne', 'client'),
(23, 'chouaki@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '101 Rue Lafayette', 'client'),
(24, 'bo@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '202 Avenue des Champs-Élysées', 'client'),
(25, 'jean@gmail.com', 'motdepasse', '303 Boulevard Saint-Michel', 'client'),
(26, 'entreprise@gmail.com', 'motdepasse', '404 Rue de Rivoli', 'client'),
(27, 'entreprise@gmail.com', 'motdepasse', '505 Quai de la Tournelle', 'client'),
(28, 'michael@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '89 Impasse des Cerisiers', 'client'),
(29, 'ryles', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '102 Rue du Moulin', 'client'),
(30, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '76 Avenue des Rosiers', 'client'),
(31, 'entreprise@gmail.com', '123', '58 Chemin des Vignes', 'client'),
(32, 'test@gmail.com', '123', '21 Boulevard de la Liberté', 'client'),
(33, 'yass@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '34 Rue des Érables', 'client'),
(34, '987987@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '47 Allée des Chênes', 'client'),
(35, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '15 Route du Soleil', 'client'),
(36, 'yasser@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '63 Place du Marché', 'client'),
(37, 'part@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'part', 'client'),
(38, 'uy@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uy', 'client');

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
  ADD UNIQUE KEY `idlivre` (`idLivre`),
  ADD KEY `idLivre_2` (`idLivre`),
  ADD KEY `idLivre_3` (`idLivre`),
  ADD KEY `idCategorie` (`idCategorie`),
  ADD KEY `idMaisonEdition` (`idMaisonEdition`);

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
  ADD PRIMARY KEY (`idPromotion`),
  ADD KEY `idLivre` (`idLivre`);

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
  MODIFY `idAbonnement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `idAvis` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  MODIFY `idLigneCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=721;

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
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `idPromotion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`);

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
  ADD CONSTRAINT `livre_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`idCategorie`) ON DELETE SET NULL,
  ADD CONSTRAINT `livre_ibfk_2` FOREIGN KEY (`idMaisonEdition`) REFERENCES `maisonEdition` (`idMaisonEdition`) ON DELETE SET NULL;

--
-- Contraintes pour la table `particulier`
--
ALTER TABLE `particulier`
  ADD CONSTRAINT `particulier_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
