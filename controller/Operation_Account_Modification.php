<?php
class Operation_Account_Modification extends Operation_Account
{
	protected $_accountId;
	protected $_name;
	protected $_description;
	protected $_information;
	protected $_type;
	protected $_owner;
	protected $_coowner;
	protected $_openingBalance;
	protected $_expectedMinimumBalance;
	protected $_creationDate;
	protected $_availabilityDate;
	protected $_closingDate;
	protected $_minimumCheckPeriod;

	protected $_delete;

	protected $_sortOrder;

	public function Save()
	{
		$handler = new AccountsHandler();

		if ($this->_accountId == '')
		{
			$handler->InsertAccount($this->_name, $this->_owner, $this->_coowner, $this->_type, $this->_openingBalance, $this->_expectedMinimumBalance, $this->_sortOrder, $this->_minimumCheckPeriod);
		}
		else
		{
			$account = $handler->GetAccount($this->_accountId);
			if ($account->get('ownerUserId') == $this->_currentUserId) // Current user is account first owner
			{
				if ($this->_delete == 'on')
					$handler->DeleteAccount($this->_accountId);
				else
					$handler->UpdateAccount(
							$this->_accountId,
							$this->_name,
							$this->_description,
							$this->_openingBalance,
							$this->_expectedMinimumBalance,
							$this->_sortOrder,
							$this->_minimumCheckPeriod,
							$this->_creationDate,
							$this->_availabilityDate);
			}
			else
			{
				if ($this->_delete == 'on')
					throw new Exception("Vous n'êtes pas le titulaire principal de ce compte, vous ne pouvez pas le supprimer.");
				else
					$handler->UpdateAccountSortOrder($this->_accountId, $this->_sortOrder);
			}
		}
	}
}