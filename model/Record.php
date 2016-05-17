<?php
class Record extends Entity
{
	protected $_accountId;
	protected $_recordId;
	protected $_recordGroupId;
	protected $_userId;
	protected $_recordDate;
	protected $_markedAsDeleted;
	protected $_designation;
	protected $_recordType;
	protected $_amount;
	protected $_amountInvested;
	protected $_value;
	protected $_withdrawal;
	protected $_income;
	protected $_charge;
	protected $_category;
	protected $_categoryId;
	protected $_actor;
	protected $_confirmed;
	protected $_creationDate;

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