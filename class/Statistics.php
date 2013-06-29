<?php
class Statistics
{
	function GetTotalPrivateExpenseByActor($actor)
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalJointAccountExpenseByActor($actor)
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalJointAccountExpenseChargedPartByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount * (charge / 100)) as total
		from {TABLEPREFIX}record
		where record_type in (22)
		and marked_as_deleted = 0
		and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
		and record_date <= curdate()
		and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = 'select sum(amount * ((100 - charge) / 100)) as total
		from {TABLEPREFIX}record
		where record_type in (22)
		and marked_as_deleted = 0
		and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
		and record_date <= curdate()
		and actor != '.$actor;
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalExpenseJointAccount()
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalExpenseFromPrivateAccountChargedPartByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = 'select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor != '.$actor;
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalExpenseChargedPartByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = 'select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor != '.$actor;
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalIncomeJointAccountByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (10)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total = $row['total'];

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total -= $row['total'];
		
		return $total;
	}

	function GetTotalRepaymentByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetJointAccountPlannedDebit($numberOfDays)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 4
			and marked_as_deleted = 0
			and record_date > curdate()
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date < adddate(curdate(), interval +'.$numberOfDays.' day)';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}
}