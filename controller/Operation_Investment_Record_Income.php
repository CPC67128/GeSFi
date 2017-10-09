<?php
class Operation_Investment_Record_Income extends Operation_Investment_Record
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

		$newRecord = new Record_Transfer_Income_Investment(
				$this->_fromAccount,
				$this->_userId,
				$this->_fromDate,
				$this->_amountDisinvested,
				$this->_designation,
				$uuid
		);
		$this->_recordsHandler->Insert($newRecord);

		if ($this->_toAccount != '')
		{
			$newRecord = new Record_Transfer_Income(
					$this->_toAccount,
					$this->_userId,
					$this->_toDate,
					$this->_amountDisinvested,
					$this->_designation,
					100,
					null,
					true,
					$uuid
			);

			$this->_recordsHandler->Insert($newRecord);
		}

		$this->CalculateIndicators();
	}
}