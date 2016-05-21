-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Jeu 28 Novembre 2013 à 14:09
-- Version du serveur: 5.5.32
-- Version de PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `GeSFi`
--

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}account`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}account`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}account` (
  `account_id` varchar(36) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL,
  `information` varchar(400) NOT NULL,
  `type` int(11) NOT NULL,
  `owner_user_id` varchar(40) NOT NULL DEFAULT '',
  `coowner_user_id` varchar(40) NOT NULL DEFAULT '',
  `opening_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `expected_minimum_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creation_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `marked_as_closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  KEY `owner_user_id` (`owner_user_id`),
  KEY `coowner_user_id` (`coowner_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}account_user_preference`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}account_user_preference`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}account_user_preference` (
  `user_id` varchar(36) NOT NULL,
  `account_id` varchar(36) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}category`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}category`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}category` (
  `category_id` varchar(36) NOT NULL DEFAULT 'UUID()',
  `link_type` varchar(20) NOT NULL,
  `link_id` varchar(36) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `category` varchar(100) NOT NULL,
  `active_from` date NOT NULL,
  `sort_order` int(11) NOT NULL,
  `marked_as_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}ccb`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}ccb`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}ccb` (
  `ccb_id` int(11) NOT NULL AUTO_INCREMENT,
  `database_version` int(11) NOT NULL,
  `upgrade_date` datetime NOT NULL,
  PRIMARY KEY (`ccb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone' AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}investment_record`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}investment_record`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}investment_record` (
  `investment_record_id` varchar(36) NOT NULL,
  `record_group_id` varchar(36) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` varchar(36) NOT NULL,
  `record_date` date NOT NULL,
  `record_type` tinyint(1) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `payment_invested` decimal(10,2) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `marked_as_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `CALC_days_since_creation` int(11) NOT NULL,
  `CALC_payment_accumulated` float DEFAULT NULL,
  `CALC_payment_invested_accumulated` float DEFAULT NULL,
  `CALC_gain` float DEFAULT NULL,
  `CALC_yield` float DEFAULT NULL,
  `CALC_yield_average` float DEFAULT NULL,
  PRIMARY KEY (`investment_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}record`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}record`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}record` (
  `account_id` varchar(36) NOT NULL DEFAULT '',
  `record_id` varchar(36) NOT NULL DEFAULT '',
  `record_group_id` varchar(36) NOT NULL DEFAULT '',
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `record_date` date NOT NULL,
  `record_date_month` tinyint(4) NOT NULL DEFAULT '-1',
  `record_date_year` smallint(6) NOT NULL DEFAULT '-1',
  `marked_as_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `designation` varchar(200) NOT NULL,
  `record_type` tinyint(1) NOT NULL DEFAULT '1',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `charge` tinyint(4) NOT NULL,
  `BOB_category` varchar(200) NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `actor` tinyint(4) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `creation_date_month` (`record_date_month`),
  KEY `creation_date_year` (`record_date_year`),
  KEY `actor` (`actor`),
  KEY `category` (`BOB_category`),
  KEY `record_type` (`record_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}user`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}user`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}user` (
  `user_id` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `subscription_date` date NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `culture` varchar(10) NOT NULL DEFAULT 'fr-FR',
  `read_only` tinyint(1) NOT NULL DEFAULT '0',
  `duo_id` varchar(36) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone';

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}user_connection`
--

DROP TABLE IF EXISTS `{TABLEPREFIX}user_connection`;
CREATE TABLE IF NOT EXISTS `{TABLEPREFIX}user_connection` (
  `user_id` varchar(40) NOT NULL,
  `connection_date_time` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `browser` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
