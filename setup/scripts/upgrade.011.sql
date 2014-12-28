ALTER TABLE `bf_record` ADD `amount_invested` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `amount` ;

ALTER TABLE `bf_record` ADD `value` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `amount_invested` ;

ALTER TABLE `bf_record` ADD `CALC_days_since_creation` INT( 11 ) NULL AFTER `creation_date` ;

ALTER TABLE `bf_record` ADD `CALC_amount_accumulated` DECIMAL( 10, 2 ) NULL AFTER `CALC_days_since_creation` ;

ALTER TABLE `bf_record` ADD `CALC_amount_invested_accumulated` DECIMAL( 10, 2 ) NULL AFTER `CALC_amount_accumulated` ;

ALTER TABLE `bf_record` ADD `CALC_gain` DECIMAL( 10, 2 ) NULL AFTER `CALC_amount_invested_accumulated` ;

ALTER TABLE `bf_record` ADD `CALC_yield` DECIMAL( 10, 2 ) NULL AFTER `CALC_gain` ;

ALTER TABLE `bf_record` ADD `CALC_yield_average` DECIMAL( 10, 2 ) NULL AFTER `CALC_yield` ;

insert into bf_record
(
record_id,
record_group_id,
creation_date,
account_id,
record_date,
record_type,
designation,
amount,
amount_invested,
value,
marked_as_deleted,
CALC_days_since_creation,
CALC_amount_accumulated,
CALC_amount_invested_accumulated,
CALC_gain,
CALC_yield,
CALC_yield_average,
BOB_category
)
select
investment_record_id,
record_group_id,
creation_date,
account_id,
record_date,
record_type,
designation,
payment,
payment_invested,
value,
marked_as_deleted,
CALC_days_since_creation,
CALC_payment_accumulated,
CALC_payment_invested_accumulated,
CALC_gain,
CALC_yield,
CALC_yield_average,
'STEVE_TRANSFER'
from bf_investment_record
;


