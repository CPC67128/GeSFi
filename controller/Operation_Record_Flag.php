<?php
class Operation_Record_Flag extends Operation_Record
{
	protected $_confirmed;
	protected $_flag;

	public function Save()
	{
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);
		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set flag_".$this->_flag." = ".$this->_confirmed." where record_group_id = '".$row['record_group_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set flag_".$this->_flag." = ".$this->_confirmed." where record_id = '".$this->_recordId."' and account_id = '{ACCOUNTID}'";
		}
		$this->_db->Execute($sql);
	}
}