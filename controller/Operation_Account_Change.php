<?php
class Operation_Account_Change extends Operation_Account
{
	public function Execute()
	{
		$_SESSION['account_id'] = $this->_accountId;
	}
}