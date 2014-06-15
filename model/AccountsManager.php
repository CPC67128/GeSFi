<?php
class AccountsManager
{
	function GetAccountTypes()
	{
		$types = array
		(
			1 => 'Compte privé',
			2 => 'Compte duo virtuel',
			3 => 'Compte duo',
			4 => 'Compte d\'optimisation financière',
			5 => 'Prêt', // TODO : créér distinction entre prêt en indivision
			10 => 'Placement bancaire',
			11 => 'Immobilier',
			12 => 'Immobilier en indivision'
		);

		return $types;
	}

	function GetAllAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\' 
			where (ACC.owner_user_id = \'{USERID}\'
			or ACC.coowner_user_id = \'{USERID}\')
			and marked_as_closed = 0
			order by PRF.sort_order';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}

		return $accounts;
	}

	function GetAllOrdinaryAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\' 
			where (ACC.owner_user_id = \'{USERID}\'
			or ACC.coowner_user_id = \'{USERID}\')
			and ACC.type < 10 
			and marked_as_closed = 0
			order by PRF.sort_order';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}

		return $accounts;
	}

	function GetAllInvestmentAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\' 
			where (ACC.owner_user_id = \'{USERID}\'
			or ACC.coowner_user_id = \'{USERID}\')
			and ACC.type >= 10 and ACC.type <= 19
			and marked_as_closed = 0
			order by PRF.sort_order';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}

		return $accounts;
	}

	function GetAllPrivateInvestmentAccounts()
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
		from {TABLEPREFIX}account as ACC
		left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\'
		where (ACC.owner_user_id = \'{USERID}\'
		or ACC.coowner_user_id = \'{USERID}\')
		and ACC.type >= 10 and ACC.type <= 11
		and marked_as_closed = 0
		order by PRF.sort_order';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}
	
		return $accounts;
	}

	function GetAllSharedInvestmentAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\' 
			where (ACC.owner_user_id = \'{USERID}\'
			or ACC.coowner_user_id = \'{USERID}\')
			and ACC.type in (12)
			and marked_as_closed = 0
			order by PRF.sort_order';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}

		return $accounts;
	}

	function GetDefaultAccount()
	{
		$db = new DB();

		$newAccount = null;

		$query = 'select *
			from {TABLEPREFIX}account
			where (owner_user_id = \'{USERID}\'
			or coowner_user_id = \'{USERID}\')
			and marked_as_closed = 0';
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
		}

		return $newAccount;
	}
	
	function GetCurrentActiveAccount()
	{
		$newAccount = new Account();

		if (!isset($_SESSION['account_id']))
		{
			$newAccount->set('type', 0);
		}
		elseif ($_SESSION['data'] == 'configuration')
		{
			$newAccount->set('type', -100);
		}
		elseif ($_SESSION['page'] == 'dashboard')
		{
			$newAccount->set('type', 0);
		}
		elseif ($_SESSION['data'] == 'asset_management' && $_SESSION['account_id'] == '')
		{
			$newAccount->set('type', 100);
		}
		elseif ($_SESSION['account_id'] == '')
		{
			$newAccount->set('type', 0);
			$newAccount->set('accountId', '');
		}
		elseif ($_SESSION['account_id'] == 'all_accounts')
		{
			$newAccount->set('type', 0);
		}
		else
		{
			$db = new DB();
		
			$query = 'select *
				from {TABLEPREFIX}account
				where (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\')
				and account_id = \'{ACCOUNTID}\'
				and marked_as_closed = 0';
			$row = $db->SelectRow($query);

			if (is_array($row))
				$newAccount->hydrate($row);
			else
				throw new Exception("Unknown active account, account_id = ".$_SESSION['account_id']);
		}
		return $newAccount;
	}

	function GetAllPrivateAccountsForDuo($idDuoAccount)
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = 'select *
			from {TABLEPREFIX}account
			where
			(
			owner_user_id = (select owner_user_id from {TABLEPREFIX}account where account_id = \''.$idDuoAccount.'\')
			or
			owner_user_id = (select coowner_user_id from {TABLEPREFIX}account where account_id = \''.$idDuoAccount.'\')
			)
			and marked_as_closed = 0
			and type = 1';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}

		return $accounts;
	}

	function GetAllPrivateAccounts()
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = "select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account
			 as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = '{USERID}'
			where ACC.owner_user_id = '{USERID}'
			and marked_as_closed = 0
			and type in (1, 4)
			order by PRF.sort_order";

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}
	
		return $accounts;
	}
	
