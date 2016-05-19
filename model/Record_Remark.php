<?php
class Record_Remark extends Record
{
	public function __construct($accountId, $userId, $recordDate, $designation)
	{
		$this->accountId = $accountId;
		$this->userId = $userId;
		$this->recordDate = $recordDate;
		$this->designation = $designation;
		$this->recordType = 2;
	}
}