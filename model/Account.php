<?php
class Account extends Entity
{
	protected $_accountId;
	protected $_name;
	protected $_description;
	protected $_information;
	protected $_type;
	protected $_ownerUserId;
	protected $_coownerUserId;
	protected $_openingBalance;
	protected $_expectedMinimumBalance;
	protected $_creationDate;
	protected $_availabilityDate;
	protected $_closingDate;
	protected $_minimumCheckPeriod;
	protected $_markedAsClosed;
	protected $_notDisplayedInMenu;

	protected $_sortOrder;

	public function getTypeDescription()
	{
		$accountsHandler = new AccountsHandler();
		$types = $accountsHandler->GetAccountTypes();
		return $types[$this->_type];
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function GetTotalIncome()
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type between 10 and 19
			and marked_as_deleted = 0
			and account_id = \''.$this->_accountId.'\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalOutcome()
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type between 20 and 29
			and marked_as_deleted = 0
			and account_id = \''.$this->_accountId.'\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalIncomeConfirmed()
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type between 10 and 19
		and marked_as_deleted = 0
		and account_id = \''.$this->_accountId.'\'
		and record_date <= curdate()
		and confirmed = 1';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}
	
	public function GetTotalOutcomeConfirmed()
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type between 20 and 29
		and marked_as_deleted = 0
		and account_id = \''.$this->_accountId.'\'
		and record_date <= curdate()
		and confirmed = 1';
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	public function GetBalance()
	{
		$balance = $this->get('openingBalance') + $this->GetTotalIncome() - $this->GetTotalOutcome();
		return $balance;
	}

	public function GetBalanceConfirmed()
	{
		$balance = $this->get('openingBalance') + $this->GetTotalIncomeConfirmed() - $this->GetTotalOutcomeConfirmed();
		return $balance;
	}

	public function GetPlannedOutcome($numberOfDays)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 22
			and marked_as_deleted = 0
			and record_date > curdate()
			and account_id = \''.$this->_accountId.'\'
			and record_date < adddate(curdate(), interval +'.$numberOfDays.' day)';
		$row = $db->SelectRow($query);

		return $row['total'];
	}
	
	public function GetOwnerName()
	{
		$db = new DB();
	
		$query = 'select name from {TABLEPREFIX}user
		where user_id = \''.$this->_ownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['name'];
	}

	public function GetOwnerEmail()
	{
		$db = new DB();
	
		$query = 'select email from {TABLEPREFIX}user
		where user_id = \''.$this->_ownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['email'];
	}

	public function GetCoownerName()
	{
		$db = new DB();
	
		$query = 'select name from {TABLEPREFIX}user
		where user_id = \''.$this->_coownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['name'];
	}

	public function GetCoownerEmail()
	{
		$db = new DB();
	
		$query = 'select email from {TABLEPREFIX}user
		where user_id = \''.$this->_coownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['email'];
	}

/*	public function GetDuoCategoriesForOutcome()
	{
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = 'select category, category_id
		from {TABLEPREFIX}category
		where link_type = \'DUO\'
		and link_id = \''.$currentUser->getDuoId().'\'
		and type = 1
		order by sort_order';
		$result = $db->Select($query);
		return $result;
	}

	public function GetUserCategoriesForOutcome()
	{
		$db = new DB();
	
		$query = 'select category, category_id
			from {TABLEPREFIX}category
			where link_type = \'USER\'
			and link_id = \'{USERID}\'
			and type = 1
			order by sort_order';
		$result = $db->Select($query);
	
		return $result;
	}

	public function GetUserCategoriesForIncome()
	{
		$db = new DB();
	
		$query = 'select category, category_id
			from {TABLEPREFIX}category
			where link_type = \'USER\'
			and link_id = \'{USERID}\'
			and type = 0
			order by sort_order';
		$result = $db->Select($query);

		return $result;
	}

	public function GetDuoCategoriesForIncome()
	{
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = 'select category, category_id
			from {TABLEPREFIX}category
			where link_type = \'DUO\'
			and link_id = \''.$currentUser->getDuoId().'\'
			and type = 0
			and marked_as_deleted = 0
			order by sort_order';
		$result = $db->Select($query);
		return $result;
	}
*/
	public function OBS_GetTotalCreditByActorAndMonthAndYear($actor, $month, $year) // OBSOLETE?
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type >= 10 and record_type < 20 
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and actor = ".$actor."
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalCredit()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 3
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalCreditByActor()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (3, 0)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function OBS_GetTotalDebitByMonthAndYear($month, $year) // OBSOLETE
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type >= 20 and record_type < 30
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalDebit()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (4, 20)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalIncomeJointAccountByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (3, 10)
		and marked_as_deleted = 0
		and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
		and record_date <= curdate()
		and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	public function GetInvestmentLastValue()
	{
		$db = new DB();
	
		$query = "select value
			from {TABLEPREFIX}investment_record
			where account_id = '".$this->_accountId."'
			and value is not null
			and marked_as_deleted = 0
			order by record_date desc, creation_date desc
			limit 1";
		$row = $db->SelectRow($query);

		if (isset($row['value']))
			return $row['value'];

		$query = "select CALC_payment_invested_accumulated
			from {TABLEPREFIX}investment_record
			where account_id = '".$this->_accountId."'
			and value is null
			and marked_as_deleted = 0
			order by record_date desc, creation_date desc
			limit 1";
		$row = $db->SelectRow($query);
		return $row['CALC_payment_invested_accumulated'];
	}

	public function GetInvestmentLastYield()
	{
		$db = new DB();
	
		$query = "select CALC_yield
			from {TABLEPREFIX}investment_record
			where account_id = '".$this->_accountId."'
			and CALC_yield is not null
			and marked_as_deleted = 0
			order by record_date desc
			limit 1";
		$row = $db->SelectRow($query);

		return $row['CALC_yield'];
	}

	public function GetInvestmentLastYieldAverage()
	{
		$db = new DB();
	
		$query = "select CALC_yield_average
			from {TABLEPREFIX}investment_record
			where account_id = '".$this->_accountId."'
			and CALC_yield_average is not null
			and marked_as_deleted = 0
			order by record_date desc
			limit 1";
		$row = $db->SelectRow($query);

		return $row['CALC_yield_average'];
	}

	
	public function GetInvestmentLastValueDate()
	{
		$db = new DB();
	
		$query = "select record_date
			from {TABLEPREFIX}investment_record
			where account_id = '".$this->_accountId."'
			and value is not null
			and marked_as_deleted = 0
			order by record_date desc
			limit 1";
		$row = $db->SelectRow($query);
	
		return $row['record_date'];
	}
}