ALTER TABLE `{TABLEPREFIX}account` ADD COLUMN `no_yield_display` TINYINT(1) NOT NULL DEFAULT '0' AFTER `no_color_in_dashboard`;

ALTER TABLE `{TABLEPREFIX}account` CHANGE COLUMN `availability_date` `availability_date` DATE NULL AFTER `creation_date`;

ALTER TABLE `{TABLEPREFIX}account` CHANGE COLUMN `closing_date` `closing_date` DATE NULL AFTER `availability_date`;
