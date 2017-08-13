<?php
class StatisticsBalanceHandler extends Handler
{
	public $totalContributionOfUser = 0;
	public $totalContributionOfPartner = 0;
	public $totalExpenses = 0;
	public $totalExpensesChargedToUser = 0;
	public $totalExpensesChargedToPartner = 0;

	public $usersHandler;
	public $user;
	public $partner;
	public $userId;
	public $db;
	public $year, $month;

	public $debug = false;
	public $aggregate = true;
	protected $recordFilter = '';

	function __construct($year, $month, $aggregate)
	{
		$this->usersHandler = new UsersHandler();
		$this->user = $this->usersHandler->GetCurrentUser();
		$this->partner = $this->usersHandler->GetUser($this->user->GetPartnerId());

		$this->userId = $this->user->get('userId');

		$this->db = new DB();
		$this->year = $year;
		$this->month = $month;

		$this->aggregate = $aggregate;
		if ($aggregate)
			//$this->recordFilter = "and (record_date_year < ".$this->year." or (record_date_month <= ".$this->month." and record_date_year = ".$this->year."))";
			// Problem because numbers are not displayed correctly during the month
			$this->recordFilter = "and record_date_year < curdate()";
		else
			$this->recordFilter = "and record_date_year = ".$this->year." and record_date_month = ".$this->month;

		$this->RefreshBalance();
	}

	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser = 0;
	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser = 0;
	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner = 0;
	
	function ExpenseFromPrivateAccountToDuoCategories()
	{
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$this->user->get('duoId')."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
			".$this->recordFilter."
			and user_id = '".$this->userId."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner = $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser - $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser;
	}

	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser = 0;
	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser = 0;
	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner = 0;

	function ExpenseFromPrivateAccountToPartnerCategories()
	{
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
				from {TABLEPREFIX}record
				where record_type in (22)
				and marked_as_deleted = 0
				and
				(
					category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$this->user->GetPartnerId()."')
					or
					category_id = 'USER/".$this->user->GetPartnerId()."'
				)
				and record_date <= curdate()
				".$this->recordFilter."
				and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
				and user_id = '".$this->userId."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner = $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser - $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser;
	}

	//TODO en cours
	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser = 0;
	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser = 0;
	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToPartner = 0;

	function ExpenseFromPrivateAccountToUserCategories()
	{
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
				from {TABLEPREFIX}record
				where record_type in (22)
				and marked_as_deleted = 0
				and
				(
					category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$this->userId."')
					or
					category_id = 'USER/".$this->userId."'
				)
				and record_date <= curdate()
				".$this->recordFilter."
				and account_id in (select account_id from {TABLEPREFIX}account where type in (1))
				and user_id = '".$this->userId."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToPartner = $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser - $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser;
	}

	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner;
	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner;
	public $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser;
	
	function ExpenseFromPrivateAccountsToDuoCategoriesMadeByPartner()
	{
		// ---------- Expense from private account to duo categories
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$this->user->get('duoId')."')
			and record_date <= curdate()
			and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
			".$this->recordFilter."
			and user_id = '".$this->partner->get('userId')."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser = $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner - $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner;
	}

	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner;
	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner;
	public $totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToUser;

	function ExpenseFromPrivateAccountToUserCategoriesMadeByPartner()
	{
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
				from {TABLEPREFIX}record
				where record_type in (22)
				and marked_as_deleted = 0
				and
				(
					category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$this->userId."')
					or
					category_id = 'USER/".$this->userId."'
				)
				and record_date <= curdate()
				".$this->recordFilter."
				and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
				and user_id = '".$this->partner->get('userId')."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToUser = $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner - $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner;
	}

	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner;
	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner;
	public $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser;

