<?php
class Record_Transfer_Credit extends Record_Transfer
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->recordType = 10;
	}
}