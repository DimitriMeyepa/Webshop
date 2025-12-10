-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 déc. 2025 à 04:31
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `webshop`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `idcontact` int NOT NULL AUTO_INCREMENT,
  `sujetcontact` varchar(255) NOT NULL,
  `messagecontact` text NOT NULL,
  `dateenvoie` datetime DEFAULT CURRENT_TIMESTAMP,
  `idutilisateur` int NOT NULL,
  PRIMARY KEY (`idcontact`),
  KEY `idutilisateur` (`idutilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `idpaiement` int NOT NULL AUTO_INCREMENT,
  `totalpaiement` decimal(10,2) NOT NULL,
  `datepaiement` datetime DEFAULT CURRENT_TIMESTAMP,
  `moyenpaiement` varchar(50) DEFAULT NULL,
  `idutilisateur` int NOT NULL,
  PRIMARY KEY (`idpaiement`),
  KEY `idutilisateur` (`idutilisateur`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `paiement_produit`
--

DROP TABLE IF EXISTS `paiement_produit`;
CREATE TABLE IF NOT EXISTS `paiement_produit` (
  `idpaiement` int NOT NULL,
  `referenceprod` int NOT NULL,
  `quantite` int DEFAULT '1',
  PRIMARY KEY (`idpaiement`,`referenceprod`),
  KEY `referenceprod` (`referenceprod`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `idpanier` int NOT NULL AUTO_INCREMENT,
  `idutilisateur` int NOT NULL,
  `datecreation` datetime DEFAULT CURRENT_TIMESTAMP,
  `est_actif` tinyint DEFAULT '1',
  PRIMARY KEY (`idpanier`),
  KEY `idutilisateur` (`idutilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier_produit`
--

DROP TABLE IF EXISTS `panier_produit`;
CREATE TABLE IF NOT EXISTS `panier_produit` (
  `idpanier` int NOT NULL,
  `referenceprod` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`idpanier`,`referenceprod`),
  KEY `referenceprod` (`referenceprod`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `referenceprod` int NOT NULL AUTO_INCREMENT,
  `imageprod` varchar(255) DEFAULT NULL,
  `nomprod` varchar(150) NOT NULL,
  `prixprod` decimal(10,2) NOT NULL,
  `tailleprod` varchar(50) DEFAULT NULL,
  `couleurprod` varchar(50) DEFAULT NULL,
  `categorieprod` varchar(100) DEFAULT NULL,
  `descriptionprod` text,
  `descriptionsupprod` text,
  `informationsupprod` text,
  `avisprod` text,
  `dateajoutprod` datetime DEFAULT CURRENT_TIMESTAMP,
  `genreprod` enum('homme','femme') NOT NULL,
  PRIMARY KEY (`referenceprod`)
) ;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`referenceprod`, `imageprod`, `nomprod`, `prixprod`, `tailleprod`, `couleurprod`, `categorieprod`, `descriptionprod`, `descriptionsupprod`, `informationsupprod`, `avisprod`, `dateajoutprod`, `genreprod`) VALUES
(1, 'C:/wamp64/www/Webshop/Webshop-frontend/img/costume/costume_noir.jpg', 'Costume élégant', 5000.00, 'S,M,L,XL', 'Noir,Bleu,Gris', 'Business', 'Conçu pour l’homme moderne, le costume élégant offre une coupe raffinée et des matériaux premium.', 'Tissu premium, coupe ajustée, nettoyage à sec uniquement.', 'Composition : 80% laine, 20% polyester. Fabriqué selon des normes durables.', 'Aucun avis pour le moment.', '2025-12-03 08:30:42', 'homme');

-- --------------------------------------------------------

--
-- Structure de la table `produit_images`
--

DROP TABLE IF EXISTS `produit_images`;
CREATE TABLE IF NOT EXISTS `produit_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit_images`
--

INSERT INTO `produit_images` (`id`, `produit_id`, `image_path`) VALUES
(1, 1, 'C:/wamp64/www/Webshop/Webshop-frontend/img/costume/costume_noir.jpg'),
(2, 1, 'C:/wamp64/www/Webshop/Webshop-frontend/img/costume/costume_bleu.jpg'),
(3, 1, 'C:/wamp64/www/Webshop/Webshop-frontend/img/costume/costume_gris.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` int NOT NULL AUTO_INCREMENT,
  `nomutilisateur` varchar(100) NOT NULL,
  `prenomutilisateur` varchar(100) NOT NULL,
  `emailutilisateur` varchar(150) NOT NULL,
  `motdepasseutilisateur` varchar(255) NOT NULL,
  `adresseutilisateur` varchar(255) DEFAULT NULL,
  `villeutilisateur` varchar(100) DEFAULT NULL,
  `codepostalutilisateur` varchar(20) DEFAULT NULL,
  `paysutilisateur` varchar(100) DEFAULT 'Mauritius',
  `telephoneutilisateur` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idutilisateur`),
  UNIQUE KEY `emailutilisateur` (`emailutilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiement_produit`
--
ALTER TABLE `paiement_produit`
  ADD CONSTRAINT `paiement_produit_ibfk_1` FOREIGN KEY (`idpaiement`) REFERENCES `paiement` (`idpaiement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paiement_produit_ibfk_2` FOREIGN KEY (`referenceprod`) REFERENCES `produit` (`referenceprod`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
