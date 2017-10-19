<?php
class Record_Transfer_Debit_Investment extends Record_Transfer_Debit
{
	public function __construct($accountId, $userId, $recordDate, $withdrawal, $designation, $confirmed, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, null, $designation, $confirmed, $recordGroupId);
		$this->withdrawal = $withdrawal;
	}
}