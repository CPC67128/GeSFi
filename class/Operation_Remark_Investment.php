<?php
class Operation_Remark_Investment extends Operation
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

		$db->InsertInvestmentRecord_Remark(
				$_SESSION['account_id'],
				$this->_date,
				$this->_designation);
	}
}