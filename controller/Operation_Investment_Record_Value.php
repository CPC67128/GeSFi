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
		$newRecord = new Record_Remark_Value(
				$this->_currentAccountId,
				$this->_currentUserId,
				$this->_date,
				$this->_designation,
				$this->_value
		);
		$this->_recordsHandler->Insert($newRecord);

		$this->CalculateIndicators();
	}
}