<?php
class Operation_Money_Expense_Duo_Account extends Operation_Money_Expense_Duo
{
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
		$accountId = $_SESSION['account_id'];
		$reverseRecordType = -1;
		$handlePrivateAccount = false;

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
		}
	}
}