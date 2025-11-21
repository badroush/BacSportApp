-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 22 mai 2025 à 21:50
-- Version du serveur : 5.7.40
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bsdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `bareme`
--

DROP TABLE IF EXISTS `bareme`;
CREATE TABLE IF NOT EXISTS `bareme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epreuve_id` int(11) NOT NULL,
  `resultat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_320DB0EEAB990336` (`epreuve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `centre`
--

DROP TABLE IF EXISTS `centre`;
CREATE TABLE IF NOT EXISTS `centre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `centre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chefcentre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lycee_id` int(11) NOT NULL,
  `nom_classe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F87BF96D1DC61BF` (`lycee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250522213950', '2025-05-22 21:40:37', 919);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `cin` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `classe_id` int(11) NOT NULL,
  `lycee_id` int(11) NOT NULL,
  `nom_prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricule` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`cin`),
  KEY `IDX_ECA105F78F5EA509` (`classe_id`),
  KEY `IDX_ECA105F7D1DC61BF` (`lycee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `epreuve`
--

DROP TABLE IF EXISTS `epreuve`;
CREATE TABLE IF NOT EXISTS `epreuve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `epreuve_bac`
--

DROP TABLE IF EXISTS `epreuve_bac`;
CREATE TABLE IF NOT EXISTS `epreuve_bac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epreuve_id` int(11) NOT NULL,
  `eleve_cin` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B88AC851AB990336` (`epreuve_id`),
  KEY `IDX_B88AC851E554E62E` (`eleve_cin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

DROP TABLE IF EXISTS `etablissement`;
CREATE TABLE IF NOT EXISTS `etablissement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lycee`
--

DROP TABLE IF EXISTS `lycee`;
CREATE TABLE IF NOT EXISTS `lycee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `centre_id` int(11) NOT NULL,
  `etablissement_id` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_epreuve` date DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_epreuve` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E314FD0B463CD7C3` (`centre_id`),
  KEY `IDX_E314FD0BFF631228` (`etablissement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operation_bac`
--

DROP TABLE IF EXISTS `operation_bac`;
CREATE TABLE IF NOT EXISTS `operation_bac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `epreuve_id` int(11) NOT NULL,
  `resultat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `par` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_36EBA64A6CC7B2` (`eleve_id`),
  KEY `IDX_36EBA64AB990336` (`epreuve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement_id` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwdmodif` date NOT NULL,
  `cnrps` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E98D93D649` (`user`),
  UNIQUE KEY `UNIQ_1483A5E9720F9FFE` (`cnrps`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  KEY `IDX_1483A5E9FF631228` (`etablissement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bareme`
--
ALTER TABLE `bareme`
  ADD CONSTRAINT `FK_320DB0EEAB990336` FOREIGN KEY (`epreuve_id`) REFERENCES `epreuve` (`id`);

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `FK_8F87BF96D1DC61BF` FOREIGN KEY (`lycee_id`) REFERENCES `lycee` (`id`);

--
-- Contraintes pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD CONSTRAINT `FK_ECA105F78F5EA509` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`),
  ADD CONSTRAINT `FK_ECA105F7D1DC61BF` FOREIGN KEY (`lycee_id`) REFERENCES `lycee` (`id`);

--
-- Contraintes pour la table `epreuve_bac`
--
ALTER TABLE `epreuve_bac`
  ADD CONSTRAINT `FK_B88AC851AB990336` FOREIGN KEY (`epreuve_id`) REFERENCES `epreuve` (`id`),
  ADD CONSTRAINT `FK_B88AC851E554E62E` FOREIGN KEY (`eleve_cin`) REFERENCES `eleve` (`cin`);

--
-- Contraintes pour la table `lycee`
--
ALTER TABLE `lycee`
  ADD CONSTRAINT `FK_E314FD0B463CD7C3` FOREIGN KEY (`centre_id`) REFERENCES `centre` (`id`),
  ADD CONSTRAINT `FK_E314FD0BFF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`);

--
-- Contraintes pour la table `operation_bac`
--
ALTER TABLE `operation_bac`
  ADD CONSTRAINT `FK_36EBA64A6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`cin`),
  ADD CONSTRAINT `FK_36EBA64AB990336` FOREIGN KEY (`epreuve_id`) REFERENCES `epreuve` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
