<?php
class Operation_Record_Income extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateAmount();
		$this->ValidateToAccount();
		$this->ValidateDesignation();
		$this->ValidateRecordDate();
		$this->ValidatePeriodicity();
		$this->ValidatePeriodicityNumber();
	}

	public function Save()
	{
		$toAccountId = $this->_toAccount;
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();

		if (substr($toAccountId, 0, 5) == "USER/")
		{
			$toAccountId = '';
		}

		for ($currentMonth = 0; $currentMonth < $this->_periodicityNumber; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $this->_db->GenerateUUID();

			foreach ($this->_categories as $categoryIndex=>$categoryData)
			{
				if (is_numeric($categoryData['amount']) && $categoryData['amount'] > 0)
				{
					$this->_db->InsertRecord_AmountUse(
						$toAccountId,
						$user->getUserId(),
						$currentDate,
						$categoryData['amount'],
						$this->_designation,
						100,
						$categoryData['categoryId'],
						12,
						$uuid);
				}
			}
		}
	}
}