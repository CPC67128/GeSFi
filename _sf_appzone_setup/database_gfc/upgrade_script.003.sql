DROP TABLE IF EXISTS `sf_gfc_configuration`;

CREATE TABLE `sf_gfc_configuration` (
  `user_id` bigint(20) NOT NULL,
  `actor1` varchar(200) character set utf8 NOT NULL default 'Homme',
  `actor2` varchar(200) character set utf8 NOT NULL default 'Femme',
  `culture` varchar(10) character set utf8 NOT NULL default 'fr-FR',
  `category1` varchar(200) character set utf8 NOT NULL default 'Vie courante',
  `category2` varchar(200) character set utf8 NOT NULL default 'Télécommunications',
  `category3` varchar(200) character set utf8 NOT NULL default 'Gaz et électricité',
  `category4` varchar(200) character set utf8 NOT NULL default 'Loyer',
  `category5` varchar(200) character set utf8 NOT NULL default 'Eau',
  `category6` varchar(200) character set utf8 NOT NULL default 'Assurance',
  `category7` varchar(200) character set utf8 NOT NULL default 'Exceptionnel (voyages...)',
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

