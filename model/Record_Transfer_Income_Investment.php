<?php
class Record_Transfer_Income_Investment extends Record_Transfer
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->amount = null;
		$this->income = $amount;
		$this->recordType = 40;

	}
}