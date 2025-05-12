-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : lun. 12 mai 2025 à 10:46
-- Version du serveur : 11.5.2-MariaDB
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stagiaires`
--

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
CREATE TABLE IF NOT EXISTS `filiere` (
  `idFiliere` int(4) NOT NULL AUTO_INCREMENT,
  `nomFiliere` varchar(50) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idFiliere`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`idFiliere`, `nomFiliere`, `niveau`) VALUES
(1, 'TSDI', 'TS'),
(2, 'TSGE', 'TS'),
(3, 'TGI', 'T'),
(4, 'TSRI', 'TS'),
(5, 'TCE', 'T'),
(6, 'EXCEPTION', 'M'),
(10, 'Filière zzzz Consulting', 'M');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `codeNiveau` varchar(2) NOT NULL,
  `libelleNiveau` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`codeNiveau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`codeNiveau`, `libelleNiveau`) VALUES
('L', 'Licence'),
('M', 'Master'),
('Q', 'Qualification'),
('T', 'Technicien'),
('TS', 'Technicien Spécialisé');

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire`
--

DROP TABLE IF EXISTS `stagiaire`;
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `idStagiaire` int(4) NOT NULL AUTO_INCREMENT,
  `idFiliere` int(4) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `civilite` varchar(1) DEFAULT NULL,
  `photoNom` varchar(100) DEFAULT NULL,
  `photoChemin` varchar(250) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(80) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `adresse_complement` varchar(255) DEFAULT NULL,
  `cin` varchar(10) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `inscription_numero` varchar(50) DEFAULT NULL,
  `inscription_date` date DEFAULT NULL,
  PRIMARY KEY (`idStagiaire`),
  KEY `idFiliere` (`idFiliere`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Déchargement des données de la table `stagiaire`
--

INSERT INTO `stagiaire` (`idStagiaire`, `idFiliere`, `nom`, `prenom`, `civilite`, `photoNom`, `photoChemin`, `date_naissance`, `lieu_naissance`, `adresse`, `adresse_complement`, `cin`, `tel`, `inscription_numero`, `inscription_date`) VALUES
(1, 1, 'SAADAOUI', 'MOHAMMED', 'M', 'Chrysantheme.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'CHAABI', 'OMAR', 'M', 'Desert.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'SALIM', 'RACHIDA', 'F', 'Hortensias.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'FAOUZI', 'NABILA', 'F', 'Meduses.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 2, 'ETTAOUSSI', 'KAMAL', 'M', 'Penguins.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 3, 'EZZAKI', 'ABDELKARIM', 'M', 'Tulipes.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 'SAADAOUI', 'MOHAMMED', 'M', 'Chrysantheme.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 2, 'CHAABI', 'OMAR', 'M', 'Desert.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 3, 'SALIM', 'RACHIDA', 'F', 'Hortensias.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'FAOUZI', 'NABILA', 'F', 'Meduses.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 2, 'ETTAOUSSI', 'KAMAL', 'M', 'Penguins.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 3, 'EZZAKI', 'ABDELKARIM', 'M', 'Tulipes.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'SAADAOUI', 'MOHAMMED', 'M', 'Chrysantheme.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, 'CHAABI', 'OMAR', 'M', 'Desert.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 3, 'SALIM', 'RACHIDA', 'F', 'Hortensias.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 1, 'FAOUZI', 'NABILA', 'F', 'Meduses.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 2, 'ETTAOUSSI', 'KAMAL', 'M', 'Penguins.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 3, 'EZZAKI', 'ABDELKARIM', 'M', 'Tulipes.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 6, 'VOZELLE', 'Thierry', 'M', NULL, NULL, '1970-07-14', 'LIMOGES', '11 RUE FRAGONARD 33520 BRUGES', 'Bât A, Appartement A.003, RES FRAGANCIA', 'CIN001', 'TEL001', '0100 870851520', '2025-05-10'),
(20, 1, 'Steinfeld', 'hailee', 'F', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 1, 'Hailee Steinfeld', 'hailee', 'F', 'hailee-steinfeld-01.jpeg', '../assets/images/20250506065614hailee-steinfeld-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 1, 'MOREAU', 'Laura', 'F', 'figue.png', '../assets/images/20250505151115figue.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 6, 'De Armas', 'Ana', 'F', 'Ana-de-Armas-01.jpeg', '../assets/images/20250505211407Ana-de-Armas-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 6, 'steinfeld', 'hailee', 'F', 'hailee-steinfeld-01.jpeg', '../assets/images/20250505211630hailee-steinfeld-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 6, 'Monot', 'Louise', 'F', 'louise-monot-01.jpeg', '../assets/images/20250505211912louise-monot-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 6, 'Thorborg Johansen', 'Kathrine', 'F', 'Kathrine-Thorborg-Johansen.jpeg', '../assets/images/20250505212840Kathrine-Thorborg-Johansen.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 6, 'Bonaventura', 'Flore', 'F', 'Flore_Bonaventura_01.jpg', '../assets/images/20250505213247Flore_Bonaventura_01.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 6, 'Coesens', 'Dounia', 'F', 'Dounia-coesens-01.jpeg', '../assets/images/20250505213428Dounia-coesens-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 6, 'stone', 'sharon', 'F', 'sharon-stone-01.jpeg', '../assets/images/20250506103824sharon-stone-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 6, 'biel', 'jessica', 'F', 'jessica-biel-01.jpeg', '../assets/images/20250506135645jessica-biel-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 6, 'gadot', 'gal', 'F', 'gal-gadot-01.jpeg', '../assets/images/20250506135904gal-gadot-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 6, 'grace-moretz', 'chloe', 'F', 'chloe-grace-moretz-01.jpeg', '../assets/images/20250506140109chloe-grace-moretz-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 6, 'Lipa', 'Dua', 'F', 'dua-lipa-01.jpeg', '../assets/images/20250506140505dua-lipa-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 6, 'Lady Gaga', 'stéphanie germanotta', 'F', 'lady-gaga-01.jpeg', '../assets/images/20250506225431lady-gaga-01.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `iduser` int(4) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'VISITEUR',
  `etat` int(1) NOT NULL DEFAULT 0,
  `pwd` varchar(255) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`iduser`, `login`, `email`, `role`, `etat`, `pwd`) VALUES
(2, 'user1', 'user1@gmail.com', 'VISITEUR', 0, '202cb962ac59075b964b07152d234b70'),
(4, 'thi.voz', 'thiiiiii.voz@gmail.com', 'ADMIN', 1, '4a7d1ed414474e4033ac29ccb8653d9b'),
(5, 'hailee.steinfeld', 'hailee.steinfeld@gmail.com', 'VISITEUR', 0, 'f43b39449585304ee42985def6c70b7c'),
(6, 'admin', 'thi.voz@gmail.com', 'ADMIN', 1, 'c93ccd78b2076528346216b3b2f701e6'),
(7, 'sandra.bullock', 'sandra.bullock@gmail.com', 'VISITEUR', 0, '4a7d1ed414474e4033ac29ccb8653d9b'),
(23, 'test', 'test@gmail.com', 'VISITEUR', 1, '098f6bcd4621d373cade4e832627b4f6');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_token`
--

DROP TABLE IF EXISTS `utilisateur_token`;
CREATE TABLE IF NOT EXISTS `utilisateur_token` (
  `iduser` int(4) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `isConnecter` int(11) DEFAULT NULL,
  `connexion_compteur` int(4) DEFAULT NULL,
  `connexion_derniere` timestamp NOT NULL,
  PRIMARY KEY (`iduser`),
  KEY `token` (`token`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

--
-- Déchargement des données de la table `utilisateur_token`
--

INSERT INTO `utilisateur_token` (`iduser`, `email`, `token`, `isConnecter`, `connexion_compteur`, `connexion_derniere`) VALUES
(6, 'thi.voz@gmail.com', '698f6aa001278c6c14a469b3a483d3fe', 0, 8, '2025-05-12 08:35:08');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `stagiaire`
--
ALTER TABLE `stagiaire`
  ADD CONSTRAINT `stagiaire_ibfk_1` FOREIGN KEY (`idFiliere`) REFERENCES `filiere` (`idFiliere`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
