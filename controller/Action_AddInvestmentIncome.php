<?php
class Action_AddInvestmentIncome
{
	protected $_fromAccount;
	protected $_fromDate;
	protected $_toAccount;
	protected $_toDate;
	protected $_payment;
	protected $_paymentInvested;
	protected $_designation;

	protected $_periodicity;
	protected $_periodicityNumber;

	public function set($member, $value)
	{
		$this->$member = $value;
	}
	
	public function get($member)
	{
		$member = '_'.$member;
		if (isset($this->$member))
			return $this->$member;
		else
			throw new Exception('Unknow attribute '.$member);
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->set('_'.$key, $value);
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function Save()
	{
		$monthly_months = 1;
		if ($this->_periodicity == "monthly")
		{
			$monthly_months = $this->_periodicityNumber;
		
			if ($monthly_months < 1)
				ReturnError ($LNG_Periodicity_field_not_filled_correctly);
			if ($monthly_months > 12)
				$monthly_months = 12;
		}

		$db = new DB();

		$recordTypeOutcome = 20;

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$fromDate = Date('Y-m-d', strtotime($this->_fromDate." +".$currentMonth." month"));
			$toDate = Date('Y-m-d', strtotime($this->_toDate." +".$currentMonth." month"));

			$uuid = $db->GenerateUUID();
	
			// Outcome
			if ($this->_fromAccount != '')
			{
				$db->InsertRecord(
						$this->_fromAccount,
						$user->getUserId(),
						0,
						$fromDate,
						$this->_payment,
						$this->_designation,
						0,
						'',
						$recordTypeOutcome,
						$uuid);
			}
	
			$db->InsertInvestmentRecord_Income(
					$this->_toAccount,
					$uuid,
					$toDate,
					$this->_designation,
					$this->_payment,
					$this->_paymentInvested);
		}
	}
}