ALTER TABLE `{TABLEPREFIX}user` ADD `user_name` VARCHAR(100) NOT NULL AFTER `user_id`;

update `{TABLEPREFIX}user` set user_name = email;

update `{TABLEPREFIX}user` set user_name = name where user_name = '';

ALTER TABLE `{TABLEPREFIX}user` CHANGE `read_only` `active` TINYINT(1) NOT NULL DEFAULT '1';
update `{TABLEPREFIX}user` set active = 1 ;

ALTER TABLE `{TABLEPREFIX}user` DROP INDEX `email`, ADD UNIQUE `user_name` (`user_name`) COMMENT '';
