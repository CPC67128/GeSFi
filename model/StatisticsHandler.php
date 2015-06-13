<?php
class StatisticsHandler extends Handler
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
			and user_id = '".$user->get('userId')."'";
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
			and user_id = '".$user->get('userId')."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];

		return $total;
	}

	// Total payments made from private accounts for payments to duo categories
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
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
			and user_id = '".$userId."'"; // TODO Liste de comptes à vérifier
		$row = $db->SelectRow($query);
		$total += $row['total'];
	
		return $total;
	}

	// Total payments made from private accounts for payments to duo categories
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
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
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
			and user_id = '".$user->get('userId')."'";
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

	// Total of payments made from duo accounts, part charged for user in parameter
	function GetTotalExpenseDuoAccountsChargedForUser($userId)
	{
		$db = new DB();
	
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and record_date <= curdate()
			and user_id != '".$userId."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];
	
		return $total;
	}

	// Total of payments made from duo accounts
	function GetTotalExpenseDuoAccounts()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	// Total deposits coming from outside to duo accounts
	function GetTotalDepositFromOutsideToDuoAccounts()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 12
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()";
		$row = $db->SelectRow($query);

		// TODO: check categories here - we should only have duo categories
		// TODO: this should not use {USERID}

		return $row['total'];
	}

	// Total deposits coming from outside to duo accounts
	function GetTotalDepositFromOutsideToDuoAccountsForUser($userId)
	{
		$db = new DB();

		/*
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 12
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()";
		$row = $db->SelectRow($query);
		*/

		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id != '".$userId."'";
		$row = $db->SelectRow($query);
		$total += $row['total'];
	
		// TODO: check categories here - we should only have duo categories
		// TODO: this should not use {USERID}
	
		return $total;
	}
	

	// Total deposits coming from outside to private accounts
	function GetTotalDepositFromOutsideToPrivateAccounts($userId)
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser($userId);

		$db = new DB();
	
		// $query = "select sum(amount * ((100 - charge) / 100)) as total
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 12
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$user->getDuoId()."')
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (1) and owner_user_id = '".$userId."')
			and record_date <= curdate()";
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
			and user_id = '".$user->get('userId')."'";
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
			account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."'))
			or
			account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->get('userId')."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
			category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->get('userId')."')
			or
			category_id like 'USER/".$user->get('userId')."'
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
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
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
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
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
			and user_id = '".$user->get('userId')."'";
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
				account_id in (select account_id from {TABLEPREFIX}account where type in (1) and (owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."'))
				or
				account_id = ''
			)
			and record_date <= curdate()
			and user_id = '".$user->get('userId')."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->get('userId')."')
				or
				category_id like 'USER/".$user->get('userId')."'
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
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->get('userId')."')
				or
				category_id like 'USER/".$user->get('userId')."'
			)
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."'))
			and record_date <= curdate()
			and user_id = '".$user->get('userId')."'";
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
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '".$user->get('userId')."' or coowner_user_id = '".$user->get('userId')."'))
			and record_date <= curdate()
			and user_id = '".$user->get('userId')."'";
		$row = $db->SelectRow($query);
		$total = $total + $row['total'];
		
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and
			(
				category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$user->get('userId')."')
				or
				category_id like 'USER/".$user->get('userId')."'
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

	// Total of money brought by one user to duo accounts
	function GetTotalIncomeDuoAccountsByUser($userId)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (10)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and
			(
				record_group_id in
				(
					select distinct record_group_id
					from {TABLEPREFIX}record
					where record_type in (20)
					and
					(
						account_id in (select account_id from {TABLEPREFIX}account where type not in (2, 3) and owner_user_id = '".$userId."')
						or
						(account_id = '' and user_id = '".$userId."')
					)
					union
					select distinct record_group_id
					from {TABLEPREFIX}record
					where account_id in (select account_id from {TABLEPREFIX}account where type not in (2, 3) and owner_user_id = '".$userId."')
					and record_type = 0
					and amount is not null
					and amount < 0
				)
				or
				(record_group_id = '' and user_id = '".$userId."')
			)
			and record_date <= curdate()";
		$row = $db->SelectRow($query);
		//die($db->Parse($query));
		$total = $row['total'];

		return $total;
	}
	
	// Total of money taken by one user from duo accounts
	function GetTotalOutcomeFromDuoAccountsByUser($userId)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			and
			(
				record_group_id in
				(
					select distinct record_group_id
					from {TABLEPREFIX}record
					where record_type in (10)
					and
					(
						account_id in (select account_id from {TABLEPREFIX}account where type not in (2, 3) and owner_user_id = '".$userId."')
						or
						(account_id = '' and user_id = '".$userId."')
					)
					union
					select distinct record_group_id
					from {TABLEPREFIX}record
					where account_id in (select account_id from {TABLEPREFIX}account where type not in (2, 3) and owner_user_id = '".$userId."')
					and record_type = 0
					and amount is not null
					and amount > 0
				)
				or
				(record_group_id = '' and user_id = '".$userId."')
				
			)
			and record_date <= curdate() ";
		//die($db->Parse($query));
		$row = $db->SelectRow($query);
		$total = $row['total'];
	
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
			and user_id = '".$user->get('userId')."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			and user_id = '".$user->get('userId')."'";
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
			and user_id = '".$user->get('userId')."'
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

	// Total repayment from user to partner
	function GetTotalRepaymentFromUserToPartner($userId, $partnerId)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (20)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
			and user_id = '".$userId."'
			and record_group_id in (
				select record_group_id
				from {TABLEPREFIX}record
				where record_type in (10)
				and record_date <= curdate()
				and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
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