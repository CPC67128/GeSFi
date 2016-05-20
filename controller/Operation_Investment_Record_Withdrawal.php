<?php
class Operation_Investment_Record_Withdrawal extends Operation_Investment_Record
{
	public function Validate()
	{
		$this->ValidateFromDate();
		$this->ValidateToAccountAllowingUnknownAccount();
		$this->ValidateToDate();
		$this->ValidateAmountDisinvested();
		$this->ValidateDesignation();
	}

	public function Save()
	{
		$recordTypeIncome = 10;

		$uuid = $this->_db->GenerateUUID();
		
		$newRecord = new Record_Transfer_Debit_Investment(
				$this->_fromAccount,
				$this->_currentUserId,
				$this->_fromDate,
				$this->_amountDisinvested,
				$this->_designation,
				$uuid
		);
		$this->_recordsHandler->Insert($newRecord);

		if ($this->_toAccount != '')
		{
			$newRecord = new Record_Transfer_Credit(
					$this->_toAccount,
					$this->_currentUserId,
					$this->_toDate,
					$this->_amountDisinvested,
					$this->_designation,
					$uuid
			);
			$this->_recordsHandler->Insert($newRecord);
		}
	}
}