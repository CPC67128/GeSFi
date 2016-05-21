<?php
class Operation_Investment_Record_Delete extends Operation_Investment_Record
{
	public function Validate()
	{
		$this->ValidateRecordId();
	}

	public function Execute()
	{
		$recordsHandlerUpdate = new RecordsHandlerUpdate();
		$recordsHandlerUpdate->UpdateRecordMarkedAsDeleted($this->_recordId, 1);
	}
}