<?php
class Operation_Money_Expense extends Operation_Money
{
	protected $_fromAccount;
	protected $_categories;
	protected $_userId;

	public function setCategoryAmount($categoryIndex, $amount)
	{
		$this->_categories[$categoryIndex]['amount'] = $amount;
	}

	public function setCategoryCategoryId($categoryIndex, $categoryId)
	{
		$this->_categories[$categoryIndex]['categoryId'] = $categoryId;
	}

	public function setCategoryChargeLevel($categoryIndex, $chargeLevel)
	{
		if (!isset($chargeLevel))
			throw new Exception('Merci de renseigner correctement la prise en charge');
		if (!is_numeric($chargeLevel))
			throw new Exception('Merci de renseigner correctement la prise en charge');
		if ($chargeLevel > 100 || $chargeLevel < 0)
			throw new Exception('Merci de renseigner correctement la prise en charge');

		$this->_categories[$categoryIndex]['chargeLevel'] = $chargeLevel;
	}

	public function setFromAccount($fromAccount)
	{
		if (!isset($fromAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
	
		$this->_fromAccount = $fromAccount;
	}
	
	public function setUserId($userId)
	{
		$this->_userId = $userId;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			if (strpos($key, 'category') === 0)
			{
				$categoryIndex = str_replace('ChargeLevel', '', str_replace('CategoryId', '', str_replace('Amount', '', str_replace('category', '', $key))));
				$typeOfData = str_replace($categoryIndex, '', str_replace('category', '', $key));

				$method = 'setCategory'.ucfirst($typeOfData);
				if (method_exists($this, $method))
				{
					$this->$method($categoryIndex, $value);
				}
			}
			else
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
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
		
			if ($monthly_months < 1)
				ReturnError ($LNG_Periodicity_field_not_filled_correctly);
			if ($monthly_months > 12)
				$monthly_months = 12;
		}

		$fromAccountId = $this->_fromAccount;
		$fromUserId = $this->_userId;
		$recordType = 22;

		if (substr($fromAccountId, 0, 5) == "USER/")
		{
			$fromUserId = substr($fromAccountId, 5, 36);
			$fromAccountId = '';
		}

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $db->GenerateUUID();

			$amount = 0;

			foreach ($this->_categories as $categoryIndex=>$categoryData)
			{
				if (is_numeric($categoryData['amount']) && $categoryData['amount'] > 0)
				{
					$db->InsertRecord(
						$fromAccountId,
						$fromUserId,
						0,
						$currentDate,
						$categoryData['amount'],
						$this->_designation,
						$categoryData['chargeLevel'],
						$categoryData['categoryId'],
						$recordType,
						$uuid);

					$amount += $categoryData['amount'];
				}
			}
		}
	}
}