	function GetAllDuoAccounts()
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = "select ACC.*, PRF.sort_order
		from {TABLEPREFIX}account as ACC
		left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = '{USERID}'
		where (ACC.owner_user_id = '{USERID}' or ACC.coowner_user_id = '{USERID}')
		and marked_as_closed = 0
		and type in (2, 3, 5)
		order by PRF.sort_order"; // todo : put 5 back or not in list of type

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}
	
		return $accounts;
	}
	
	function GetAllSharedLoans()
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = "select ACC.*, PRF.sort_order
		from {TABLEPREFIX}account as ACC
		left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = '{USERID}'
		where (ACC.owner_user_id = '{USERID}' or ACC.coowner_user_id = '{USERID}')
		and marked_as_closed = 0
		and type in (5)
		order by PRF.sort_order";

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newAccount = new Account();
			$newAccount->hydrate($row);
			array_push($accounts, $newAccount);
		}
	
		return $accounts;
	}

	function GetAccount($id)
	{
		$newAccount = new Account();
		
		$db = new DB();

		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\'
			where ACC.account_id = \''.$id.'\'
			and marked_as_closed = 0';
		$row = $db->SelectRow($query);
		$newAccount->hydrate($row);

		return $newAccount;
	}

	function InsertAccount($name, $owner, $coowner, $type, $openingBalance, $expectedMinimumBalance, $sortOrder, $minimumCheckPeriod)
	{
		$db = new DB();

		$uuid = $db->GenerateUUID();

		$originalSortOrder = $sortOrder;
		$continue = true;
		
		$updateQueries = array();
		while ($continue)
		{
			$query = sprintf("select count(*) as total from {TABLEPREFIX}account_user_preference where sort_order = %s and user_id = '{USERID}'",
					$sortOrder);
			$row = $db->SelectRow($query);
		
			if ($row['total'] == 0)
				$continue = false;
			else
			{
				$query = sprintf("update {TABLEPREFIX}account_user_preference set sort_order = sort_order + 1 where sort_order = %s and user_id = '{USERID}'",
						$sortOrder);
				array_push($updateQueries, $query);
			}
			$sortOrder++;
		}
		
		for ($i = (count($updateQueries) - 1); $i >= 0; $i--)
		{
			$db->Execute($updateQueries[$i]);
		}
		
		$sortOrder = $originalSortOrder;
		
		$query = sprintf("insert into {TABLEPREFIX}account (account_id, name, type, owner_user_id, coowner_user_id, opening_balance, expected_minimum_balance, minimum_check_period, creation_date)
				values ('%s', '%s', %s, '%s', '%s', %s, %s, %s, CURRENT_TIMESTAMP())",
				$uuid,
				$name,
				$type,
				$owner,
				$coowner,
				$openingBalance,
				$expectedMinimumBalance,
				$minimumCheckPeriod);
		$result = $db->Execute($query);

		$query = sprintf("insert into {TABLEPREFIX}account_user_preference (user_id, account_id, sort_order)
				values ('{USERID}', '%s', %s)",
				$uuid,
				$sortOrder);
		$result = $db->Execute($query);

		return $result;
	}

	function DeleteAccount($accountId)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}account set marked_as_closed = 1 where account_id = '%s'",
				$accountId);
		$result = $db->Execute($query);
		
		$query = sprintf("delete from {TABLEPREFIX}account_user_preference where account_id = '%s' and user_id = '{USERID}'",
				$accountId);
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateAccount($accountId, $name, $description, $openingBalance, $expectedMinimumBalance, $sortOrder, $minimumCheckPeriod, $creationDate, $availabilityDate)
	{
		$db = new DB();

		$query = sprintf("update {TABLEPREFIX}account set name = '%s', description='%s', opening_balance = %s, expected_minimum_balance = %s, minimum_check_period = %s, creation_date = '%s', availability_date = '%s' where account_id = '%s'",
				$db->ConvertStringForSqlInjection($name),
				$db->ConvertStringForSqlInjection($description),
				$openingBalance,
				$expectedMinimumBalance,
				$minimumCheckPeriod,
				$creationDate,
				$availabilityDate,
				$accountId);
		
		$result = $db->Execute($query);

		$result = $this->UpdateAccountSortOrder($accountId, $sortOrder);

		return $result;
	}

	
	function UpdateAccountSortOrder($accountId, $sortOrder)
	{
		$db = new DB();
	
		$originalSortOrder = $sortOrder;
		$continue = true;
	
		$updateQueries = array();
		while ($continue)
		{
			$query = sprintf("select count(*) as total from {TABLEPREFIX}account_user_preference where sort_order = %s and user_id = '{USERID}' and account_id != '%s'",
					$sortOrder,
					$accountId);
			$row = $db->SelectRow($query);
	
			if ($row['total'] == 0)
				$continue = false;
			else
			{
				$query = sprintf("update {TABLEPREFIX}account_user_preference set sort_order = sort_order + 1 where sort_order = %s and user_id = '{USERID}' and account_id != '%s'",
						$sortOrder,
						$accountId);
				array_push($updateQueries, $query);
			}
			$sortOrder++;
		}
	
		for ($i = (count($updateQueries) - 1); $i >= 0; $i--)
		{
		$db->Execute($updateQueries[$i]);
		}
	
		$sortOrder = $originalSortOrder;

		$query = sprintf("delete from {TABLEPREFIX}account_user_preference where account_id = '%s' and user_id = '{USERID}'",
				$accountId);
		$result = $db->Execute($query);

		$query = sprintf("insert into {TABLEPREFIX}account_user_preference (sort_order, account_id, user_id) values (%s, '%s', '{USERID}')",
				$sortOrder,
				$accountId);
		$result = $db->Execute($query);

		return $result;
	}
}

