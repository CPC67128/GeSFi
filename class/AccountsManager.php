<?php
class AccountsManager
{
	function GetAllAccounts()
	{
		$accounts = array();

		$db = new DB();
	
		$query = 'select ACC.*
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
		$db = new DB();
	
		$query = 'select *
			from {TABLEPREFIX}account
			where (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\')
			and account_id = \'{ACCOUNTID}\'';
		$row = $db->SelectRow($query);
		$newAccount = new Account();
		$newAccount->hydrate($row);

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

	function GetAccount($id)
	{
		$db = new DB();
	
		$query = 'select *
			from {TABLEPREFIX}account
			where account_id = \''.$id.'\'';
		$row = $db->SelectRow($query);
		$newAccount = new Account();
		$newAccount->hydrate($row);
	
		return $newAccount;
	}
	
	
}

