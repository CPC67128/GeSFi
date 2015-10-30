ALTER TABLE `{TABLEPREFIX}investment_record` CHANGE `fee` `dividend` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `{TABLEPREFIX}investment_record` CHANGE `dividend` `income` DECIMAL(10,2) NULL DEFAULT NULL;

ALTER TABLE `{TABLEPREFIX}record` ADD `income` DECIMAL(10,2) NULL AFTER `value`;

ALTER TABLE `{TABLEPREFIX}account` ADD `generate_income` TINYINT(1) NOT NULL DEFAULT '0' AFTER `not_displayed_in_menu`;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_income_sum` DECIMAL(10,2) NULL AFTER `CALC_yield_average`;

DROP TABLE {TABLEPREFIX}investment_record;

ALTER TABLE `{TABLEPREFIX}record` ADD `surrender` DECIMAL(10,2) NULL DEFAULT NULL AFTER `value`;
ALTER TABLE `{TABLEPREFIX}record` CHANGE `surrender` `surrender` DECIMAL(10,2) NULL DEFAULT '0';

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_surrender_sum` DECIMAL(10,2) NULL DEFAULT '0' AFTER `CALC_yield_average`;

ALTER TABLE `{TABLEPREFIX}record` CHANGE `surrender` `withdrawal` DECIMAL(10,2) NULL DEFAULT '0.00';

ALTER TABLE `{TABLEPREFIX}record` CHANGE `CALC_surrender_sum` `CALC_withdrawal_sum` DECIMAL(10,2) NULL DEFAULT '0.00';