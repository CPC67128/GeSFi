-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Dim 27 Août 2017 à 23:04
-- Version du serveur :  10.0.30-MariaDB
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `gesfi`
--

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}account`
--

CREATE TABLE `{TABLEPREFIX}account` (
  `account_id` varchar(36) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `owner_user_id` varchar(40) NOT NULL DEFAULT '',
  `opening_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `expected_minimum_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creation_date` date NOT NULL,
  `availability_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `minimum_check_period` int(11) NOT NULL DEFAULT '30',
  `record_confirmation` tinyint(4) NOT NULL,
  `marked_as_closed` tinyint(1) NOT NULL DEFAULT '0',
  `not_displayed_in_menu` tinyint(1) NOT NULL DEFAULT '0',
  `no_color_in_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `generate_income` tinyint(1) NOT NULL DEFAULT '0',
  `CALC_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `CALC_balance_confirmed` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}account_user_preference`
--

CREATE TABLE `{TABLEPREFIX}account_user_preference` (
  `user_id` varchar(36) NOT NULL,
  `account_id` varchar(36) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}category`
--

CREATE TABLE `{TABLEPREFIX}category` (
  `category_id` varchar(36) NOT NULL DEFAULT 'UUID()',
  `link_type` varchar(20) NOT NULL,
  `link_id` varchar(36) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `category` varchar(100) NOT NULL,
  `active_from` date NOT NULL,
  `sort_order` int(11) NOT NULL,
  `marked_as_inactive` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}ccb`
--

CREATE TABLE `{TABLEPREFIX}ccb` (
  `ccb_id` int(11) NOT NULL,
  `database_version` int(11) NOT NULL,
  `upgrade_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone';

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}record`
--

CREATE TABLE `{TABLEPREFIX}record` (
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
  `amount` decimal(10,2) DEFAULT '0.00',
  `amount_invested` decimal(10,2) DEFAULT '0.00',
  `value` decimal(10,2) DEFAULT '0.00',
  `withdrawal` decimal(10,2) DEFAULT '0.00',
  `income` decimal(10,2) DEFAULT NULL,
  `charge` tinyint(4) NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `actor` tinyint(4) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flag_1` tinyint(4) NOT NULL,
  `flag_2` tinyint(4) NOT NULL,
  `flag_3` tinyint(4) NOT NULL,
  `CALC_days_since_creation` int(11) DEFAULT NULL,
  `CALC_amount_accumulated` decimal(10,2) DEFAULT NULL,
  `CALC_amount_invested_accumulated` decimal(10,2) DEFAULT NULL,
  `CALC_gain` decimal(10,2) DEFAULT NULL,
  `CALC_yield` decimal(10,2) DEFAULT NULL,
  `CALC_yield_average` decimal(10,2) DEFAULT NULL,
  `CALC_withdrawal_sum` decimal(10,2) DEFAULT '0.00',
  `CALC_income_sum` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}user`
--

CREATE TABLE `{TABLEPREFIX}user` (
  `user_id` varchar(40) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone';

--
-- Contenu de la table `{TABLEPREFIX}user`
--

INSERT INTO `{TABLEPREFIX}user` (`user_id`, `name`, `email`, `password`, `role`) VALUES
('4768b151-bd52-11e2-8d63-5c260a87ddbb', 'Partenaire 1', 'partner1@nowhere.com', 'd41d8cd98f00b204e9800998ecf8427e', 0),
('bbb3b8d1-1e44-4914-b2e7-b95896ac3983', 'Partenaire 2', 'partner2@nowhere.com', 'd41d8cd98f00b204e9800998ecf8427e', 0);

-- --------------------------------------------------------

--
-- Structure de la table `{TABLEPREFIX}user_connection`
--

CREATE TABLE `{TABLEPREFIX}user_connection` (
  `user_id` varchar(40) NOT NULL,
  `connection_date_time` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `browser` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gérée par le framework SF_AppZone';

--
-- Index pour les tables exportées
--

--
-- Index pour la table `{TABLEPREFIX}account`
--
ALTER TABLE `{TABLEPREFIX}account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `owner_user_id` (`owner_user_id`),
  ADD KEY `type` (`type`);

--
-- Index pour la table `{TABLEPREFIX}account_user_preference`
--
ALTER TABLE `{TABLEPREFIX}account_user_preference`
  ADD PRIMARY KEY (`user_id`,`account_id`);

--
-- Index pour la table `{TABLEPREFIX}category`
--
ALTER TABLE `{TABLEPREFIX}category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `link_type` (`link_type`),
  ADD KEY `link_id` (`link_id`);

--
-- Index pour la table `{TABLEPREFIX}ccb`
--
ALTER TABLE `{TABLEPREFIX}ccb`
  ADD PRIMARY KEY (`ccb_id`);

--
-- Index pour la table `{TABLEPREFIX}record`
--
ALTER TABLE `{TABLEPREFIX}record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `creation_date_month` (`record_date_month`),
  ADD KEY `creation_date_year` (`record_date_year`),
  ADD KEY `actor` (`actor`),
  ADD KEY `record_type` (`record_type`),
  ADD KEY `record_group_id` (`record_group_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `marked_as_deleted` (`marked_as_deleted`),
  ADD KEY `record_date` (`record_date`),
  ADD KEY `record_date_month` (`record_date_month`),
  ADD KEY `record_date_year` (`record_date_year`),
  ADD KEY `confirmed` (`confirmed`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `{TABLEPREFIX}user`
--
ALTER TABLE `{TABLEPREFIX}user`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `{TABLEPREFIX}user_connection`
--
ALTER TABLE `{TABLEPREFIX}user_connection`
  ADD PRIMARY KEY (`user_id`,`connection_date_time`,`ip_address`,`browser`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `{TABLEPREFIX}ccb`
--
ALTER TABLE `{TABLEPREFIX}ccb`
  MODIFY `ccb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
