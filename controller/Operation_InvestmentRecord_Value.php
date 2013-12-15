<?php
class Operation_InvestmentRecord_Value extends Operation_InvestmentRecord
{
	public function Validate()
	{
		$this->ValidateRecordDate();
		$this->ValidateDesignation();
		$this->ValidateValue();
	}

	public function Save()
	{
		$this->_db->InsertInvestmentRecord_Value(
				$this->_currentAccountId,
				$this->_date,
				$this->_designation,
				$this->_value);
	}
}