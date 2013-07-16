<?php
class Action_ConfirmRecord extends Action
{
	protected $_recordId;
	protected $_confirmed;

	public function setRecordId($recordId)
	{
		if (!isset($recordId) || $recordId == '')
			throw new Exception('L\'identifiant de la ligne n\'est pas renseignée');
		$this->_recordId = $recordId;
	}

	public function setConfirmed($confirmed)
	{
		$this->_confirmed = $confirmed;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------
	
	public function Execute()
	{
		$db = new DB();

		$query = 'select record_group_id from {TABLEPREFIX}record where record_id = \''.$this->_recordId.'\'';
		$row = $db->SelectRow($query);
		if (strlen($row['record_group_id']) > 0)
		{
			$query = 'update {TABLEPREFIX}record set confirmed = '.$this->_confirmed.' where record_group_id = \''.$row['record_group_id'].'\'';
		}
		else
		{
			$query = 'update {TABLEPREFIX}record set confirmed = '.$this->_confirmed.' where record_id = \''.$this->_recordId.'\' and account_id = \'{ACCOUNTID}\'';
		}
		$row = $db->Execute($query);
	}
}