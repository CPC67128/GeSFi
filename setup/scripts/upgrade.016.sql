ALTER TABLE `{TABLEPREFIX}investment_record` CHANGE `fee` `dividend` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `{TABLEPREFIX}investment_record` CHANGE `dividend` `income` DECIMAL(10,2) NULL DEFAULT NULL;

ALTER TABLE `{TABLEPREFIX}record` ADD `income` DECIMAL(10,2) NULL AFTER `value`;

ALTER TABLE `{TABLEPREFIX}account` ADD `generate_income` TINYINT(1) NOT NULL DEFAULT '0' AFTER `not_displayed_in_menu`;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_income_sum` DECIMAL(10,2) NULL AFTER `CALC_yield_average`;

DROP TABLE {TABLEPREFIX}investment_record;

