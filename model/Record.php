<?php
class Record extends Entity
{
	protected $accountId;
	protected $recordId;
	protected $recordGroupId;
	protected $userId;
	protected $recordDate;
	protected $markedAsDeleted;
	protected $designation;
	protected $recordType;
	protected $amount;
	protected $amountInvested;
	protected $value;
	protected $withdrawal;
	protected $income;
	protected $charge;
	protected $category;
	protected $categoryId;
	protected $actor;
	protected $confirmed;
	protected $creationDate;

	public function __construct()
	{
	}
	
	public function get_recordDateMonth()
	{
		return date('m', strtotime($this->get('recordDate'))); 
	}

	public function get_recordDateYear()
	{
		return date('Y', strtotime($this->get('recordDate')));
	}
}