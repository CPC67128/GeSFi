<?php
class Record_Transfer_Income_Investment extends Record_Transfer_Income
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $charge, $category, $confirmed, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->recordType = 40;
	}
}