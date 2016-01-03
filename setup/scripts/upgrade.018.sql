ALTER TABLE `{TABLEPREFIX}record` DROP INDEX `category`;

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX `category_id` (`category_id`) USING BTREE;

ANALYZE TABLE `{TABLEPREFIX}record` ;

OPTIMIZE TABLE `{TABLEPREFIX}record` ;

