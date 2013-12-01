<?php
class Operation_InvestmentRecord_Remark extends Operation_Record_Remark // The behavior is very similar
{
	public function Save()
	{
		$this->_db->InsertInvestmentRecord_Remark(
				$this->_currentAccountId,
				$this->_date,
				$this->_designation);
	}
}