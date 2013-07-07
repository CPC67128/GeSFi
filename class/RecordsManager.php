<?php
class RecordsManager
{
	function GetAllRecords($month)
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select r.*, (amount * (charge / 100)) as part_actor1, (amount * ((100 - charge) / 100)) as part_actor2, c.category, c.link_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id 
			where record_date < adddate(curdate(), interval 2 month)
			and account_id = \'{ACCOUNTID}\'
			and actor = 1
			and record_type in (1, 4, 22)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and account_id = \'{ACCOUNTID}\'), interval -'.$month.' month)
			union all
			select r.*, (amount * ((100 - charge) / 100)) as part_actor1, (amount * (charge / 100)) as part_actor2, c.category, c.link_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			where record_date < adddate(curdate(), interval 2 month)
			and account_id = \'{ACCOUNTID}\'
			and actor = 2
			and record_type in (1, 4, 22)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and account_id = \'{ACCOUNTID}\'), interval -'.$month.' month)
			union all
			select r.*, 0 as part_actor2, 0 as part_actor1, c.category, c.link_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			where record_date < adddate(curdate(), interval 2 month)
			and account_id = \'{ACCOUNTID}\'
			and record_type not in (1, 4, 22)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and account_id = \'{ACCOUNTID}\'), interval -'.$month.' month)
			order by record_date desc, creation_date desc';
		$result = $db->Select($query);
		return $result;
	}

	function GetTotalOutcomeToDuoAccount($month, $year)
	{
		$total = 0;

		$db = new DB();
		
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (12)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and record_date_month = ".$month."
		and record_date_year = ".$year."
		and actor = 1
		and account_id in (select account_id from {TABLEPREFIX}account where type = 2 and owner_user_id = '".$_SESSION['user_id']."')";
		$row = $db->SelectRow($query);
		$total += $row['total'];
		
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (12)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and record_date_month = ".$month."
		and record_date_year = ".$year."
		and actor = 2
		and account_id in (select account_id from {TABLEPREFIX}account where type = 2 and coowner_user_id = '".$_SESSION['user_id']."')";
		$row = $db->SelectRow($query);
		$total += $row['total'];
		
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (10)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and record_date_month = ".$month."
		and record_date_year = ".$year."
		and actor = 1
		and account_id in (select account_id from {TABLEPREFIX}account where type = 3 and owner_user_id = '".$_SESSION['user_id']."')";
		$row = $db->SelectRow($query);
		$total += $row['total'];
		
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (10)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and record_date_month = ".$month."
		and record_date_year = ".$year."
		and actor = 2
		and account_id in (select account_id from {TABLEPREFIX}account where type = 3 and coowner_user_id = '".$_SESSION['user_id']."')";
		$row = $db->SelectRow($query);
		$total += $row['total'];

		return $total;
	}
}

