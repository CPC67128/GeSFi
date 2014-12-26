ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`record_group_id`);

ALTER TABLE `{TABLEPREFIX}account` ADD INDEX(`type`);

ALTER TABLE `{TABLEPREFIX}category` ADD INDEX(`link_type`);

ALTER TABLE `{TABLEPREFIX}category` ADD INDEX(`link_id`);

ALTER TABLE `{TABLEPREFIX}investment_record` ADD INDEX(`record_group_id`);

ALTER TABLE `{TABLEPREFIX}investment_record` ADD INDEX(`account_id`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`account_id`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`user_id`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`marked_as_deleted`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`record_date`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`record_date_month`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`record_date_year`);

ALTER TABLE `{TABLEPREFIX}record` ADD INDEX(`confirmed`);

