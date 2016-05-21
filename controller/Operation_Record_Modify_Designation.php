<?php
class Operation_Record_Modify_Designation extends Operation_Record
{
	public function Validate()
	{
		$this->_designation = $this->_newValue;
		$this->ValidateDesignation();
	}

	public function Save()
	{
		$recordsHandlerUpdate = new RecordsHandlerUpdate();
		$recordsHandlerUpdate->UpdateRecordDesignation($this->_recordId, $this->_designation);
	}
}