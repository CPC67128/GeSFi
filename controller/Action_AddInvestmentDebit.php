<?php
class Action_AddInvestmentDebit
{
	protected $_fromAccount;
	protected $_fromDate;
	protected $_toAccount;
	protected $_toDate;
	protected $_paymentDisinvested;
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

		$recordTypeIncome = 10;

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$fromDate = Date('Y-m-d', strtotime($this->_fromDate." +".$currentMonth." month"));
			$toDate = Date('Y-m-d', strtotime($this->_toDate." +".$currentMonth." month"));

			$uuid = $db->GenerateUUID();
	
			$db->InsertInvestmentRecord(
					$this->_fromAccount,
					$uuid,
					$fromDate,
					$this->_designation,
					'null',
					-1 * $this->_paymentDisinvested,
					'null');

			if ($this->_toAccount != '')
			{
				$db->InsertRecord(
						$this->_toAccount,
						$user->getUserId(),
						0,
						$toDate,
						$this->_paymentDisinvested,
						$this->_designation,
						0,
						'',
						$recordTypeIncome,
						$uuid);
			}
		}
	}
}