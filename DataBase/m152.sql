-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 23 Mars 2020 à 01:04
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `m152`
--

-- --------------------------------------------------------

--
-- Structure de la table `medias`
--

CREATE TABLE IF NOT EXISTS `medias` (
  `idMedias` int(11) NOT NULL AUTO_INCREMENT,
  `typeMedia` varchar(15) NOT NULL,
  `nomMedia` varchar(150) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `idPosts` int(11) NOT NULL,
  PRIMARY KEY (`idMedias`),
  KEY `medias_ibfk_1` (`idPosts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=174 ;

--
-- Contenu de la table `medias`
--

INSERT INTO `medias` (`idMedias`, `typeMedia`, `nomMedia`, `creationDate`, `modificationDate`, `idPosts`) VALUES
(171, 'video/mp4', '78_happy.mp4', '2020-03-23 00:01:19', '0000-00-00 00:00:00', 78),
(172, 'audio/mp3', '78_happy2min.mp3', '2020-03-23 00:01:19', '0000-00-00 00:00:00', 78),
(173, 'image/jpeg', '78_mlg.jpg', '2020-03-23 00:01:19', '0000-00-00 00:00:00', 78);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `idPosts` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(255) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPosts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`idPosts`, `commentaire`, `creationDate`, `modificationDate`) VALUES
(78, 'Bienvenue sur ma page', '2020-03-23 00:01:19', '0000-00-00 00:00:00');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `medias`
--
ALTER TABLE `medias`
  ADD CONSTRAINT `medias_ibfk_1` FOREIGN KEY (`idPosts`) REFERENCES `posts` (`idPosts`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
