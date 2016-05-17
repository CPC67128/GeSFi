<?php
class Operation_Record_Delete extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateRecordId();
	}

	public function Execute()
	{
		$this->_recordsHandler->DeleteRecord($this->_recordId);
	}
}