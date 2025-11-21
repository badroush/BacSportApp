-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 20 mai 2025 à 13:13
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gab`
--

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

DROP TABLE IF EXISTS `absence`;
CREATE TABLE IF NOT EXISTS `absence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcadre` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tabsence` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `datedb` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `datefn` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `actualite`
--

DROP TABLE IF EXISTS `actualite`;
CREATE TABLE IF NOT EXISTS `actualite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  `section` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adhesion`
--

DROP TABLE IF EXISTS `adhesion`;
CREATE TABLE IF NOT EXISTS `adhesion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomp` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `datnais` date NOT NULL,
  `lieunais` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mj` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `etablissement` int(11) NOT NULL,
  `mobile` int(20) NOT NULL,
  `pwdmodif` date NOT NULL,
  `cnrps` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cnrps` (`cnrps`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `arrive`
--

DROP TABLE IF EXISTS `arrive`;
CREATE TABLE IF NOT EXISTS `arrive` (
  `identarr` int(11) NOT NULL AUTO_INCREMENT,
  `id_arr` int(11) NOT NULL,
  `date_arr` date NOT NULL,
  `num_bord` varchar(20) NOT NULL,
  `date_bord` date DEFAULT '1970-01-01',
  `source_bord` varchar(250) NOT NULL,
  `sujet` text NOT NULL,
  `remarque` text NOT NULL,
  `dest` varchar(8) NOT NULL,
  PRIMARY KEY (`identarr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `unite` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bareme`
--

DROP TABLE IF EXISTS `bareme`;
CREATE TABLE IF NOT EXISTS `bareme` (
  `idbarem` int(11) NOT NULL AUTO_INCREMENT,
  `idspecialite` int(11) NOT NULL,
  `resultat` varchar(10) NOT NULL,
  `note` varchar(10) NOT NULL,
  `sex` varchar(1) NOT NULL,
  PRIMARY KEY (`idbarem`),
  KEY `c47` (`idspecialite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `budget`
--

DROP TABLE IF EXISTS `budget`;
CREATE TABLE IF NOT EXISTS `budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtit` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `etab` int(11) NOT NULL,
  `mont` double NOT NULL,
  `anne` int(4) NOT NULL,
  `par` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `budgetachat`
--

DROP TABLE IF EXISTS `budgetachat`;
CREATE TABLE IF NOT EXISTS `budgetachat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mjcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `titcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `montant` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anne` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `budget_etat`
--

DROP TABLE IF EXISTS `budget_etat`;
CREATE TABLE IF NOT EXISTS `budget_etat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mjs` int(11) NOT NULL,
  `etat` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cartenotesmehni`
--

DROP TABLE IF EXISTS `cartenotesmehni`;
CREATE TABLE IF NOT EXISTS `cartenotesmehni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `matricule` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `etatadm` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `remarque` text COLLATE utf8_unicode_ci NOT NULL,
  `evaluation` text COLLATE utf8_unicode_ci NOT NULL,
  `qnttrv` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mnrtrv` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `forme` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `courage` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `point` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matricule` (`matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cartenotestakyim`
--

DROP TABLE IF EXISTS `cartenotestakyim`;
CREATE TABLE IF NOT EXISTS `cartenotestakyim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `matricule` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `semestre` int(11) NOT NULL,
  `evaluation` text COLLATE utf8_unicode_ci NOT NULL,
  `retard` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `maladie` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sanssolde` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `absnv` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qtetrv` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qlttrv` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reguliere` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matricule` (`matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `centre`
--

DROP TABLE IF EXISTS `centre`;
CREATE TABLE IF NOT EXISTS `centre` (
  `idcentre` int(11) NOT NULL AUTO_INCREMENT,
  `centre` varchar(200) NOT NULL,
  `chefcentre` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`idcentre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `chauffeur`
--

DROP TABLE IF EXISTS `chauffeur`;
CREATE TABLE IF NOT EXISTS `chauffeur` (
  `code` varchar(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `chira`
--

DROP TABLE IF EXISTS `chira`;
CREATE TABLE IF NOT EXISTS `chira` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtit` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idprod` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prix` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `chira2t`
--

DROP TABLE IF EXISTS `chira2t`;
CREATE TABLE IF NOT EXISTS `chira2t` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idprod` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idtit` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idmj` int(10) NOT NULL,
  `qte` double NOT NULL,
  `prix` varchar(10) NOT NULL,
  `year` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `idclasse` int(11) NOT NULL AUTO_INCREMENT,
  `classe` varchar(50) NOT NULL,
  `idlycee` int(11) NOT NULL,
  PRIMARY KEY (`idclasse`),
  KEY `c32` (`idlycee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commissariat`
--

DROP TABLE IF EXISTS `commissariat`;
CREATE TABLE IF NOT EXISTS `commissariat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commissariat` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compare`
--

DROP TABLE IF EXISTS `compare`;
CREATE TABLE IF NOT EXISTS `compare` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `datecprix` date NOT NULL,
  `fasl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conge`
--

DROP TABLE IF EXISTS `conge`;
CREATE TABLE IF NOT EXISTS `conge` (
  `ref` int(5) NOT NULL AUTO_INCREMENT,
  `tpcg` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `connected`
--

DROP TABLE IF EXISTS `connected`;
CREATE TABLE IF NOT EXISTS `connected` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

DROP TABLE IF EXISTS `connexion`;
CREATE TABLE IF NOT EXISTS `connexion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  `ipadr` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deconx`
--

DROP TABLE IF EXISTS `deconx`;
CREATE TABLE IF NOT EXISTS `deconx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  `ipadr` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `ref` int(5) NOT NULL AUTO_INCREMENT,
  `tc` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cnrps` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `du` date NOT NULL,
  `au` date NOT NULL,
  `ann` int(4) NOT NULL,
  `etat` varchar(20) NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `cnrps` (`cnrps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demandepapier`
--

DROP TABLE IF EXISTS `demandepapier`;
CREATE TABLE IF NOT EXISTS `demandepapier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dates` date NOT NULL,
  `matricule` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `papier` int(11) NOT NULL,
  `autre` text COLLATE utf8_unicode_ci NOT NULL,
  `etat` int(11) NOT NULL,
  `teslim` int(11) NOT NULL,
  `dateteslim` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demande_jeune`
--

DROP TABLE IF EXISTS `demande_jeune`;
CREATE TABLE IF NOT EXISTS `demande_jeune` (
  `ref` int(5) NOT NULL AUTO_INCREMENT,
  `tc` int(3) NOT NULL,
  `cnrps` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `du` date NOT NULL,
  `au` date NOT NULL,
  `ann` int(4) NOT NULL,
  `etat` int(2) NOT NULL,
  `vu` int(11) NOT NULL,
  `rq` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `cnrps` (`cnrps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depart`
--

DROP TABLE IF EXISTS `depart`;
CREATE TABLE IF NOT EXISTS `depart` (
  `identdep` int(11) NOT NULL AUTO_INCREMENT,
  `id_dep` int(10) NOT NULL,
  `date_dep` date NOT NULL,
  `sujet` varchar(250) NOT NULL,
  `destinateur` varchar(250) NOT NULL,
  `remarque` text NOT NULL,
  PRIMARY KEY (`identdep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

DROP TABLE IF EXISTS `depense`;
CREATE TABLE IF NOT EXISTS `depense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etab` int(11) NOT NULL,
  `titre` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fourn` int(11) NOT NULL,
  `nfac` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dfac` date NOT NULL,
  `mont` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `resp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `desg`
--

DROP TABLE IF EXISTS `desg`;
CREATE TABLE IF NOT EXISTS `desg` (
  `coded` int(11) NOT NULL AUTO_INCREMENT,
  `codev` int(11) NOT NULL,
  `codec` varchar(10) NOT NULL,
  `montant` varchar(20) NOT NULL,
  PRIMARY KEY (`coded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detail1`
--

DROP TABLE IF EXISTS `detail1`;
CREATE TABLE IF NOT EXISTS `detail1` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cpt` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nvp` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mvp` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pvp` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ncg` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rvp` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vad` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vp` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detail2`
--

DROP TABLE IF EXISTS `detail2`;
CREATE TABLE IF NOT EXISTS `detail2` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `nm` varchar(255) NOT NULL,
  `dm` varchar(255) NOT NULL,
  `tdp` varchar(255) NOT NULL,
  `tar` varchar(255) NOT NULL,
  `df` varchar(255) NOT NULL,
  `dt` varchar(255) NOT NULL,
  `prd` varchar(255) NOT NULL,
  `mj` varchar(255) NOT NULL,
  `st` varchar(255) NOT NULL,
  `dr1` varchar(255) NOT NULL,
  `dr2` varchar(255) NOT NULL,
  `p1k` varchar(20) NOT NULL,
  `mtk` varchar(255) NOT NULL,
  `tik` varchar(255) NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detmawred`
--

DROP TABLE IF EXISTS `detmawred`;
CREATE TABLE IF NOT EXISTS `detmawred` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idmw` varchar(12) NOT NULL,
  `idmj` int(11) NOT NULL,
  `mont` double NOT NULL,
  `anne` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dettravaux`
--

DROP TABLE IF EXISTS `dettravaux`;
CREATE TABLE IF NOT EXISTS `dettravaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `centre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `titre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `dateemb` date NOT NULL,
  `datecop` date NOT NULL,
  `raisoncop` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `situation` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idtravaux` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devischirareel`
--

DROP TABLE IF EXISTS `devischirareel`;
CREATE TABLE IF NOT EXISTS `devischirareel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtit` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `article` int(11) NOT NULL,
  `prix` double NOT NULL,
  `anne` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `directeur`
--

DROP TABLE IF EXISTS `directeur`;
CREATE TABLE IF NOT EXISTS `directeur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `idetab` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discussion_tel`
--

DROP TABLE IF EXISTS `discussion_tel`;
CREATE TABLE IF NOT EXISTS `discussion_tel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dispense`
--

DROP TABLE IF EXISTS `dispense`;
CREATE TABLE IF NOT EXISTS `dispense` (
  `iddispense` int(11) NOT NULL AUTO_INCREMENT,
  `cin` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(1) NOT NULL,
  PRIMARY KEY (`iddispense`),
  KEY `c19` (`cin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `cin` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `np` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idclasse` int(11) NOT NULL,
  `idlycee` int(11) NOT NULL,
  `matricule` varchar(10) NOT NULL,
  PRIMARY KEY (`cin`),
  KEY `c31` (`idlycee`),
  KEY `c21` (`idclasse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve_acces`
--

DROP TABLE IF EXISTS `eleve_acces`;
CREATE TABLE IF NOT EXISTS `eleve_acces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ideleve` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `heure` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entremagasin`
--

DROP TABLE IF EXISTS `entremagasin`;
CREATE TABLE IF NOT EXISTS `entremagasin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `quantite` decimal(20,0) NOT NULL,
  `fournisseur` int(11) NOT NULL,
  `date` date NOT NULL,
  `idsecteur` int(11) NOT NULL,
  `codinvfact` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entremagasin_ibfk_1` (`article`),
  KEY `entremagasin_ibfk_2` (`fournisseur`),
  KEY `idsecteur` (`idsecteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `epreuve_bac`
--

DROP TABLE IF EXISTS `epreuve_bac`;
CREATE TABLE IF NOT EXISTS `epreuve_bac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ep` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `id_eleve` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

DROP TABLE IF EXISTS `etablissement`;
CREATE TABLE IF NOT EXISTS `etablissement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

DROP TABLE IF EXISTS `fonction`;
CREATE TABLE IF NOT EXISTS `fonction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fonction` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `forn`
--

DROP TABLE IF EXISTS `forn`;
CREATE TABLE IF NOT EXISTS `forn` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idcprix` int(11) NOT NULL,
  `fournisseur` int(11) NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `idcprix` (`idcprix`),
  KEY `fournisseur` (`fournisseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fournisseur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mf` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `geryjelsa`
--

DROP TABLE IF EXISTS `geryjelsa`;
CREATE TABLE IF NOT EXISTS `geryjelsa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `titre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gouvernerat`
--

DROP TABLE IF EXISTS `gouvernerat`;
CREATE TABLE IF NOT EXISTS `gouvernerat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gouvernerat` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `infos`
--

DROP TABLE IF EXISTS `infos`;
CREATE TABLE IF NOT EXISTS `infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlycee` int(11) NOT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inspection`
--

DROP TABLE IF EXISTS `inspection`;
CREATE TABLE IF NOT EXISTS `inspection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlycee` int(11) NOT NULL,
  `contenu` text COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

DROP TABLE IF EXISTS `inventaire`;
CREATE TABLE IF NOT EXISTS `inventaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `mjs` int(11) NOT NULL,
  `article` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `qte` double NOT NULL,
  `remq` text COLLATE utf8_unicode_ci NOT NULL,
  `categorie` int(11) NOT NULL,
  `ns` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(4) NOT NULL,
  `etat` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire1`
--

DROP TABLE IF EXISTS `inventaire1`;
CREATE TABLE IF NOT EXISTS `inventaire1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `mjs` int(11) NOT NULL,
  `article` text COLLATE utf8_unicode_ci NOT NULL,
  `qte` double NOT NULL,
  `remq` text COLLATE utf8_unicode_ci NOT NULL,
  `categorie` int(11) NOT NULL,
  `ns` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jam3iya`
--

DROP TABLE IF EXISTS `jam3iya`;
CREATE TABLE IF NOT EXISTS `jam3iya` (
  `code` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `responsable` varchar(100) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lastupdatesys`
--

DROP TABLE IF EXISTS `lastupdatesys`;
CREATE TABLE IF NOT EXISTS `lastupdatesys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateupd` datetime NOT NULL,
  `tache` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liaisoncp`
--

DROP TABLE IF EXISTS `liaisoncp`;
CREATE TABLE IF NOT EXISTS `liaisoncp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cprix` int(11) NOT NULL,
  `mahdhar` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne`
--

DROP TABLE IF EXISTS `ligne`;
CREATE TABLE IF NOT EXISTS `ligne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tcapacite` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rcapacite` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `idetab` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `listedemandepapier`
--

DROP TABLE IF EXISTS `listedemandepapier`;
CREATE TABLE IF NOT EXISTS `listedemandepapier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valeur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lycee`
--

DROP TABLE IF EXISTS `lycee`;
CREATE TABLE IF NOT EXISTS `lycee` (
  `idlycee` int(11) NOT NULL AUTO_INCREMENT,
  `lycee` varchar(50) NOT NULL,
  `idcentre` int(11) NOT NULL,
  `dateepreuve` date DEFAULT NULL,
  `idetablissement` varchar(5) NOT NULL,
  `type` varchar(50) NOT NULL,
  `heurepreuve` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idlycee`),
  KEY `c43` (`idcentre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mahdhfourn`
--

DROP TABLE IF EXISTS `mahdhfourn`;
CREATE TABLE IF NOT EXISTS `mahdhfourn` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idmahdhar` int(11) NOT NULL,
  `fournisseur` varchar(255) NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mahdhgery`
--

DROP TABLE IF EXISTS `mahdhgery`;
CREATE TABLE IF NOT EXISTS `mahdhgery` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idmahdh` int(11) NOT NULL,
  `idgery` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mahdhjelsa1`
--

DROP TABLE IF EXISTS `mahdhjelsa1`;
CREATE TABLE IF NOT EXISTS `mahdhjelsa1` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `datecre` varchar(10) NOT NULL,
  `fasl` varchar(255) NOT NULL,
  `datejelsa` varchar(10) NOT NULL,
  `hjelsa` varchar(5) NOT NULL,
  `nbf` int(5) NOT NULL,
  `datelimite` varchar(10) NOT NULL,
  `nbd` int(5) NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mahdhselction`
--

DROP TABLE IF EXISTS `mahdhselction`;
CREATE TABLE IF NOT EXISTS `mahdhselction` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idmahdh` int(11) NOT NULL,
  `idforn` int(11) NOT NULL,
  `prix` double NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mawared`
--

DROP TABLE IF EXISTS `mawared`;
CREATE TABLE IF NOT EXISTS `mawared` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(12) NOT NULL,
  `nmwr` varchar(100) NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `msg` text COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ministere`
--

DROP TABLE IF EXISTS `ministere`;
CREATE TABLE IF NOT EXISTS `ministere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ministere` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modem`
--

DROP TABLE IF EXISTS `modem`;
CREATE TABLE IF NOT EXISTS `modem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcontrat` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `idetab` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `morasla`
--

DROP TABLE IF EXISTS `morasla`;
CREATE TABLE IF NOT EXISTS `morasla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'بدون عنوان',
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `objet` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `heur` time NOT NULL,
  `vue` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nationalite`
--

DROP TABLE IF EXISTS `nationalite`;
CREATE TABLE IF NOT EXISTS `nationalite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nationalite` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idtypenationalite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idtypenationalite` (`idtypenationalite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nbconge`
--

DROP TABLE IF EXISTS `nbconge`;
CREATE TABLE IF NOT EXISTS `nbconge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cnrps` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tpcng` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nbj` float NOT NULL,
  `reste` float NOT NULL,
  `ann` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cnrps` (`cnrps`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `titre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `editeur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `idoperation` int(11) NOT NULL AUTO_INCREMENT,
  `cin` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idspecialite` int(11) NOT NULL,
  `resultat` varchar(10) NOT NULL,
  `note` varchar(10) NOT NULL,
  `par` varchar(10) NOT NULL,
  PRIMARY KEY (`idoperation`),
  KEY `cin` (`cin`),
  KEY `c58` (`idspecialite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `operation_logement`
--

DROP TABLE IF EXISTS `operation_logement`;
CREATE TABLE IF NOT EXISTS `operation_logement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idgroupe` int(11) NOT NULL,
  `idtypenationalite` int(11) NOT NULL,
  `idnationalite` int(11) NOT NULL,
  `idresident` int(11) NOT NULL,
  `idservice` int(11) NOT NULL,
  `nb_femme` int(11) DEFAULT NULL,
  `nb_homme` int(11) DEFAULT NULL,
  `nb_nuit` int(11) NOT NULL,
  `createdAt` date NOT NULL,
  `createdBy` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `num_wasl` int(11) NOT NULL,
  `etab` int(11) NOT NULL,
  `montant` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `steg` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `velo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restaurant` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `station` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `etab` (`etab`),
  KEY `idgroupe` (`idgroupe`),
  KEY `idtypenationalite` (`idtypenationalite`),
  KEY `idnationalite` (`idnationalite`),
  KEY `idresident` (`idresident`),
  KEY `idservice` (`idservice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `page_role`
--

DROP TABLE IF EXISTS `page_role`;
CREATE TABLE IF NOT EXISTS `page_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `page` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nompage` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panne`
--

DROP TABLE IF EXISTS `panne`;
CREATE TABLE IF NOT EXISTS `panne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datep` date NOT NULL,
  `daterp` date NOT NULL,
  `reclamation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `idetab` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `papier`
--

DROP TABLE IF EXISTS `papier`;
CREATE TABLE IF NOT EXISTS `papier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fichier` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permission`
--

DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `page` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personel`
--

DROP TABLE IF EXISTS `personel`;
CREATE TABLE IF NOT EXISTS `personel` (
  `ncnrps` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cin` varchar(8) NOT NULL,
  `np` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dn` varchar(15) NOT NULL,
  `grd` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fonction` varchar(200) NOT NULL,
  `lt` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adm` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datemb` varchar(15) NOT NULL,
  `dateters` varchar(15) NOT NULL,
  `btar` varchar(10) NOT NULL,
  `remarque` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `types` varchar(11) NOT NULL,
  `pere` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gpere` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lieu` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) NOT NULL,
  `lastcert` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nbetude` varchar(5) NOT NULL,
  `nvetude` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `caisse` varchar(20) NOT NULL,
  `epouse` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `profepouse` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `etcivil` varchar(11) NOT NULL,
  `etciviltravail` varchar(11) NOT NULL,
  `ettravail` varchar(11) NOT NULL,
  `gov` varchar(11) NOT NULL,
  `ministere` varchar(11) NOT NULL,
  `mission` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(11) NOT NULL,
  PRIMARY KEY (`ncnrps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `personel_pointage`
--

DROP TABLE IF EXISTS `personel_pointage`;
CREATE TABLE IF NOT EXISTS `personel_pointage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idu` (`idu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

DROP TABLE IF EXISTS `presence`;
CREATE TABLE IF NOT EXISTS `presence` (
  `idp` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadre` int(11) NOT NULL,
  `dt_entre` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hr_entre` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `ids` int(11) NOT NULL,
  PRIMARY KEY (`idp`),
  KEY `presence_ibfk_1` (`id_cadre`),
  KEY `ids` (`ids`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

DROP TABLE IF EXISTS `prix`;
CREATE TABLE IF NOT EXISTS `prix` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idcprix` int(11) NOT NULL,
  `idproduit` int(11) NOT NULL,
  `idforn` int(11) NOT NULL,
  `punitaire` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `conformite` int(2) NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `idcprix` (`idcprix`),
  KEY `idproduit` (`idproduit`),
  KEY `idforn` (`idforn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prix_service`
--

DROP TABLE IF EXISTS `prix_service`;
CREATE TABLE IF NOT EXISTS `prix_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_groupe` int(11) NOT NULL,
  `type_nationalite` int(11) NOT NULL,
  `id_nationalite` int(11) NOT NULL,
  `id_resident` int(11) NOT NULL,
  `id_service` int(11) DEFAULT NULL,
  `montant` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_service` (`id_service`),
  KEY `id_nationalite` (`id_nationalite`),
  KEY `id_resident` (`id_resident`),
  KEY `type_nationalite` (`type_nationalite`),
  KEY `type_groupe` (`type_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idcprix` int(11) NOT NULL,
  `desg` text COLLATE utf8_unicode_ci NOT NULL,
  `qte` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `etatvue` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ref`),
  KEY `idcprix` (`idcprix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomregion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `qrcoderegion` int(150) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reglement`
--

DROP TABLE IF EXISTS `reglement`;
CREATE TABLE IF NOT EXISTS `reglement` (
  `coder` int(11) NOT NULL AUTO_INCREMENT,
  `coded` int(11) NOT NULL,
  PRIMARY KEY (`coder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `remarqe_reponse`
--

DROP TABLE IF EXISTS `remarqe_reponse`;
CREATE TABLE IF NOT EXISTS `remarqe_reponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idreq` int(11) NOT NULL,
  `reponse` text COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `remarque`
--

DROP TABLE IF EXISTS `remarque`;
CREATE TABLE IF NOT EXISTS `remarque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `req_txt` text COLLATE utf8_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  `daterq` date NOT NULL,
  `timerq` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `remise`
--

DROP TABLE IF EXISTS `remise`;
CREATE TABLE IF NOT EXISTS `remise` (
  `idremise` int(11) NOT NULL AUTO_INCREMENT,
  `idcompare` int(11) NOT NULL,
  `idforn` int(11) NOT NULL,
  `montant` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idremise`),
  KEY `idcompare` (`idcompare`),
  KEY `idforn` (`idforn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seance`
--

DROP TABLE IF EXISTS `seance`;
CREATE TABLE IF NOT EXISTS `seance` (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `valseance` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hrdb` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `hrfn` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `idts` int(11) NOT NULL,
  PRIMARY KEY (`ids`),
  KEY `idts` (`idts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `secteurmagasin`
--

DROP TABLE IF EXISTS `secteurmagasin`;
CREATE TABLE IF NOT EXISTS `secteurmagasin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secteur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `selection`
--

DROP TABLE IF EXISTS `selection`;
CREATE TABLE IF NOT EXISTS `selection` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idcprix` int(11) NOT NULL,
  `idforn` int(11) NOT NULL,
  `prix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ref`),
  KEY `idcprix` (`idcprix`),
  KEY `idforn` (`idforn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `selseance`
--

DROP TABLE IF EXISTS `selseance`;
CREATE TABLE IF NOT EXISTS `selseance` (
  `idss` int(11) NOT NULL AUTO_INCREMENT,
  `selection` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idss`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logement` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `signatures`
--

DROP TABLE IF EXISTS `signatures`;
CREATE TABLE IF NOT EXISTS `signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `signature` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

DROP TABLE IF EXISTS `sortie`;
CREATE TABLE IF NOT EXISTS `sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datesortie` date NOT NULL,
  `lieusortie` int(11) NOT NULL,
  `descripsortie` text COLLATE utf8_unicode_ci NOT NULL,
  `remqsortie` text COLLATE utf8_unicode_ci NOT NULL,
  `technicien` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortiemagasin`
--

DROP TABLE IF EXISTS `sortiemagasin`;
CREATE TABLE IF NOT EXISTS `sortiemagasin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `quantite` float NOT NULL,
  `receveur` int(11) NOT NULL,
  `date` date NOT NULL,
  `remarque` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`),
  KEY `receveur` (`receveur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specialite`
--

DROP TABLE IF EXISTS `specialite`;
CREATE TABLE IF NOT EXISTS `specialite` (
  `idspecialite` int(11) NOT NULL AUTO_INCREMENT,
  `specialite` varchar(30) NOT NULL,
  PRIMARY KEY (`idspecialite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `standard`
--

DROP TABLE IF EXISTS `standard`;
CREATE TABLE IF NOT EXISTS `standard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `tel` int(20) NOT NULL,
  `fax` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

DROP TABLE IF EXISTS `taches`;
CREATE TABLE IF NOT EXISTS `taches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `technicien`
--

DROP TABLE IF EXISTS `technicien`;
CREATE TABLE IF NOT EXISTS `technicien` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `grade` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idetab` int(11) NOT NULL,
  `photo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `testsortie`
--

DROP TABLE IF EXISTS `testsortie`;
CREATE TABLE IF NOT EXISTS `testsortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `quantite` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `titrej`
--

DROP TABLE IF EXISTS `titrej`;
CREATE TABLE IF NOT EXISTS `titrej` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtit` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ntit` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tracing`
--

DROP TABLE IF EXISTS `tracing`;
CREATE TABLE IF NOT EXISTS `tracing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idconnecte` int(11) NOT NULL,
  `date` date NOT NULL,
  `heur` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `travaux`
--

DROP TABLE IF EXISTS `travaux`;
CREATE TABLE IF NOT EXISTS `travaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricule` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matricule` (`matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typeseance`
--

DROP TABLE IF EXISTS `typeseance`;
CREATE TABLE IF NOT EXISTS `typeseance` (
  `idts` int(11) NOT NULL AUTO_INCREMENT,
  `valts` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_groupe`
--

DROP TABLE IF EXISTS `type_groupe`;
CREATE TABLE IF NOT EXISTS `type_groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typegroupe` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_nationalite`
--

DROP TABLE IF EXISTS `type_nationalite`;
CREATE TABLE IF NOT EXISTS `type_nationalite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typenationalite` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_resident`
--

DROP TABLE IF EXISTS `type_resident`;
CREATE TABLE IF NOT EXISTS `type_resident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resident` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_user`
--

DROP TABLE IF EXISTS `type_user`;
CREATE TABLE IF NOT EXISTS `type_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `under_construction`
--

DROP TABLE IF EXISTS `under_construction`;
CREATE TABLE IF NOT EXISTS `under_construction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etat` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voyage`
--

DROP TABLE IF EXISTS `voyage`;
CREATE TABLE IF NOT EXISTS `voyage` (
  `codev` int(11) NOT NULL AUTO_INCREMENT,
  `codej` int(11) NOT NULL,
  `raison` varchar(255) NOT NULL,
  `lieu` varchar(50) NOT NULL,
  `date` varchar(10) NOT NULL,
  `anne` varchar(4) NOT NULL,
  PRIMARY KEY (`codev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bareme`
--
ALTER TABLE `bareme`
  ADD CONSTRAINT `c47` FOREIGN KEY (`idspecialite`) REFERENCES `specialite` (`idspecialite`);

--
-- Contraintes pour la table `cartenotesmehni`
--
ALTER TABLE `cartenotesmehni`
  ADD CONSTRAINT `cartenotesmehni_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cartenotestakyim`
--
ALTER TABLE `cartenotestakyim`
  ADD CONSTRAINT `cartenotestakyim_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `c32` FOREIGN KEY (`idlycee`) REFERENCES `lycee` (`idlycee`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `demande_jeune`
--
ALTER TABLE `demande_jeune`
  ADD CONSTRAINT `demande_jeune_ibfk_1` FOREIGN KEY (`cnrps`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `detail2`
--
ALTER TABLE `detail2`
  ADD CONSTRAINT `detail2_ibfk_1` FOREIGN KEY (`id`) REFERENCES `detail1` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dispense`
--
ALTER TABLE `dispense`
  ADD CONSTRAINT `dispense_ibfk_1` FOREIGN KEY (`cin`) REFERENCES `eleve` (`cin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `entremagasin`
--
ALTER TABLE `entremagasin`
  ADD CONSTRAINT `entremagasin_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entremagasin_ibfk_2` FOREIGN KEY (`fournisseur`) REFERENCES `fournisseur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `forn`
--
ALTER TABLE `forn`
  ADD CONSTRAINT `forn_ibfk_1` FOREIGN KEY (`idcprix`) REFERENCES `compare` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `forn_ibfk_2` FOREIGN KEY (`fournisseur`) REFERENCES `fournisseur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `lycee`
--
ALTER TABLE `lycee`
  ADD CONSTRAINT `c43` FOREIGN KEY (`idcentre`) REFERENCES `centre` (`idcentre`);

--
-- Contraintes pour la table `nationalite`
--
ALTER TABLE `nationalite`
  ADD CONSTRAINT `nationalite_ibfk_1` FOREIGN KEY (`idtypenationalite`) REFERENCES `type_nationalite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `nbconge`
--
ALTER TABLE `nbconge`
  ADD CONSTRAINT `nbcpers` FOREIGN KEY (`cnrps`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `c58` FOREIGN KEY (`idspecialite`) REFERENCES `specialite` (`idspecialite`),
  ADD CONSTRAINT `operation_ibfk_1` FOREIGN KEY (`cin`) REFERENCES `eleve` (`cin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`type`) REFERENCES `taches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `personel_pointage`
--
ALTER TABLE `personel_pointage`
  ADD CONSTRAINT `personel_pointage_ibfk_1` FOREIGN KEY (`idu`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `presence`
--
ALTER TABLE `presence`
  ADD CONSTRAINT `presence_ibfk_2` FOREIGN KEY (`ids`) REFERENCES `seance` (`ids`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `prix`
--
ALTER TABLE `prix`
  ADD CONSTRAINT `prix_ibfk_1` FOREIGN KEY (`idcprix`) REFERENCES `compare` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prix_ibfk_2` FOREIGN KEY (`idproduit`) REFERENCES `produit` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `prix_service`
--
ALTER TABLE `prix_service`
  ADD CONSTRAINT `prix_service_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prix_service_ibfk_2` FOREIGN KEY (`id_nationalite`) REFERENCES `nationalite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prix_service_ibfk_3` FOREIGN KEY (`id_resident`) REFERENCES `type_resident` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prix_service_ibfk_4` FOREIGN KEY (`type_groupe`) REFERENCES `type_groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prix_service_ibfk_5` FOREIGN KEY (`type_nationalite`) REFERENCES `type_nationalite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idcprix`) REFERENCES `compare` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `remise`
--
ALTER TABLE `remise`
  ADD CONSTRAINT `remise_ibfk_1` FOREIGN KEY (`idcompare`) REFERENCES `compare` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remise_ibfk_2` FOREIGN KEY (`idforn`) REFERENCES `fournisseur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `seance`
--
ALTER TABLE `seance`
  ADD CONSTRAINT `seance_ibfk_1` FOREIGN KEY (`idts`) REFERENCES `typeseance` (`idts`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `selection`
--
ALTER TABLE `selection`
  ADD CONSTRAINT `selection_ibfk_1` FOREIGN KEY (`idcprix`) REFERENCES `compare` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sortiemagasin`
--
ALTER TABLE `sortiemagasin`
  ADD CONSTRAINT `sortiemagasin_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `travaux`
--
ALTER TABLE `travaux`
  ADD CONSTRAINT `travaux_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `personel` (`ncnrps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `type_user`
--
ALTER TABLE `type_user`
  ADD CONSTRAINT `type_user_ibfk_1` FOREIGN KEY (`type`) REFERENCES `taches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
