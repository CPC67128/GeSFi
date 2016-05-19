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
		$newRecord = new Record_Remark(
				$this->_currentAccountId,
				$this->_currentUserId,
				$this->_date,
				$this->_designation
		);

		$this->_recordsHandler->Insert($newRecord);
	}
}