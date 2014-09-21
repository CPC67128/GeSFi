ALTER TABLE `{TABLEPREFIX}account` ADD `record_confirmation` tinyint NOT NULL AFTER `minimum_check_period` ;

update `{TABLEPREFIX}account`
set record_confirmation = 1 ;