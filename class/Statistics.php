<?php
class Statistics
{
	function GetTotalPrivateExpenseByActor($actor)
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 1
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalExpenseJointAccount()
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 4
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalExpenseChargedPartByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (1, 4)
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = 'select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (1, 4)
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
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
			where record_type = 3
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
			and record_date <= curdate()
			and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalRepaymentByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 0
			and marked_as_deleted = 0
			and account_id = \'{ACCOUNTID}\'
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
			and account_id = \'{ACCOUNTID}\'
			and record_date < adddate(curdate(), interval +'.$numberOfDays.' day)';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}
}