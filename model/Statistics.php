<?php
class Statistics
{
	function GetTotalPrivateExpenseByActor($userId) // OBSOLETE
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();

		$total = 0;

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and category_id like 'USER/%'
			and category_id not like 'USER/{USERID}'
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];

		return $total;
	}

	function GetTotalExpensePrivateAccountsForDuoCategoriesMadeByUser($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);
	
		$db = new DB();
	
		$total = 0;
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 4, 5, 10))
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];
	
		return $total;
	}
	
	function GetTotalExpensePrivateAccountsForPartnerCategoriesMadeByUser($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);
	
		$db = new DB();
	
		$total = 0;
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->GetPartnerId()."')
				or
				category_id = 'USER/".$user->GetPartnerId()."'
			)
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 4, 10))
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];
	
		return $total;
	}
	
	function GetTotalJointAccountExpenseByActor($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalJointAccountExpenseChargedPartByActor($userId) // OBSOLETE
	{
		$db = new DB();
	
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id != '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalExpenseDuoAccountsChargedForUser($userId)
	{
		$db = new DB();
	
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id != '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalExpenseDuoAccounts()
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
			and record_date <= curdate()';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}
	
	function GetTotalExpenseJointAccount() // OBSOLETE
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

	function GetTotalExpenseFromPrivateAccountChargedPartByActor($userId) // OBSOLETE
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();

		$total = 0;

		// ====================== Expenses from duo purposes
		
		// Expense from the user
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
		
		// Expense from partner
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];

		// ====================== Expenses from private account for private purposes
		
		$query = "select sum(amount * ((charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
			category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->GetPartnerId()."')
			or
			category_id like 'USER/".$user->GetPartnerId()."'
			)
			and
			(
			account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."'))
			or
			account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
			category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->getUserId()."')
			or
			category_id like 'USER/".$user->getUserId()."'
			)
			and
			(
			account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
			or
			account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
	
		return $total;
	}

	function GetTotalExpensePrivateAccountsForDuoCategoriesChargedForUser($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);
	
		$db = new DB();
	
		$total = 0;
	
		// Expense from the user
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3, 5))
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];

		return $total;
	}
	
	function GetTotalExpensePrivateAccountsForPartnerCategoriesChargedForUser($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);
	
		$db = new DB();
	
		$total = 0;
	
		// Expense from the user
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->getPartnerId()."')
				or
				category_id = 'USER/".$user->getPartnerId()."'
			)
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 4, 10))
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		return $total;
	}
	
	function GetTotalExpenseChargedPartByActor($userId) // OBSOLETE
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();

		$total = 0;

		// ====================== Expenses from duo purposes
		
		// Expense from the user
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
		
		// Expense from partner
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and record_date <= curdate()
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];

		// ====================== Expenses from private account for private purposes
		
		$query = "select sum(amount * ((charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->GetPartnerId()."')
				or
				category_id like 'USER/".$user->GetPartnerId()."'
			)
			and
			(
				account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."'))
				or
				account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->getUserId()."')
				or
				category_id like 'USER/".$user->getUserId()."'
			)
			and
			(
				account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
				or
				account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];

		// ====================== Expenses from duo account for private purposes

		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->getUserId()."')
				or
				category_id like 'USER/".$user->getUserId()."'
			)
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->GetPartnerId()."')
				or
				category_id like 'USER/".$user->GetPartnerId()."'
			)
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->getUserId()."' or coowner_user_id = '".$user->getUserId()."'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->getUserId()."')
				or
				category_id like 'USER/".$user->getUserId()."'
			)
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
			and record_date <= curdate()
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->GetPartnerId()."')
				or
				category_id like 'USER/".$user->GetPartnerId()."'
			)
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->GetPartnerId()."' or coowner_user_id = '".$user->GetPartnerId()."'))
			and record_date <= curdate()
			and user_id = '".$user->GetPartnerId()."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];

		return $total;
	}

	function GetTotalIncomeDuoAccountsByUser($userId)
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (10)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total -= $row['total'];
	
		return $total;
	}

	function GetTotalIncomeJointAccountByActor($userId) // OBSOLETE
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (10)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'";
		$row = $db->SelectRow($query);
		$total -= $row['total'];
		
		return $total;
	}

	function GetTotalRepaymentByActor($userId) // OBSOLETE
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and user_id = '".$user->getUserId()."'
			and record_group_id in (
				select record_group_id
				from {TABLEPREFIX}record
				where record_type in (10)
				and user_id = '".$user->getPartnerId()."'
			)";
		// and account_id not in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalRepaymentFromUserToPartner($userId, $partnerId)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (3))
			and user_id = '".$userId."'
			and record_group_id in (
				select record_group_id
				from {TABLEPREFIX}record
				where record_type in (10)
				and record_date <= curdate()
				and account_id not in (select account_id from {TABLEPREFIX}account where type in (3))
				and user_id = '".$partnerId."'
			)";
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