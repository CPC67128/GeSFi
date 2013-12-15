<?php
class Operation_InvestmentRecord_Delete extends Operation_InvestmentRecord
{
	public function Validate()
	{
		$this->ValidateRecordId();
	}

	public function Execute()
	{
		$this->_db->DeleteInvestmentRecord($this->_recordId);
	}
}