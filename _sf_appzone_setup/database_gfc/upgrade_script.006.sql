ALTER TABLE `sf_gfc_configuration` CHANGE `common_account` `real_joint_account_use` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sf_gfc_configuration` ADD `actor1_email` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `actor1`;
ALTER TABLE `sf_gfc_configuration` ADD `actor2_email` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `actor2` ;
ALTER TABLE `sf_gfc_configuration` ADD `joint_account_expected_minimum_balance` DECIMAL( 10, 2 ) NOT NULL DEFAULT '300';
ALTER TABLE `sf_gfc_configuration` ADD `actor1_default_charge` TINYINT NOT NULL DEFAULT '50';
ALTER TABLE `sf_gfc_configuration` ADD `joint_account_expected_minimum_credit` DECIMAL( 10, 2 ) NOT NULL DEFAULT '300' AFTER `joint_account_expected_minimum_balance` ;
ALTER TABLE `sf_gfc_configuration` ADD `joint_account_maximum_actor_extra_credit` DECIMAL( 10, 2 ) NOT NULL DEFAULT '150' AFTER `joint_account_expected_minimum_credit` ;