	function ExpenseFromPrivateAccountToPartnerCategoriesMadeByPartner()
	{
		$query = "select sum(amount) as total, sum(amount * (charge / 100)) as totalCharged
				from {TABLEPREFIX}record
				where record_type in (22)
				and marked_as_deleted = 0
				and
				(
					category_id in (select category_id from {TABLEPREFIX}category where link_type = 'USER' and link_id = '".$this->user->GetPartnerId()."')
					or
					category_id = 'USER/".$this->user->GetPartnerId()."'
				)
				and record_date <= curdate()
				".$this->recordFilter."
				and account_id in (select account_id from {TABLEPREFIX}account where type in (1))
				and user_id = '".$this->partner->get('userId')."'";
		$row = $this->db->SelectRow($query);
	
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner = $row['total'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner = $row['totalCharged'] ?: 0;
		$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser = $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner - $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner;
	}

	public $totalIncomeDuoAccountsMadeByUser;
	public $totalIncomeDuoAccountsMadeByPartner;
	
	function GetTotalIncomeDuoAccountsByUser($userId, &$value)
	{
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
			".$this->recordFilter."
			and record_date <= curdate()";
		$row = $this->db->SelectRow($query);
		$value = $row['total'] ?: 0;
	}

	public $totalIncomeDuoAccountsOutsidePartners;
	public $totalIncomeDuoAccountsOutsidePartnersForUser;
	public $totalIncomeDuoAccountsOutsidePartnersForPartner;
/*
	// Total deposits coming from outside to duo accounts
	function GetTotalDepositFromOutsideToDuoAccounts(&$value)
	{
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 12
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()";
		$row = $this->db->SelectRow($query);
	
		// TODO: check categories here - we should only have duo categories
		// TODO: this should not use {USERID}
	
		$value = $row['total'];
	}
*/
	// Total deposits coming from outside to duo accounts
	function GetTotalDepositFromOutsideToDuoAccountsForUser($userId, &$value)
	{
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
			".$this->recordFilter."
			and user_id = '".$userId."'";
		$row = $this->db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
			and record_date <= curdate()
			".$this->recordFilter."
			and user_id != '".$userId."'";
		$row = $this->db->SelectRow($query);
		$total += $row['total'];
	
		// TODO: check categories here - we should only have duo categories
		// TODO: this should not use {USERID}
	
		$value = $total ?: 0;
	}

	public $totalOutcomeDuoAccountsMadeByUser;
	public $totalOutcomeDuoAccountsMadeByPartner;

	// Total of money taken by one user from duo accounts
	function GetTotalOutcomeFromDuoAccountsByUser($userId, &$value)
	{
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
			".$this->recordFilter."
			and record_date <= curdate() ";
		//die($db->Parse($query));
		$row = $this->db->SelectRow($query);
		$value = $row['total'];
	}

	public $totalExpensesDuoAccounts;
	public $totalExpensesDuoAccountsChargedForUser;
	public $totalExpensesDuoAccountsChargedForPartner;
	

	// Total of payments made from duo accounts, part charged for user in parameter
	function GetTotalExpenseDuoAccountsChargedForUser($userId, &$value)
	{
		$query = "select sum(amount * (charge / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			".$this->recordFilter."
			and record_date <= curdate()
			and user_id = '".$userId."'";
		$row = $this->db->SelectRow($query);
		$total = $row['total'];
	
		$query = "select sum(amount * ((100 - charge) / 100)) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '".$userId."' or coowner_user_id = '".$userId."'))
			".$this->recordFilter."
			and record_date <= curdate()
			and user_id != '".$userId."'";
		$row = $this->db->SelectRow($query);
		$total += $row['total'];
	
		$value = $total;
	}
	
	// Total of payments made from duo accounts
	function GetTotalExpenseDuoAccounts(&$value)
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = '{USERID}' or coowner_user_id = '{USERID}'))
				".$this->recordFilter."
			and record_date <= curdate()";
		$row = $db->SelectRow($query);
	
		$value = $row['total'];
	}

	public $totalRepaymentFromUserToPartner;
	public $totalRepaymentFromPartnerToUser;

	// Total repayment from user to partner
	function GetTotalRepaymentFromUserToPartner($userId, $partnerId, &$value)
	{
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
				".$this->recordFilter."
				and record_date <= curdate()
				and account_id not in (select account_id from {TABLEPREFIX}account where type in (2, 3, 5, 12))
				and user_id = '".$partnerId."'
			)";
		$row = $this->db->SelectRow($query);
	
		$value = $row['total'];
	}
	
	public $totalIncomeOutsidePartnersToUser;
	public $totalIncomeOutsidePartnersToPartner;

	// Total deposits coming from outside to private accounts
	function GetTotalDepositFromOutsideToPrivateAccounts($userId, &$value)
	{
		// $query = "select sum(amount * ((100 - charge) / 100)) as total
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 12
			and marked_as_deleted = 0
			and category_id in (select category_id from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '".$this->user->get('duoId')."')
			and account_id in (select account_id from {TABLEPREFIX}account where marked_as_closed = 0 and type in (1) and owner_user_id = '".$userId."')
			".$this->recordFilter."
			and record_date <= curdate()";
		$row = $this->db->SelectRow($query);
	
		$value = $row['total'] ?: 0;
	}
	

	function RefreshBalance()
	{
		$this->ExpenseFromPrivateAccountToDuoCategories();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner.'<br/>';
		$this->ExpenseFromPrivateAccountToPartnerCategories();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner.'<br/>';
		$this->ExpenseFromPrivateAccountToUserCategories();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToPartner.'<br/>';

		$this->totalContributionOfUser += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser;
		$this->totalExpenses += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser;
		$this->totalExpensesChargedToUser += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser;
		$this->totalExpensesChargedToPartner += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToPartner;

		$this->ExpenseFromPrivateAccountsToDuoCategoriesMadeByPartner();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner.'<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner.'<br/>'.$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser.'<br/>';
		$this->ExpenseFromPrivateAccountToUserCategoriesMadeByPartner();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner.'<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner.'<br/>'.$this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToUser.'<br/>';
		$this->ExpenseFromPrivateAccountToPartnerCategories();
		if ($this->debug) print '<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser.'<br/>'.$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner.'<br/>';
		
		$this->totalContributionOfPartner += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner;
		$this->totalExpenses += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner;
		$this->totalExpensesChargedToUser += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToUser + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser;
		$this->totalExpensesChargedToPartner += $this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner + $this->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner + $this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner;

		$this->GetTotalIncomeDuoAccountsByUser($this->user->get('userId'), $this->totalIncomeDuoAccountsMadeByUser);
		$this->GetTotalIncomeDuoAccountsByUser($this->user->GetPartnerId(), $this->totalIncomeDuoAccountsMadeByPartner);
		if ($this->debug) print '<br/>'.$this->totalIncomeDuoAccountsMadeByUser.'<br/>'.$this->totalIncomeDuoAccountsMadeByPartner.'<br/>';

		$this->GetTotalDepositFromOutsideToDuoAccountsForUser($this->user->get('userId'), $this->totalIncomeDuoAccountsOutsidePartnersForUser);
		$this->GetTotalDepositFromOutsideToDuoAccountsForUser($this->user->GetPartnerId(), $this->totalIncomeDuoAccountsOutsidePartnersForPartner);
		$this->totalIncomeDuoAccountsOutsidePartners = $this->totalIncomeDuoAccountsOutsidePartnersForUser + $this->totalIncomeDuoAccountsOutsidePartnersForPartner;
		if ($this->debug) print '<br/>'.$this->totalIncomeDuoAccountsOutsidePartners.'<br/>'.$this->totalIncomeDuoAccountsOutsidePartnersForUser.'<br/>'.$this->totalIncomeDuoAccountsOutsidePartnersForPartner.'<br/>';

		$this->GetTotalOutcomeFromDuoAccountsByUser($this->user->get('userId'), $this->totalOutcomeDuoAccountsMadeByUser);
		$this->GetTotalOutcomeFromDuoAccountsByUser($this->user->GetPartnerId(), $this->totalOutcomeDuoAccountsMadeByPartner);
		if ($this->debug) print '<br/>'.$this->totalOutcomeDuoAccountsMadeByUser.'<br/>'.$this->totalOutcomeDuoAccountsMadeByPartner.'<br/>';
		
		$this->GetTotalExpenseDuoAccounts($this->totalExpensesDuoAccounts);
		$this->GetTotalExpenseDuoAccountsChargedForUser($this->user->get('userId'), $this->totalExpensesDuoAccountsChargedForUser);
		$this->GetTotalExpenseDuoAccountsChargedForUser($this->user->GetPartnerId(), $this->totalExpensesDuoAccountsChargedForPartner);
		if ($this->debug) print '<br/>'.$this->totalExpensesDuoAccounts.'<br/>'.$this->totalExpensesDuoAccountsChargedForUser.'<br/>'.$this->totalExpensesDuoAccountsChargedForPartner.'<br/>';
		
		$this->totalContributionOfUser += $this->totalIncomeDuoAccountsMadeByUser - $this->totalOutcomeDuoAccountsMadeByUser + $this->totalIncomeDuoAccountsOutsidePartnersForUser;
		$this->totalContributionOfPartner += $this->totalIncomeDuoAccountsMadeByPartner - $this->totalOutcomeDuoAccountsMadeByPartner + $this->totalIncomeDuoAccountsOutsidePartnersForPartner;
		$this->totalExpenses += $this->totalExpensesDuoAccounts;
		$this->totalExpensesChargedToUser += $this->totalExpensesDuoAccountsChargedForUser;
		$this->totalExpensesChargedToPartner += $this->totalExpensesDuoAccountsChargedForPartner;
		
		
		$this->GetTotalRepaymentFromUserToPartner($this->user->get('userId'), $this->user->getPartnerId(), $this->totalRepaymentFromUserToPartner);
		$this->GetTotalRepaymentFromUserToPartner($this->user->getPartnerId(), $this->user->get('userId'), $this->totalRepaymentFromPartnerToUser);
		if ($this->debug) print '<br/>'.$this->totalRepaymentFromUserToPartner.'<br/>'.$this->totalRepaymentFromPartnerToUser.'<br/>';
		
		$this->totalContributionOfUser += $this->totalRepaymentFromUserToPartner - $this->totalRepaymentFromPartnerToUser;
		$this->totalContributionOfPartner += $this->totalRepaymentFromPartnerToUser - $this->totalRepaymentFromUserToPartner;
		
		$this->GetTotalDepositFromOutsideToPrivateAccounts($this->user->get('userId'), $this->totalIncomeOutsidePartnersToUser);
		$this->GetTotalDepositFromOutsideToPrivateAccounts($this->user->GetPartnerId(), $this->totalIncomeOutsidePartnersToPartner);
		if ($this->debug) print '<br/>'.$this->totalIncomeOutsidePartnersToUser.'<br/>'.$this->totalIncomeOutsidePartnersToPartner.'<br/>';
		
		$this->totalContributionOfUser += $this->totalIncomeOutsidePartnersToPartner / 2 - $this->totalIncomeOutsidePartnersToUser / 2;
		$this->totalContributionOfPartner += $this->totalIncomeOutsidePartnersToUser / 2 - $this->totalIncomeOutsidePartnersToPartner / 2;
		
		
		$sql = 'delete from {TABLEPREFIX}statistics_balance where year='.$this->year.' and month='.$this->month;
		$this->db->Execute($sql);
		
		$query = 'insert into {TABLEPREFIX}statistics_balance
				(
				year,month,update_date,aggregate,
				expFromPrivateAccountsToDuoCategoriesMadeByUser,expFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser,expFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner,
				expFromPrivateAccountsToParCategoriesMadeByUser,expFromPrivateAccountsToParCategoriesMadeByUserChargedToUser,expFromPrivateAccountsToParCategoriesMadeByUserChargedToPartner,
				contribution_user, contribution_partner
				)
				values
				(
				%s,%s,now(),%s,
				%s,%s,%s,
				%s,%s,%s,
				%s,%s
				)';
		$sql = sprintf($query,
				$this->year,$this->month,$this->aggregate ? 1 : 0,
				$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser,$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser,$this->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner,
				$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser,$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser,$this->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner,
				$this->totalContributionOfUser - $this->totalExpensesChargedToUser, $this->totalContributionOfPartner - $this->totalExpensesChargedToPartner
		);
		$this->db->Execute($sql);
/*
SELECT
CONCAT_WS('-', year,month),
year,
month,
(select sum(contribution_user) from `bf_statistics_balance` T1 where T1.year < T.year or (T1.year = T.year and T1.month <= T.month)) as sum_contribution_user,

(select sum(contribution_partner) from `bf_statistics_balance` T1 where T1.year < T.year or (T1.year = T.year and T1.month <= T.month)) as sum_contribution_partner
FROM `bf_statistics_balance` T
order by year desc, month desc
 */
	}
}
