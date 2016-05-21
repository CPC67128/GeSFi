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
	protected $_generateIncome;
	protected $_calcBalance;
	protected $_calcBalanceConfirmed;

	protected $_sortOrder;

	public function getTypeDescription()
	{
		$accountsHandler = new AccountsHandler();
		$types = $accountsHandler->GetAccountTypes();
		return $types[$this->_type];
	}

	// -------------------------------------------------------------------------------------------------------------------

	function GetAccountTypeName()
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

		if (array_key_exists($this->_type, $types))
			return $types[$this->_type];
		else
			return '';
	}

	function GetAccountTypeColor()
	{
		$types = array
		(
				1 => '#FFFFFF',
				2 => '#FFFFFF',
				3 => '#FFFFFF',
				4 => '#FFFFFF',
				5 => '#FEBFD2',
				10 => '#FFFFFF',
				11 => '#FFFFFF',
				12 => '#FFFFFF'
		);
	
		return $types[$this->_type];
	}

	public function GetBalance()
	{
		return $this->get('calcBalance');
	}

	public function GetBalanceConfirmed()
	{
		//$balance = $this->get('openingBalance') + $this->GetTotalIncomeConfirmed() - $this->GetTotalOutcomeConfirmed();
		return $this->get('calcBalanceConfirmed');
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

	public function GetCoownerName()
	{
		$db = new DB();
	
		$query = 'select name from {TABLEPREFIX}user
		where user_id = \''.$this->_coownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['name'];
	}

	public function GetInvestmentLastValue()
	{
		$db = new DB();
	
		$query = "select value
			from {TABLEPREFIX}record
			where account_id = '".$this->_accountId."'
			and value is not null
			and marked_as_deleted = 0
			order by record_date desc, creation_date desc
			limit 1";
		$row = $db->SelectRow($query);

		if (isset($row['value']))
			return $row['value'];

		$query = "select CALC_amount_invested_accumulated
			from {TABLEPREFIX}record
			where account_id = '".$this->_accountId."'
			and value is null
			and marked_as_deleted = 0
			order by record_date desc, creation_date desc
			limit 1";
		$row = $db->SelectRow($query);
		return $row['CALC_amount_invested_accumulated'];
	}

	public function GetInvestmentLastYield()
	{
		$db = new DB();
	
		$query = "select CALC_yield
			from {TABLEPREFIX}record
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
			from {TABLEPREFIX}record
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
			from {TABLEPREFIX}record
			where account_id = '".$this->_accountId."'
			and value is not null
			and marked_as_deleted = 0
			order by record_date desc
			limit 1";
		$row = $db->SelectRow($query);
	
		return $row['record_date'];
	}
}