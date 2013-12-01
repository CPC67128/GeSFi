<?php
class Operation_Record_Expense extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateAmount();
		$this->ValidateFromAccount();
		$this->ValidateDesignation();
		$this->ValidateRecordDate();
		$this->ValidatePeriodicity();
		$this->ValidatePeriodicityNumber();
	}

	public function Save()
	{
		$fromAccountId = $this->_fromAccount;
		$fromUserId = $this->_userId;
		$recordType = 22;

		if (substr($fromAccountId, 0, 5) == "USER/")
		{
			$fromUserId = substr($fromAccountId, 5, 36);
			$fromAccountId = '';
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
						$fromAccountId,
						$fromUserId,
						$currentDate,
						$categoryData['amount'],
						$this->_designation,
						$categoryData['chargeLevel'],
						$categoryData['categoryId'],
						$recordType,
						$uuid);
				}
			}
		}
	}
}