<?php
class AccountsManager
{
	function GetAllAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*, PRF.sort_order
			from {TABLEPREFIX}account as ACC
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = \'{USERID}\' 
			where ACC.owner_user_id = \'{USERID}\'
			or ACC.coowner_user_id = \'{USERID}\'
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
			where owner_user_id = \'{USERID}\'
			or coowner_user_id = \'{USERID}\'';
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

		if ($_SESSION['account_id'] == 'dashboard')
		{
			$newAccount->setType(0);
		}
		else
		{
			$db = new DB();
		
			$query = 'select *
				from {TABLEPREFIX}account
				where (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\')
				and account_id = \'{ACCOUNTID}\'';
			$row = $db->SelectRow($query);
			$newAccount->hydrate($row);
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
	
		$query = 'select *
			from {TABLEPREFIX}account
			where owner_user_id = \'{USERID}\'
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
	
	function GetAllDuoAccounts()
	{
		$accounts = array();
	
		$db = new DB();
	
		$query = 'select *
		from {TABLEPREFIX}account
		where (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\')
		and type = 3';
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
			where ACC.account_id = \''.$id.'\'';
		$row = $db->SelectRow($query);
		$newAccount->hydrate($row);

		return $newAccount;
	}

	function InsertAccount($name, $owner, $coowner, $type, $openingBalance, $expectedMinimumBalance, $sortOrder)
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
		
		$query = sprintf("insert into {TABLEPREFIX}account (account_id, name, type, owner_user_id, coowner_user_id, opening_balance, expected_minimum_balance, creation_date)
				values ('%s', '%s', %s, '%s', '%s', %s, %s, CURRENT_TIMESTAMP())",
				$uuid,
				$name,
				$type,
				$owner,
				$coowner,
				$openingBalance,
				$expectedMinimumBalance);
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
	
		$query = sprintf("delete from {TABLEPREFIX}account where account_id = '%s'",
				$accountId);
		$result = $db->Execute($query);
		
		$query = sprintf("delete from {TABLEPREFIX}account_user_preference where account_id = '%s' and user_id = '{USERID}'",
				$accountId);
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateAccount($accountId, $name, $openingBalance, $expectedMinimumBalance, $sortOrder)
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

		$query = sprintf("update {TABLEPREFIX}account set name = '%s', opening_balance = %s, expected_minimum_balance = %s where account_id = '%s'",
				$name,
				$openingBalance,
				$expectedMinimumBalance,
				$accountId);
		
		$result = $db->Execute($query);
		
		$query = sprintf("update {TABLEPREFIX}account_user_preference set sort_order = %s where account_id = '%s' and user_id = '{USERID}'",
				$sortOrder,
				$accountId);

		$result = $db->Execute($query);
	
		return $result;
	}
}

