-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Server version: 5.5.23
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `urban_psymap`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `points_id` bigint(20) unsigned NOT NULL,
  `users_id` bigint(20) unsigned NOT NULL,
  `tfl_answer` int(10) unsigned DEFAULT NULL,
  `boroughs_answer` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_answer_photos1` (`points_id`),
  KEY `fk_answers_users1` (`users_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21539 ;

-- --------------------------------------------------------

--
-- Table structure for table `boroughs`
--

CREATE TABLE IF NOT EXISTS `boroughs` (
  `ons_label` varchar(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lat` decimal(12,10) NOT NULL,
  `lon` decimal(12,10) NOT NULL,
  PRIMARY KEY (`ons_label`),
  UNIQUE KEY `tfl_id_UNIQUE` (`ons_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE IF NOT EXISTS `points` (
  `id` bigint(20) unsigned NOT NULL,
  `tubes_tfl_id` int(10) unsigned NOT NULL,
  `boroughs_ons_label` varchar(4) NOT NULL,
  `lat` decimal(12,10) NOT NULL,
  `lon` decimal(12,10) NOT NULL,
  `fake` tinyint(4) DEFAULT NULL,
  `reported` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_photos_tubes` (`tubes_tfl_id`),
  KEY `fk_points_boroughs1` (`boroughs_ons_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tubes`
--

CREATE TABLE IF NOT EXISTS `tubes` (
  `tfl_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `lat` decimal(12,10) NOT NULL,
  `lon` decimal(12,10) NOT NULL,
  PRIMARY KEY (`tfl_id`),
  UNIQUE KEY `tfl_id_UNIQUE` (`tfl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) DEFAULT NULL,
  `ip_city` varchar(100) DEFAULT NULL,
  `ip_country` varchar(100) DEFAULT NULL,
  `ip_lat` decimal(12,10) DEFAULT NULL,
  `ip_lon` decimal(12,10) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `age` varchar(3) DEFAULT NULL,
  `ethnic` varchar(45) DEFAULT NULL,
  `occupation` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15321 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `fk_answers_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_answer_photos1` FOREIGN KEY (`points_id`) REFERENCES `points` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `fk_photos_tubes` FOREIGN KEY (`tubes_tfl_id`) REFERENCES `tubes` (`tfl_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_points_boroughs1` FOREIGN KEY (`boroughs_ons_label`) REFERENCES `boroughs` (`ons_label`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
