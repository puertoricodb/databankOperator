-- phpMyAdmin SQL Dump
-- version 3.4.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 02, 2012 at 11:32 AM
-- Server version: 5.0.92
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `databank`
--

-- --------------------------------------------------------

--
-- Table structure for table `atoms`
--

CREATE TABLE IF NOT EXISTS `atoms` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `category_id` int(11) NOT NULL,
  `img` varchar(60) default NULL,
  `status` varchar(60) default NULL,
  `foursquare_id` varchar(80) default NULL,
  PRIMARY KEY  (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Triggers `atoms`
--
DROP TRIGGER IF EXISTS `atoms_trigger_new`;
DELIMITER //
CREATE TRIGGER `atoms_trigger_new` AFTER INSERT ON `atoms`
 FOR EACH ROW begin
         INSERT INTO atom_logs (`atom_id`, `type` ) VALUES(NEW.id, 'NEW');
    end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `atoms_trigger_upd`;
DELIMITER //
CREATE TRIGGER `atoms_trigger_upd` AFTER UPDATE ON `atoms`
 FOR EACH ROW begin
         INSERT INTO atom_logs (`atom_id`, `type` ) VALUES(NEW.id, 'UPD');
    end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `atom_logs`
--

CREATE TABLE IF NOT EXISTS `atom_logs` (
  `id` int(11) NOT NULL auto_increment,
  `atom_id` int(11) default NULL,
  `timestamp` timestamp NULL default CURRENT_TIMESTAMP,
  `type` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL auto_increment,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
