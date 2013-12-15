<?php
class Operation_Record_Confirm extends Operation_Record
{
	protected $_confirmed;

	public function Save()
	{
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);
		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set confirmed = ".$this->_confirmed." where record_group_id = '".$row['record_group_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set confirmed = ".$this->_confirmed." where record_id = '".$this->_recordId."' and account_id = '{ACCOUNTID}'";
		}
		$row = $this->_db->Execute($sql);
	}
}