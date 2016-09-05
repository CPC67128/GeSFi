DROP TRIGGER IF EXISTS `Trigger_Account_AfterUpdate`;
DROP TRIGGER IF EXISTS `Trigger_Account_AfterInsert`;

DROP TRIGGER IF EXISTS `Trigger_Record_AfterDelete`;
DROP TRIGGER IF EXISTS `Trigger_Record_AfterInsert`;
DROP TRIGGER IF EXISTS `Trigger_Record_AfterUpdate`;


DROP PROCEDURE IF EXISTS `CalculateAccountBalances`;
DROP PROCEDURE IF EXISTS `CalculateAllAccountsBalances`;

delete from `{TABLEPREFIX}user` where `user_id` = '1bd821a8-e5d5-11e4-b1a2-e4d53de1ede6';

