<?php
class RecordsManager
{
	function GetAllRecords($month)
	{
		$accountsHandler = new AccountsManager();
		$activeAccount = $accountsHandler->GetCurrentActiveAccount();

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
	
		$db = new DB();
	
		$query = "select r.*, (amount * (charge / 100)) as part_actor1, (amount * ((100 - charge) / 100)) as part_actor2, c.category, c.link_type, u.name as user_name, a.name as account_name, a.type as account_type
			from {TABLEPREFIX}record r
			left join {TABLEPREFIX}category c on r.category_id = c.category_id
			inner join {TABLEPREFIX}user u on r.user_id = u.user_id
			left join {TABLEPREFIX}account a on r.account_id = a.account_id
			where record_date < adddate(curdate(), interval 2 month) ".
			($activeAccount->get('type') > 0 ? "and r.account_id = '{ACCOUNTID}'" : "")." 
			and r.user_id = '".$user->getUserId()."'
			and record_type in (1, 4, 22)
			and
			(
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."')
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
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."')
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
				r.account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."')
				or
				r.account_id = ''
			) 
			order by record_date desc, creation_date desc";
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
		and user_id = '".$_SESSION['user_id']."'
		and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$_SESSION['user_id']."' or coowner_user_id = '".$_SESSION['user_id']."'))";
		$row = $db->SelectRow($query);
		$total += $row['total'];

		return $total;
	}

	function ListDesignation($searchString)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		$db = new DB();

		$query = "select designation, count(*) as total
			from {TABLEPREFIX}record
			where designation like '%".$searchString."%'
			and account_id in (select account_id from {TABLEPREFIX}account where owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."')
			group by designation
			order by count(*) desc";
		$result = $db->Select($query);
		return $result;
	}
}

