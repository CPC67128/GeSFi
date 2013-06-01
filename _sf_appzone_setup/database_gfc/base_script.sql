-- phpMyAdmin SQL Dump
-- version 2.6.4-pl3
-- http://www.phpmyadmin.net
-- 
-- Serveur: db367700422.db.1and1.com
-- Généré le : Dimanche 18 Septembre 2011 à 13:41
-- Version du serveur: 5.0.91
-- Version de PHP: 5.3.3-7+squeeze3
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `gfc_records`
-- 

DROP TABLE IF EXISTS `gfc_records`;
CREATE TABLE IF NOT EXISTS `gfc_records` (
  `id` int(11) NOT NULL auto_increment,
  `creation_date` date NOT NULL,
  `marked_as_archived` tinyint(1) NOT NULL default '0',
  `marked_as_deleted` tinyint(1) NOT NULL default '0',
  `designation` varchar(200) character set utf8 NOT NULL,
  `paiement_homme` decimal(10,2) NOT NULL default '0.00',
  `paiement_femme` decimal(10,2) NOT NULL default '0.00',
  `a_rembourser_par_homme` decimal(10,2) NOT NULL default '0.00',
  `a_rembourser_par_femme` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------

ALTER TABLE `gfc_records` ADD `expense` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `gfc_records` ADD `actor` TINYINT NOT NULL AFTER `expense` ;
ALTER TABLE `gfc_records` CHANGE `expense` `amount` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00';

update gfc_records set amount = paiement_homme + paiement_femme;
update gfc_records set actor=1 where paiement_homme>0;
update gfc_records set actor=2 where paiement_femme>0;
update gfc_records set actor=1 where a_rembourser_par_homme<0;
update gfc_records set actor=2 where a_rembourser_par_femme<0;

ALTER TABLE `gfc_records` ADD `charge` TINYINT NOT NULL AFTER `amount`;

UPDATE gfc_records SET charge = ( 100 - ( (a_rembourser_par_homme + a_rembourser_par_femme) / amount ) *100 ) ;

update gfc_records set amount = -1 * a_rembourser_par_femme where a_rembourser_par_femme < 0;
update gfc_records set amount = -1 * a_rembourser_par_homme where a_rembourser_par_homme < 0;

ALTER TABLE `gfc_records` ADD `expense` BOOLEAN NOT NULL DEFAULT '1' AFTER `a_rembourser_par_femme`;

update gfc_records set expense = 1;
update gfc_records set expense = 0 where a_rembourser_par_homme < 0;
update gfc_records set expense = 0 where a_rembourser_par_femme < 0;


ALTER TABLE `gfc_records` ADD `category` INT NOT NULL DEFAULT '0' AFTER `charge` ;

update gfc_records set category = 0;
update gfc_records set category = 1 where expense = 1;

ALTER TABLE `gfc_records` ADD `creation_date_month` TINYINT NOT NULL DEFAULT '-1' AFTER `creation_date` ;
ALTER TABLE `gfc_records` ADD `creation_date_year` SMALLINT NOT NULL DEFAULT '-1' AFTER `creation_date_month` ;

update gfc_records set creation_date_month = month(creation_date), creation_date_year = year(creation_date) where creation_date_month = -1;

ALTER TABLE `gfc_records` ADD INDEX ( `creation_date_month` ) ;
ALTER TABLE `gfc_records` ADD INDEX ( `creation_date_year` ) ;
ALTER TABLE `gfc_records` ADD INDEX ( `expense` ) ;
ALTER TABLE `gfc_records` ADD INDEX ( `actor` ) ;
ALTER TABLE `gfc_records` ADD INDEX ( `category` ) ;

-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------

ALTER TABLE `gfc_records` CHANGE `expense` `record_type` TINYINT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE gfc_records DROP INDEX expense;

ALTER TABLE `gfc_records` ADD INDEX ( `record_type` ) ;

