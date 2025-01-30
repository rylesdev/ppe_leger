-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 30 jan. 2025 à 11:02
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `pOffrirLivre` (IN `p_idUser` INT, IN `chiffre` INT)   BEGIN
    DECLARE newIdCommande INT;
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
    IF chiffre = 5 THEN
        UPDATE livre
        SET prixLivre = 0
        WHERE idLivre = 6;
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, 6, 1);
        UPDATE livre
        SET prixLivre = 22.00
        WHERE idLivre = 6;
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
  `dateFinAbonnement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`idAbonnement`, `idUser`, `dateDebutAbonnement`, `dateFinAbonnement`) VALUES
(1, 2, '2025-01-01', '2025-12-31'),
(3, 23, '2025-01-25', '2025-04-25'),
(4, 15, '2025-01-26', '2025-02-28'),
(25, 24, '2025-01-29', '2025-02-28');

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
(25, 2, 'Crime et Chatiment', 15, 'surcôté', 3, '2025-01-29');

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
(410, '2025-01-30', 'expédiée', '2025-02-06', 15);

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
(667, 410, 6, 1);

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
CREATE TRIGGER `tInsertStockCommande` AFTER INSERT ON `ligneCommande` FOR EACH ROW begin
update livre
set exemplaireLivre = exemplaireLivre - NEW.quantiteLigneCommande
where idLivre = NEW.idLivre;
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
DELIMITER $$
CREATE TRIGGER `tUpdateStockCommande` AFTER UPDATE ON `ligneCommande` FOR EACH ROW begin
update livre
set exemplaireLivre = exemplaireLivre - (NEW.quantiteLigneCommande - OLD.quantiteLigneCommande)
where idLivre = NEW.idLivre;
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
  `categorieLivre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
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

