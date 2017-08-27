ALTER TABLE `{TABLEPREFIX}user` DROP `duo_id`;
ALTER TABLE `{TABLEPREFIX}user` DROP `culture`;
ALTER TABLE `{TABLEPREFIX}user` DROP `subscription_date`;
ALTER TABLE `{TABLEPREFIX}user` DROP `user_name`;

ALTER TABLE `{TABLEPREFIX}account` DROP `coowner_user_id`;
ALTER TABLE `{TABLEPREFIX}account` DROP `information`;

UPDATE `{TABLEPREFIX}category` set link_id = '' where link_type = 'DUO';

DROP TABLE {TABLEPREFIX}statistics_balance;

DROP TABLE {TABLEPREFIX}statistics;
