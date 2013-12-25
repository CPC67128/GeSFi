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

		$accountsManager = new AccountsManager();

		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetCurrentUser();
		
		$fromAccountId = $this->_fromAccount;
		$fromUserId = $user->getUserId();
		if (substr($fromAccountId, 0, 5) == "USER/")
		{
			$fromUserId = substr($fromAccountId, 5, 36);
			$fromAccountId = '';
		}

		$toAccountId = $this->_toAccount;
		$toUserId = $user->getUserId();
		if (substr($toAccountId, 0, 5) == "USER/")
		{
			$toUserId = substr($toAccountId, 5);
			$toAccountId = '';
		}

		if ($toAccountId != '')
		{
			$account = $accountsManager->GetAccount($toAccountId);
			if ($account->get('type') == 3) // Duo account
			{
				$toUserId = $fromUserId;
			}
		}

		for ($currentMonth = 0; $currentMonth < $this->_periodicityNumber; $currentMonth++)
		{
			$currentDate = Date('Y-m-d', strtotime($this->_date." +".$currentMonth." month"));
			$uuid = $this->_db->GenerateUUID();

			$this->_db->InsertRecord_AmountTransfer(
				$fromAccountId,
				$fromUserId,
				$currentDate,
				$amountOutcome,
				$this->_designation,
				$recordTypeOutcome,
				$uuid);

			$this->_db->InsertRecord_AmountTransfer( 
				$toAccountId,
				$toUserId,
				$currentDate,
				$amountIncome,
				$this->_designation,
				$recordTypeIncome,
				$uuid);
		}
	}
}