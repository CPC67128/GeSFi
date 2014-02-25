<?php
class Operation_InvestmentRecord_Debit extends Operation_InvestmentRecord
{
	public function Validate()
	{
		$this->ValidateFromDate();
		$this->ValidateToAccountAllowingUnknownAccount();
		$this->ValidateToDate();
		$this->ValidatePaymentDisinvested();
		$this->ValidateDesignation();
	}

	public function Save()
	{
		$recordTypeIncome = 10;

		$uuid = $this->_db->GenerateUUID();

		$this->_db->InsertInvestmentRecord_Income(
				$this->_fromAccount,
				$uuid,
				$this->_fromDate,
				$this->_designation,
				-1 * $this->_paymentDisinvested,
				-1 * $this->_paymentDisinvested);

		if ($this->_toAccount != '')
		{
			$this->_db->InsertRecord_AmountTransfer(
					$this->_toAccount,
					$this->_currentUserId,
					$this->_toDate,
					$this->_paymentDisinvested,
					$this->_designation,
					$recordTypeIncome,
					$uuid);
		}
	}
}