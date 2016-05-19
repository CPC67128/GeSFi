<?php
class Record_Transfer_Credit_Investment extends Record_Transfer_Credit
{
	public function __construct($accountId, $userId, $recordDate, $amount, $amountInvested, $designation, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->amountInvested = $amountInvested;
	}
}