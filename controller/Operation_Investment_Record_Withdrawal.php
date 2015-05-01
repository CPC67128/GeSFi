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

		$this->_db->InsertInvestmentRecord_Outcome(
				$this->_fromAccount,
				$uuid,
				$this->_fromDate,
				$this->_designation,
				$this->_amountDisinvested,
				$this->_amountDisinvested);

		if ($this->_toAccount != '')
		{
			$this->_db->InsertRecord_AmountTransfer(
					$this->_toAccount,
					$this->_currentUserId,
					$this->_toDate,
					$this->_amountDisinvested,
					$this->_designation,
					$recordTypeIncome,
					$uuid);
		}
	}
}