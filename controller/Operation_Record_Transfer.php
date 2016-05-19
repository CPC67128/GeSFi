<?php
class Operation_Record_Transfer extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateAmount();
		$this->ValidateFromAccount();
		$this->ValidateToAccount();
		$this->ValidateDesignation();
		$this->ValidateRecordDate();
		$this->ValidatePeriodicity();
		$this->ValidatePeriodicityNumber();
	}

	public function Save()
	{
		$recordTypeOutcome = 20;
		$recordTypeIncome = 10;

		$amountOutcome = $this->_amount;
		$amountIncome = $this->_amount;

		$accountsHandler = new AccountsHandler();

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
		
		$fromAccountId = $this->_fromAccount;
		$fromUserId = $user->get('userId');
		if (substr($fromAccountId, 0, 5) == "USER/")
		{
			$fromUserId = substr($fromAccountId, 5, 36);
			$fromAccountId = '';
		}

		$toAccountId = $this->_toAccount;
		$toUserId = $user->get('userId');
		if (substr($toAccountId, 0, 5) == "USER/")
		{
			$toUserId = substr($toAccountId, 5);
			$toAccountId = '';
		}

		if ($toAccountId != '')
		{
			$account = $accountsHandler->GetAccount($toAccountId);
			if ($account->get('type') == 3) // Duo account
			{
				$toUserId = $fromUserId;
			}
		}

		for ($currentMonth = 0; $currentMonth < $this->_periodicityNumber; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $this->_db->GenerateUUID();

			$newRecord = new Record_Transfer_Debit(
					$fromAccountId,
					$fromUserId,
					$currentDate,
					$amountOutcome,
					$this->_designation,
					$uuid
			);
			$this->_recordsHandler->Insert($newRecord);

			$newRecord = new Record_Transfer_Credit(
					$toAccountId,
					$toUserId,
					$currentDate,
					$amountIncome,
					$this->_designation,
					$uuid
			);
			$this->_recordsHandler->Insert($newRecord);
		}
	}
}