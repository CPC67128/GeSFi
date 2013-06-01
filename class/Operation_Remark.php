<?php
class Operation_Remark extends Operation
{
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

	public function Save()
	{
		$db = new DB();

		$uuid = $db->GenerateUUID();

		$db->InsertRecord(
				$_SESSION['account_id'],
				$this->_actor,
				$this->_date,
				0,
				$this->_designation,
				0,
				'',
				2,
				$uuid);
	}
}