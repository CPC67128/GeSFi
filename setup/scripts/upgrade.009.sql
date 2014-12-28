CREATE PROCEDURE `CalculateAccountBalances`(IN `p_account_id` VARCHAR(36)) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
BEGIN
update bf_account
set CALC_balance =
opening_balance
+
(
    select sum(amount) as total
	from bf_record
	where record_type between 10 and 19
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
)
-
(
    select sum(amount) as total
	from bf_record
	where record_type between 20 and 29
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
)
where account_id = p_account_id
;

update bf_account
set CALC_balance_confirmed =
opening_balance
+
(
    select sum(amount) as total
	from bf_record
	where record_type between 10 and 19
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
	and confirmed = 1
)
-
(
    select sum(amount) as total
	from bf_record
	where record_type between 20 and 29
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
	and confirmed = 1
)
where account_id = p_account_id
and record_confirmation = 1
;

update bf_account
set CALC_balance_confirmed = CALC_balance
where account_id = p_account_id
and record_confirmation != 1
;

END
;

DROP TRIGGER IF EXISTS `Trigger_Record_AfterInsert`;
CREATE TRIGGER `Trigger_Record_AfterInsert` AFTER INSERT ON `bf_record` FOR EACH ROW call CalculateAccountBalances(new.account_id);

DROP TRIGGER IF EXISTS `Trigger_Record_AfterUpdate`;
CREATE TRIGGER `Trigger_Record_AfterUpdate` AFTER UPDATE ON `bf_record` FOR EACH ROW call CalculateAccountBalances(old.account_id);

DROP TRIGGER IF EXISTS `Trigger_Record_AfterDelete`;
CREATE TRIGGER `Trigger_Record_AfterDelete` AFTER DELETE ON `bf_record` FOR EACH ROW call CalculateAccountBalances(old.account_id);

DROP TRIGGER IF EXISTS `Trigger_Account_AfterInsert`;
CREATE TRIGGER `Trigger_Account_AfterInsert` AFTER INSERT ON `bf_account` FOR EACH ROW call CalculateAccountBalances(new.account_id);
