ALTER TABLE `budgetfox`.`bf_record` DROP INDEX `category`;

ALTER TABLE `budgetfox`.`bf_record` ADD INDEX `category_id` (`category_id`) USING BTREE;

ANALYZE TABLE `bf_record` ;

OPTIMIZE TABLE `bf_record` ;

