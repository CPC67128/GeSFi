DROP PROCEDURE `CalculateAccountBalances`;

CREATE PROCEDURE `CalculateAccountBalances`(IN `p_account_id` VARCHAR(36)) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
BEGIN

update bf_account
set CALC_balance =
opening_balance
+
(
    select ifnull(sum(amount), 0) as total
	from bf_record
	where record_type between 10 and 19
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
)
-
(
    select ifnull(sum(amount), 0) as total
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
    select ifnull(sum(amount), 0) as total
	from bf_record
	where record_type between 10 and 19
	and marked_as_deleted = 0
	and account_id = bf_account.account_id
	and record_date <= curdate()
	and confirmed = 1
)
-
(
    select ifnull(sum(amount), 0) as total
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

CALL `CalculateAllAccountsBalances`();
