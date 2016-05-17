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
		$newRecord = new Record();
		$newRecord->set('accountId', $this->_currentAccountId);
		$newRecord->set('userId', $this->_currentUserId);
		$newRecord->set('recordDate', $this->_date);
		$newRecord->set('designation', $this->_designation);
		$newRecord->set('recordType', 2);
		
		$this->_recordsHandler->Insert($newRecord);
	}
}