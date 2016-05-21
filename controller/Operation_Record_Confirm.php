<?php
class Operation_Record_Confirm extends Operation_Record
{
	protected $_confirmed;

	public function Save()
	{
		$recordsHandlerUpdate = new RecordsHandlerUpdate();
		$recordsHandlerUpdate->UpdateRecordConfirmed($this->_recordId, $this->_confirmed);
	}
}