INSERT INTO `livre` (`idLivre`, `nomLivre`, `categorieLivre`, `auteurLivre`, `imageLivre`, `exemplaireLivre`, `prixLivre`, `idCategorie`, `idMaisonEdition`) VALUES
(1, 'Alcools', 'test', 'Apollinaire', 'alcools.png', 99, 12.50, 3, 1),
(2, 'Crime et Chatiment', 'test', 'Dostoïevski', 'crime_et_chatiment.png', 1, 15.00, 1, 2),
(3, 'L\'Etranger', 'test', 'Camus', 'l_etranger.png', 67, 10.00, 1, 3),
(4, 'L\'Odyssée', 'test', 'Homère', 'l_odyssee.png', 89, 13.50, 2, 4),
(5, 'Les Fleurs du Mal', 'test', 'Baudelaire', 'les_fleurs_du_mal.png', 100, 14.00, 3, 5),
(6, 'PHP et MySQL pour les nuls', 'test', 'Valade', 'php_et_mysql_pour_les_nuls.png', 79, 22.00, 4, 6),
(7, 'Programmer en Java', 'test', 'Delannoy', 'programmer_en_java.png', 100, 25.00, 4, 7),
(8, 'SPQR', 'test', 'Beard', 'spqr.png', 99, 18.00, 2, 8),
(9, 'À la recherche du temps perdu', 'test', 'Proust', 'a_la_recherche_du_temps_perdu.png', 99, 0.00, 1, 1),
(10, 'Les Misérables', 'test', 'Hugo', 'les_miserables_I.png', 100, 0.00, 1, 2),
(11, '1984', 'test', 'Orwell', '1984.png', 100, 0.00, 1, 3),
(12, 'L\'Art d\'aimer', 'test', 'Ovide', 'l_art_d_aimer', 100, 0.00, 1, 4),
(13, 'La Peste', 'test', 'Camus', 'la_peste.png', 100, 15.99, 1, 1),
(14, 'Les Mémoires d\'Hadrien', 'test', 'Yourcenar', 'les_memoires_d_hadrien.png', 100, 12.99, 1, 1),
(15, 'La Condition humaine', 'test', 'Malraux', 'la_condition_humaine.png', 100, 14.99, 1, 1),
(16, 'Le Comte de Monte-Cristo', 'test', 'Dumas', 'le_comte_de_monte_cristo.png', 100, 9.99, 1, 2),
(17, 'Orgueil et Préjugés', 'test', 'Austen', 'orgueil_et_prejuges.png', 100, 8.99, 1, 2),
(18, 'Shining', 'test', 'King', 'shining.png', 100, 10.99, 1, 2),
(19, 'Bel-Ami', 'test', 'Maupassant', 'bel_ami.png', 100, 11.99, 1, 3),
(20, 'Fahrenheit 451', 'test', 'Bradbury', 'fahrenheit_451.png', 100, 9.99, 1, 3),
(21, 'La Nuit des temps', 'test', 'Barjavel', 'la_nuit_des_temps.png', 100, 12.99, 1, 3),
(22, 'L\'Énéide', 'test', 'Virgile', 'l_eneide.png', 100, 19.99, 3, 4),
(23, 'Les Pensées', 'test', 'Aurèle', 'les_pensees.png', 100, 18.99, 3, 4),
(24, 'Les Métamorphoses', 'test', 'Ovide', 'les_metamorphoses.png', 100, 20.99, 3, 4),
(25, 'Le Petit Livre des citations latines', 'test', 'Delamaire', 'le_petit_livre_des_citations_latines.png', 100, 7.99, 3, 6),
(43, 'Le Petit Livre des grandes coïncidences', 'test', 'Chiflet', 'le_petit_livre_des_grandes_coincidences.png', 100, 7.99, 3, 6),
(44, 'Le Petit Livre des gros mensonges', 'test', 'Chiflet', 'le_petit_livre_des_gros_mensonges.png', 100, 7.99, 3, 6),
(45, 'L\'Art de la guerre', 'test', 'Sun', 'l_art_de_la_guerre.png', 100, 12.99, 2, 7),
(46, 'Apprendre à dessiner', 'test', 'Edwards', 'apprendre_a_dessiner.png', 100, 14.99, 4, 7),
(47, 'Le Lean Startup', 'test', 'Ries', 'le_lean_startup.png', 100, 16.99, 4, 7),
(48, 'Les Templiers', 'test', 'Demurger', 'les_templiers.png', 100, 18.99, 2, 8),
(49, 'La Seconde Guerre mondiale', 'test', 'Beevor', 'la_seconde_guerre_mondiale.png', 100, 19.99, 2, 8),
(50, 'Napoléon : Une ambition française', 'test', 'Tulard', 'napoleon_une_ambition_francaise.png', 100, 20.99, 2, 8);

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
(1, 3, '2025-01-05', '2025-01-20', 9.00),
(2, 6, '2025-02-01', '2025-02-15', 19.80),
(3, 1, '2025-03-01', '2025-03-10', 11.25);

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
(2, 'Dubois', 'Jean', 'jean@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Bois de Vincennes', 'client', NULL),
(12, 'marie', 'm', 'm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', NULL),
(13, 'jean', 'valjean', 'klza', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', NULL),
(14, 'poi', 'poi', 'poi', '123', '', 'client', '2025-01-08'),
(15, 'tAbonnement', 'insert', 'i', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 'client', '2025-01-09'),
(23, 'chouaki', 'chouaki', 'chouaki@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'chouaki', 'client', '2025-01-23'),
(24, 'abo', 'bo', 'bo@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'bo', 'client', '2025-01-29');

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
`idCommande` int
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotallivreenattente`  AS SELECT `li`.`idCommande` AS `idCommande`, `c`.`idUser` AS `idUser`, `l`.`nomLivre` AS `nomLivre`, `l`.`prixLivre` AS `prixLivre`, `li`.`quantiteLigneCommande` AS `quantiteLigneCommande`, (`l`.`prixLivre` * `li`.`quantiteLigneCommande`) AS `totalLivre` FROM ((`livre` `l` join `lignecommande` `li` on((`l`.`idLivre` = `li`.`idLivre`))) join `commande` `c` on((`c`.`idCommande` = `li`.`idCommande`))) WHERE (`c`.`statutCommande` = 'en attente') ;

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
  MODIFY `idCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=411;

--
-- AUTO_INCREMENT pour la table `ligneCommande`
--
ALTER TABLE `ligneCommande`
  MODIFY `idLigneCommande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=668;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `idLivre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `maisonEdition`
--
ALTER TABLE `maisonEdition`
  MODIFY `idMaisonEdition` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `idPromotion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;

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
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`idLivre`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
