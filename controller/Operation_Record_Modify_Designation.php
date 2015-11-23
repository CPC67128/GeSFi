<?php
class Operation_Record_Modify_Designation extends Operation_Record
{
	public function Validate()
	{
		$this->_designation = $this->_newValue;
		$this->ValidateDesignation();
	}

	public function Save()
	{
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$this->_recordId."'";
		$row = $this->_db->SelectRow($sql);

		if (strlen($row['record_group_id']) > 0)
		{
			$sql = sprintf("update {TABLEPREFIX}record set designation='%s' where record_group_id = '%s'",
					$this->_db->ConvertStringForSqlInjection($this->_designation),
					$row['record_group_id']);
		}
		else
		{
			$sql = sprintf("update {TABLEPREFIX}record set designation='%s' where record_id = '%s'",
					$this->_db->ConvertStringForSqlInjection($this->_designation),
					$this->_recordId);
		}
		$row = $this->_db->Execute($sql);
	}
}