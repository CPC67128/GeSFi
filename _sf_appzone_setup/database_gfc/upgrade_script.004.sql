ALTER TABLE `sf_gfc_records` ADD `to_account` TINYINT NOT NULL ;
ALTER TABLE `sf_gfc_records` CHANGE `to_account` `to_account` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `sf_gfc_configuration` ADD `common_account` BOOLEAN NOT NULL DEFAULT FALSE ;
