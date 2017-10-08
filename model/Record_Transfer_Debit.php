<?php
class Record_Transfer_Debit extends Record_Transfer
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $confirmed, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->recordType = 20;
		$this->confirmed = $confirmed;
	}
}