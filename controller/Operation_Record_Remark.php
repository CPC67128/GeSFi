<?php
class Operation_Record_Remark extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateRecordDate();
		$this->ValidateDesignation();
	}

	public function Save()
	{
		$this->_db->InsertRecord_Remark(
				$this->_currentAccountId,
				$this->_currentUserId,
				$this->_date,
				$this->_designation);
	}
}