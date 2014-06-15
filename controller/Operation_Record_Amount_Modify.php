<?php
class Operation_Record_Amount_Modify extends Operation_Record
{
	protected $_amount;

	public function Save()
	{
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set amount = ".$this->_amount." where record_id = '".$this->_recordId."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set amount = ".$this->_amount." where record_id = '".$this->_recordId."'";
		}
		$row = $this->_db->Execute($sql);
	}
}