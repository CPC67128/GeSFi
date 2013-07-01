<?php
class Operation_Money_Transfer extends Operation_Money
{
	protected $_fromAccount = '';
	protected $_toAccount = '';
	protected $_amount;
	
	public function setAmount($amount)
	{
		if (!isset($amount))
			throw new Exception('Merci de renseigner correctement le montant');
	
		$amount = str_replace(",", ".", $amount);
		if (!is_numeric($amount))
			throw new Exception('Merci de renseigner correctement le montant');
	
		$this->_amount = $amount;
	}

	public function setFromAccount($fromAccount)
	{
		if (!isset($fromAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
		if ($fromAccount == '')
			throw new Exception('Merci de renseigner le compte d\'origine');
		if ($fromAccount == $this->_toAccount)
			throw new Exception('Merci de renseigner correctement le compte d\'origine');

		$this->_fromAccount = $fromAccount;
	}
	
	public function setToAccount($toAccount)
	{
		if (!isset($toAccount))
			throw new Exception('Merci de renseigner le compte de destination');
		if ($toAccount == '')
			throw new Exception('Merci de renseigner le compte de destination');
		if ($toAccount == $this->_fromAccount)
			throw new Exception('Merci de renseigner correctement le compte de destination');
		
		$this->_toAccount = $toAccount;
	}
	
	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function Save()
	{
		if ($this->_toAccount == '' || $this->_fromAccount == '' )
			throw new Exception('Merci de renseigner correctement les comptes');

		$db = new DB();

		$monthly_months = 1;
		if ($this->_periodicity == "monthly")
		{
			$monthly_months = $this->_periodicityNumber;
		
			if ($monthly_months < 1)
				ReturnError ($LNG_Periodicity_field_not_filled_correctly);
			if ($monthly_months > 12)
				$monthly_months = 12;
		}

		$recordTypeOutcome = 20;
		$recordTypeIncome = 10;

		$amountOutcome = $this->_amount;
		$amountIncome = $this->_amount;

		$accountsManager = new AccountsManager();
		$fromAccount = $accountsManager->GetAccount($this->_fromAccount);

		if ($fromAccount->getType() == 2) // Particular to account type DUO R+V (2) TODO
		{
			$amountOutcome = -1 * $amountOutcome;
			$recordTypeOutcome = 10;
		}

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $db->GenerateUUID();

			$db->InsertRecord(
				$this->_fromAccount,
				1,
				$currentDate,
				$amountOutcome,
				$this->_designation,
				0,
				'',
				$recordTypeOutcome,
				$uuid);

			$db->InsertRecord(
				$this->_toAccount,
				1,
				$currentDate,
				$amountIncome,
				$this->_designation,
				0,
				'',
				$recordTypeIncome,
				$uuid);
		}
	}
}