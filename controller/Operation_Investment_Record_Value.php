<?php
class Operation_Investment_Record_Value extends Operation_Investment_Record
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