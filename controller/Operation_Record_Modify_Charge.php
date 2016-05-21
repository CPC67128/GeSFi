<?php
class Operation_Record_Modify_Charge extends Operation_Record
{
	public function Validate()
	{
		$this->_charge = $this->_newValue;
		$this->ValidateCharge();
	}

	public function Save()
	{
		$recordsHandlerUpdate = new RecordsHandlerUpdate();
		$recordsHandlerUpdate->UpdateRecordCharge($this->_recordId, $this->_charge);
	}
}