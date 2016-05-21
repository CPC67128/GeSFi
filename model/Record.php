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

	public static $types = array
	(
			0 => 'In',
			3 => 'In',
			10 => 'TrIn',
			11 => 'TrIn',
			12 => 'In',
			20 => 'TrOut',
			21 => 'TrOut',
			22 => 'Out'
	);

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
	
	public static function GetRecordTypeGroup($recordType)
	{
		return self::$types[$recordType];
	}
}