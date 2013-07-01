<?php
class Operation_Money_Income_Duo extends Operation_Money
{
	protected $_toAccount;
	protected $_fromAccount;
	protected $_amount;

	public function setToAccount($toAccount)
	{
		if (!isset($toAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
	
		$this->_toAccount = $toAccount;
	}
	
	public function setFromAccount($fromAccount)
	{
		$this->_fromAccount = $fromAccount;
	}
	
	public function setAmount($amount)
	{
		if (!isset($amount))
			throw new Exception('Merci de renseigner correctement le montant');

		$amount = str_replace(",", ".", $amount);
		if (!is_numeric($amount))
			throw new Exception('Merci de renseigner correctement le montant');

		$this->_amount = $amount;
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
		$db = new DB();

		$monthly_months = 1;
		if ($this->_periodicity == "monthly")
		{
			$monthly_months = $this->_periodicityNumber;
		
			if (!is_numeric($amount))
				ReturnError ($LNG_Periodicity_field_not_filled_correctly);
			if ($monthly_months < 1)
				ReturnError ($LNG_Periodicity_field_not_filled_correctly);
			if ($monthly_months > 12)
				$monthly_months = 12;
		}
		
		$recordType = 10;
		$reverseRecordType = 20;

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $db->GenerateUUID();

			$db->InsertRecord(
				$_SESSION['account_id'],
				$this->_actor,
				$currentDate,
				$this->_amount,
				$this->_designation,
				0,
				'',
				$recordType,
				$uuid);

			if ($this->_fromAccount != '')
			{
				$db->InsertRecord(
					$this->_fromAccount,
					1,
					$currentDate,
					$this->_amount,
					$this->_designation,
					0,
					'',
					$reverseRecordType,
					$uuid);
			}
		}
	}
}