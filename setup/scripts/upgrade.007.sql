ALTER TABLE `bf_account` ADD `CALC_balance` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `not_displayed_in_menu`;

ALTER TABLE `bf_account`  ADD `CALC_balance_confirmed` DECIMAL(10,2) NOT NULL DEFAULT '0'  AFTER `CALC_balance`;
