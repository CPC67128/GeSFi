<?php
class Record_Transfer_Payment extends Record_Transfer
{
	public function __construct($accountId, $userId, $recordDate, $amount, $designation, $charge, $category, $confirmed, $recordGroupId)
	{
		parent::__construct($accountId, $userId, $recordDate, $amount, $designation, $recordGroupId);
		$this->charge = $charge;
		$this->category = $category;
		$this->confirmed = $confirmed;
		$this->recordType = 22;
	}
}