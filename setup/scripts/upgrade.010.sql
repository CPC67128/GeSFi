CREATE TRIGGER `Trigger_Account_AfterUpdate` AFTER UPDATE ON `bf_account` FOR EACH ROW begin if (new.opening_balance != old.opening_balance) then call CalculateAccountBalances(new.account_id); END IF; end;

DROP PROCEDURE IF EXISTS `CalculateAllAccountsBalances`;

CREATE PROCEDURE `CalculateAllAccountsBalances`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
begin

DECLARE done int DEFAULT FALSE;
DECLARE t_account_id varchar(36);

DECLARE cur1 cursor for SELECT account_id FROM bf_account;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
open cur1;

myloop: loop
    fetch cur1 into t_account_id;
    if done then
        leave myloop;
    end if;
    call CalculateAccountBalances(t_account_id);
end loop;

close cur1;

end
;

call CalculateAllAccountsBalances();