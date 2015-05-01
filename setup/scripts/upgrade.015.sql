UPDATE `{TABLEPREFIX}account` set type = 3 where type = 2;
UPDATE `{TABLEPREFIX}account` set type = 1 where type = 4;

INSERT INTO `{TABLEPREFIX}record`(`account_id`, `record_id`, `user_id`, `record_date`, `record_date_month`, `record_date_year`, `marked_as_deleted`, `designation`, `record_type`, `amount`, `amount_invested`, `value`, `charge`, `BOB_category`, `category_id`, `actor`, `confirmed`, `creation_date`, `CALC_days_since_creation`, `CALC_amount_accumulated`, `CALC_amount_invested_accumulated`, `CALC_gain`, `CALC_yield`, `CALC_yield_average`)
SELECT `account_id`, uuid(), `user_id`, `record_date`, `record_date_month`, `record_date_year`, `marked_as_deleted`, `designation`, 30, null, null, `value`, `charge`, `BOB_category`, `category_id`, `actor`, `confirmed`, current_timestamp, `CALC_days_since_creation`, `CALC_amount_accumulated`, `CALC_amount_invested_accumulated`, `CALC_gain`, `CALC_yield`, `CALC_yield_average`
FROM `{TABLEPREFIX}record`
WHERE record_type = 0
and amount is not null
and value is not null
;

update `{TABLEPREFIX}record`
set value = null
WHERE record_type = 0
and amount is not null and value is not null
;

CREATE TABLE {TABLEPREFIX}account_mirror LIKE {TABLEPREFIX}account;
INSERT {TABLEPREFIX}account_mirror SELECT * FROM {TABLEPREFIX}account;

delete from `{TABLEPREFIX}record`
WHERE account_id not in (select account_id from {TABLEPREFIX}account_mirror)
and account_id != ''
;

update `{TABLEPREFIX}record`
set record_type = 30
WHERE account_id in (select account_id from {TABLEPREFIX}account_mirror where type = 10)
and value is not null
;

update `{TABLEPREFIX}record`
set record_type = 10
WHERE account_id in (select account_id from {TABLEPREFIX}account_mirror where type = 10)
and value is null
and amount >= 0
;

update `{TABLEPREFIX}record`
set record_type = 20, amount = -1 * amount, amount_invested = -1 * amount_invested
WHERE account_id in (select account_id from {TABLEPREFIX}account_mirror where type = 10)
and value is null
and amount < 0
;

update `{TABLEPREFIX}record`
set record_type = 30
WHERE account_id in (select account_id from {TABLEPREFIX}account_mirror where type = 12)
and `record_type` = 0
and value is not null
;
delete FROM `{TABLEPREFIX}record`
WHERE record_type = 0
and account_id = ''
and value is not null
;

DROP TABLE {TABLEPREFIX}account_mirror
;


