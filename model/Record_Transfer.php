<?php
class Record_Transfer extends Record
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId)
	{
		$this->accountId = $accountId;
		$this->userId = $userId;
		$this->recordDate = $recordDate;
		$this->designation = $designation;
		$this->amount = $amount;
		$this->recordGroupId = $recordGroupId;
	}
}