<?php
class RecordsHandler extends Handler
{
	function GetRecordTypeGroups()
	{
		$types = array
		(
				0 => 'In',
				3 => 'In',
				10 => 'TrIn',
				11 => 'TrIn',
				12 => 'In',
				20 => 'TrOut',
				21 => 'TrOut',
				22 => 'Out'
		);
	
		return $types;
	}

	/*****************/

	function Insert($record)
	{
		$db = new DB();

		$query = sprintf("insert into {TABLEPREFIX}record (account_id, record_id, record_group_id,
				user_id, record_date, record_date_month, record_date_year, marked_as_deleted,
				designation, record_type, amount, amount_invested, value, withdrawal, income,
				charge, category_id, actor, confirmed)

				values ('%s', uuid(), '%s',
				'%s', '%s', %s, %s, %s,
				%s, %s, %s, %s, %s, %s, %s,
				%s, '%s', %s, %s)",
	
				$record->get('accountId'),
				$record->getIfSetOrDefault('recordGroupId', ''),
				
				$record->getIfSetOrDefault('userId', ''),
				$record->get('recordDate'),
				$record->get('recordDateMonth'),
				$record->get('recordDateYear'),
				$record->getIfSetOrDefault('markedAsDeleted', '0'),

				$db->ConvertStringForSqlInjection($record->get('designation')),
				$record->get('recordType'),
				$record->getIfSetOrDefault('amount', 'null'),
				$record->getIfSetOrDefault('amountInvested', 'null'),
				$record->getIfSetOrDefault('value', 'null'),
				$record->getIfSetOrDefault('withdrawal', 'null'),
				$record->getIfSetOrDefault('income', 'null'),

				$record->getIfSetOrDefault('charge', '0'),
				$record->getIfSetOrDefault('category', ''),
				$record->getIfSetOrDefault('actor', '0'),
				$record->getIfSetOrDefault('confirmed', '0')
				);
		//throw new Exception($query);
		$result = $db->Execute($query);
	
		return $result;
	}



	function InsertRecord($accountId,
			$userId,
			$actor,
			$recordDate,
			$amount,
			$designation,
			$charge,
			$category,
			$recordType,
			$confirmed,
			$recordGroupId)
	{
		$db = new DB();
	
		$query = sprintf("insert into {TABLEPREFIX}record (account_id, user_id, record_date, marked_as_deleted, designation, record_type, amount, actor, charge, category_id, record_group_id, record_id, confirmed)
				values ('%s', '%s', '%s', 0, %s, %s, %s, %s, %s, '%s', '%s', uuid(), %s)",
				$accountId,
				$userId,
				$recordDate,
				$db->ConvertStringForSqlInjection($designation),
				$recordType,
				$amount,
				$actor == null ? 0 : $actor,
				$charge,
				$category == null ? "" : $category,
				$recordGroupId == null ? "" : $recordGroupId,
				$confirmed);
		$result = $db->Execute($query);
	
		$queryMonthYearFill = "update {TABLEPREFIX}record set record_date_month = month(record_date), record_date_year = year(record_date) where record_date_month = -1";
		$resultMonthYearFill = $db->Execute($queryMonthYearFill);
	
		return $result;
	}
