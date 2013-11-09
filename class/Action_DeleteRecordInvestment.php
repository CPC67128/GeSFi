<?php
class Action_DeleteRecordInvestment extends Action
{
	protected $_recordId;

	public function setRecordId($recordId)
	{
		if (!isset($recordId) || $recordId == '')
			throw new Exception('L\'identifiant de la ligne n\'est pas renseignée');
		$this->_recordId = $recordId;
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

		$query = 'delete from {TABLEPREFIX}investment_record where investment_record_id = \''.$this->_recordId.'\' and account_id = \'{ACCOUNTID}\'';
		$row = $db->Execute($query);
	}
}