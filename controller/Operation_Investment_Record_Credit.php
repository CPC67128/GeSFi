<?php
class Operation_Investment_Record_Credit extends Operation_Investment_Record
{
	public function Validate()
	{
		$this->ValidateFromAccount();
		$this->ValidateFromDate();
		$this->ValidateToDate();
		$this->ValidatePayment();
		$this->ValidatePaymentInvested();
		$this->ValidateDesignation();
		$this->ValidatePeriodicity();
		$this->ValidatePeriodicityNumber();
	}

	public function Save()
	{
		$recordTypeOutcome = 20;

		for ($currentMonth = 0; $currentMonth < $this->_periodicityNumber; $currentMonth++)
		{
			$fromDate = Date('Y-m-d', strtotime($this->_fromDate." +".$currentMonth." month"));
			$toDate = Date('Y-m-d', strtotime($this->_toDate." +".$currentMonth." month"));

			$uuid = $this->_db->GenerateUUID();
	
			// Outcome
			if ($this->_fromAccount != '')
			{
				$this->_db->InsertRecord_AmountTransfer(
						$this->_fromAccount,
						$this->_currentUserId,
						$fromDate,
						$this->_amount,
						$this->_designation,
						$recordTypeOutcome,
						$uuid);
			}

			$newRecord = new Record_Transfer_Credit_Investment(
					$this->_toAccount,
					$this->_currentUserId,
					$toDate,
					$this->_amount,
					$this->_amountInvested,
					$this->_designation,
					$uuid
			);
			$this->_recordsHandler->Insert($newRecord);
		}
	}
}