/*
	function InsertRecord_Remark($accountId, $userId, $recordDate, $designation)
	{
		return $this->InsertRecord($accountId,
				$userId,
				null,
				$recordDate,
				0,
				$designation,
				0,
				null,
				2,
				0,
				null);
	}
*/
	
	function InsertRecord_AmountTransfer($accountId, $userId, $recordDate, $amount, $designation, $recordType, $recordGroupId)
	{
		return $this->InsertRecord($accountId,
				$userId,
				null,
				$recordDate,
				$amount,
				$designation,
				0,
				null,
				$recordType,
				0,
				$recordGroupId);
	}
	
	function DeleteRecord($recordId)
	{
		$db = new DB();

		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$recordId."'";
		$row = $db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set marked_as_deleted = 1 where record_group_id = '".$row['record_group_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set marked_as_deleted = 1 where record_id = '".$recordId."'";
		}
		$result = $db->Execute($sql);

		return $result;
	}
	/***************************/

	function GetRecordTypeGroup($recordType)
	{
		return $this->GetRecordTypeGroups()[$recordType];
	}

	function GetAllRecords($month)
	{
		$accountsHandler = new AccountsHandler();
		$activeAccount = $accountsHandler->GetCurrentActiveAccount();

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
	
		$db = new DB();
	
		/*
		$query = "select r.*, (amount * (charge / 100)) as part_actor1, (amount * ((100 - charge) / 100)) as part_actor2, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and r.user_id = '".$user->get('userId')."'
			and record_type in (1, 4, 22)
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			) 
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)

			union all

			select r.*, (amount * ((100 - charge) / 100)) as part_actor1, (amount * (charge / 100)) as part_actor2, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and r.user_id = '".$user->GetPartnerId()."'
			and record_type in (1, 4, 22)
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			) 
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)

			union all

			select r.*, 0 as part_actor2, 0 as part_actor1, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and record_type not in (1, 4, 22)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			) 
			order by record_date desc, creation_date desc";
		*/

		$query = "select r.*, (amount * (charge / 100)) as part_actor1, (amount * ((100 - charge) / 100)) as part_actor2, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and r.user_id = '".$user->get('userId')."'
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			) 
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)

			union all

			select r.*, (amount * ((100 - charge) / 100)) as part_actor1, (amount * (charge / 100)) as part_actor2, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and r.user_id = '".$user->GetPartnerId()."'
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			) 
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)

			order by record_date desc, creation_date desc";
		
		$result = $db->Select($query);
		return $result;
	}

	function GetAllRecordsOfCategory($month, $categoryId, $categoryId2)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
	
		$db = new DB();
	
		$query = "select r.*, (amount * (charge / 100)) as part_actor1, (amount * ((100 - charge) / 100)) as part_actor2, c.category, c.link_type, c.type as category_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month)
			and r.category_id in ('".$categoryId."', '".$categoryId2."')
			and r.user_id = '".$user->get('userId')."'
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)
	
			union all
	
			select r.*, (amount * ((100 - charge) / 100)) as part_actor1, (amount * (charge / 100)) as part_actor2, c.category, c.link_type, c.type as category_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month)
			and r.category_id in ('".$categoryId."', '".$categoryId2."')
			and r.user_id = '".$user->GetPartnerId()."'
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
				or
				r.account_id = ''
			)
			and record_date > adddate((select max(record_date) from {TABLEPREFIX}record where record_date <= curdate() and user_id = '{USERID}'), interval -".$month." month)
	
			order by record_date desc, creation_date desc";
	
		$result = $db->Select($query);
		return $result;
	}

	function ListDesignation($searchString, $type)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
	
		$db = new DB();
		$searchString = $db->ConvertStringForSqlInjection('%'.$searchString.'%');

		$typeStart = $type.'0';
		$typeEnd = $type.'9';

		if ($type == 0)
		{
			$typeStart = 0;
			$typeEnd = 99;
		}

		$query = "select designation, count(*) as total
			from {TABLEPREFIX}record
			where designation like ".$searchString."
			and record_type >= ".$typeStart." and record_type <= ".$typeEnd."
			and account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
			group by designation
			order by count(*) desc";
		$result = $db->Select($query);
		return $result;
	}

	function RenameDesignation($source, $destination)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		$db = new DB();
		$source = $db->ConvertStringForSqlInjection($source);
		$destination = $db->ConvertStringForSqlInjection($destination);

		$query = "update {TABLEPREFIX}record
			set designation = '".$destination."'
			where designation = '".$source."'
			and account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')";
		$result = $db->_Execute($query, false);
		return $result;
	}
}

