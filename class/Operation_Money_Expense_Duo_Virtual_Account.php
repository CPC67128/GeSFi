<?php
class Operation_Money_Expense_Duo_Virtual_Account extends Operation_Money
{
	protected $_privateAccount;
	protected $_categories;

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

	public function setPrivateAccount($privateAccount)
	{
		if (!isset($privateAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
	
		$this->_privateAccount = $privateAccount;
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

		$accountId = $_SESSION['account_id'];
		$handlePrivateAccount = false;
		$recordType = 22;

		if ($this->_privateAccount != "")
			$handlePrivateAccount = true;
		$reverseRecordType = 20;

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
						$accountId,
						$this->_actor,
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

			if ($handlePrivateAccount)
			{
				$db->InsertRecord(
					$this->_privateAccount,
					1,
					$currentDate,
					$amount,
					$this->_designation,
					0,
					'',
					$reverseRecordType,
					$uuid);
			}
		}
	}
}