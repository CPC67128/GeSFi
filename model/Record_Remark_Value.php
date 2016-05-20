<?php
class Record_Remark_Value extends Record_Remark
{
	public function __construct($accountId, $userId, $recordDate, $designation, $value)
	{
		parent::__construct($accountId, $userId, $recordDate, $designation, $value);
		$this->recordType = 30;
		$this->value = $value;
	}
}