<?php
class Operation_Record_Modify_Amount extends Operation_Record
{
	public function Validate()
	{
		$this->_amount = $this->_newValue;
		$this->ValidateAmount();
	}

	public function Save()
	{
		$recordsHandlerUpdate = new RecordsHandlerUpdate();
		$recordsHandlerUpdate->UpdateRecordAmount($this->_recordId, $this->_amount);
	}
}