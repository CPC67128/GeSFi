ALTER TABLE `{TABLEPREFIX}user` DROP `active`;

ALTER TABLE `{TABLEPREFIX}user` MODIFY COLUMN name varchar(200) AFTER user_id;

delete FROM `{TABLEPREFIX}user_connection` where browser = null;
delete FROM `{TABLEPREFIX}user_connection` where browser = '';

create table {TABLEPREFIX}user_connection_temp like {TABLEPREFIX}user_connection;
insert into {TABLEPREFIX}user_connection_temp select distinct * from {TABLEPREFIX}user_connection;
delete from {TABLEPREFIX}user_connection;
ALTER TABLE `{TABLEPREFIX}user_connection` ADD PRIMARY KEY( `user_id`, `connection_date_time`, `ip_address`, `browser`);
insert into {TABLEPREFIX}user_connection select * from {TABLEPREFIX}user_connection_temp;
drop table {TABLEPREFIX}user_connection_temp;



update `{TABLEPREFIX}user` set user_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where user_id = '4768b159-bd52-11e2-8d63-5c260a87ddbb';
update `{TABLEPREFIX}user_connection` set user_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where user_id = '4768b159-bd52-11e2-8d63-5c260a87ddbb';
update `{TABLEPREFIX}record` set user_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where user_id = '4768b159-bd52-11e2-8d63-5c260a87ddbb';
update `{TABLEPREFIX}category` set link_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where link_id = '4768b159-bd52-11e2-8d63-5c260a87ddbb';
update `{TABLEPREFIX}account_user_preference` set user_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where user_id = '4768b159-bd52-11e2-8d63-5c260a87ddbb';
update `{TABLEPREFIX}account` set owner_user_id = 'bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where owner_user_id  = '4768b159-bd52-11e2-8d63-5c260a87ddbb';

update `{TABLEPREFIX}record` set category_id = 'USER/bbb3b8d1-1e44-4914-b2e7-b95896ac3983' where category_id = 'USER/4768b159-bd52-11e2-8d63-5c260a87ddbb';

ALTER TABLE `{TABLEPREFIX}record` DROP `BOB_category`;

