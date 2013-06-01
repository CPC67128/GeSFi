update `sf_user` set password = md5;

ALTER TABLE `sf_user` DROP `md5`;

ALTER TABLE `sf_user` CHANGE `full_name` `full_name` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';

update `sf_user` set password = 'd41d8cd98f00b204e9800998ecf8427e' where password is null;
update `sf_user` set password = 'd41d8cd98f00b204e9800998ecf8427e' where password = '';

update `sf_user` set full_name = '' where full_name is null;