<?php
class Operation_Record_Modify_Amount extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateAmount();
	}

	public function Save()
	{
		$sql = "select record_group_id, category_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set amount = ".$this->_amount." where record_group_id = '".$row['record_group_id']."' and category_id = '".$row['category_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set amount = ".$this->_amount." where record_id = '".$this->_recordId."'";
		}
		$row = $this->_db->Execute($sql);
	}
}