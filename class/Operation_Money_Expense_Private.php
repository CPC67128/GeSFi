<?php
class Operation_Money_Expense_Private extends Operation_Money
{
	protected $_categories;

	public function setCategoryAmount($categoryIndex, $amount)
	{
		$this->_categories[$categoryIndex]['amount'] = $amount;
	}

	public function setCategoryCategoryId($categoryIndex, $categoryId)
	{
		$this->_categories[$categoryIndex]['categoryId'] = $categoryId;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			if (strpos($key, 'category') === 0)
			{
				$categoryIndex = str_replace('CategoryId', '', str_replace('Amount', '', str_replace('category', '', $key)));
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

		$recordType = 4;

		for ($currentMonth = 0; $currentMonth < $monthly_months; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $db->GenerateUUID();

			foreach ($this->_categories as $categoryIndex=>$categoryData)
			{
				if (is_numeric($categoryData['amount']) && $categoryData['amount'] > 0)
				{
					$db->InsertRecord(
						$_SESSION['account_id'],
						1,
						$currentDate,
						$categoryData['amount'],
						$this->_designation,
						100,
						$categoryData['categoryId'],
						$recordType,
						$uuid);
				}
			}
		}
	}
}