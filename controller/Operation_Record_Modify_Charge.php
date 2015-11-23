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
		$sql = "select record_group_id, category_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set charge = ".$this->_charge." where record_group_id = '".$row['record_group_id']."' and category_id = '".$row['category_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set charge = ".$this->_charge." where record_id = '".$this->_recordId."'";
		}
		$row = $this->_db->Execute($sql);
	}
}