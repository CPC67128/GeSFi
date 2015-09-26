<?php
class Operation_Record_Modify_Charge extends Operation_Record
{
	public function Validate()
	{
		$this->ValidateCharge();
	}

	public function Save()
	{
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
/* 			$sql = sprintf("update {TABLEPREFIX}record set charge=%s where record_group_id = '%s'",
					$this->_charge,
					$row['record_group_id']); */
		}
		else
		{
			$sql = sprintf("update {TABLEPREFIX}record set charge=%s where record_id = '%s'",
					$this->_charge,
					$this->_recordId);
		}
		$row = $this->_db->Execute($sql);
	}
}