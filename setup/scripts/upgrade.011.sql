ALTER TABLE `{TABLEPREFIX}record` ADD `amount_invested` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `amount` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `value` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `amount_invested` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_days_since_creation` INT( 11 ) NULL AFTER `creation_date` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_amount_accumulated` DECIMAL( 10, 2 ) NULL AFTER `CALC_days_since_creation` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_amount_invested_accumulated` DECIMAL( 10, 2 ) NULL AFTER `CALC_amount_accumulated` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_gain` DECIMAL( 10, 2 ) NULL AFTER `CALC_amount_invested_accumulated` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_yield` DECIMAL( 10, 2 ) NULL AFTER `CALC_gain` ;

ALTER TABLE `{TABLEPREFIX}record` ADD `CALC_yield_average` DECIMAL( 10, 2 ) NULL AFTER `CALC_yield` ;

insert into {TABLEPREFIX}record
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
from {TABLEPREFIX}investment_record
;


ALTER TABLE `{TABLEPREFIX}record` CHANGE `amount` `amount` DECIMAL( 10, 2 ) NULL DEFAULT '0.00';
ALTER TABLE `{TABLEPREFIX}record` CHANGE `amount_invested` `amount_invested` DECIMAL( 10, 2 ) NULL DEFAULT '0.00';
ALTER TABLE `{TABLEPREFIX}record` CHANGE `value` `value` DECIMAL( 10, 2 ) NULL DEFAULT '0.00';

update `{TABLEPREFIX}record`
set amount = null, amount_invested = null
WHERE BOB_category = 'STEVE_TRANSFER'
and amount = 0
and amount_invested = 0;

update `{TABLEPREFIX}record`
set value = null
WHERE BOB_category = 'STEVE_TRANSFER'
and amount is not null
and amount_invested is not null
and value = 0;

update `{TABLEPREFIX}record`
set value = null
WHERE BOB_category = 'STEVE_TRANSFER'
and amount is null
and amount_invested is null
and value = 0
and record_type = 2;
