<?php
class Operation_Record extends Operation
{
	protected $_actor;
	protected $_date;
	protected $_designation;
	protected $_periodicity;
	protected $_periodicityNumber;
	protected $_recordId;
	protected $_fromAccount;
	protected $_toAccount;
	protected $_amount;
	protected $_userId;
	protected $_categories;

	// -------------------------------------------------------------------------------------------------------------------
	
	public function setCategoryAmount($categoryIndex, $amount)
	{
		$this->_categories[$categoryIndex]['amount'] = $amount;
	}
	
	public function setCategoryCategoryId($categoryIndex, $categoryId)
	{
		$this->_categories[$categoryIndex]['categoryId'] = $categoryId;
	}

	public function setCategoryChargeLevel($categoryIndex, $chargeLevel)
	{
		if (!isset($chargeLevel))
			throw new Exception('Merci de renseigner correctement la prise en charge');
		if (!is_numeric($chargeLevel))
			throw new Exception('Merci de renseigner correctement la prise en charge');
		if ($chargeLevel > 100 || $chargeLevel < 0)
			throw new Exception('Merci de renseigner correctement la prise en charge');
	
		$this->_categories[$categoryIndex]['chargeLevel'] = $chargeLevel;
	}
	
	public function hydrate(array $data)
	{
		parent::hydrate($data);
	
		foreach ($data as $key => $value)
		{
			if (strpos($key, 'category') === 0)
			{
				$categoryIndex = str_replace('ChargeLevel', '', str_replace('CategoryId', '', str_replace('Amount', '', str_replace('category', '', $key))));
				$typeOfData = str_replace($categoryIndex, '', str_replace('category', '', $key));
					
				$method = 'setCategory'.ucfirst($typeOfData);
				if (method_exists($this, $method))
				{
					$this->$method($categoryIndex, $value);
				}
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function ValidateRecordDate()
	{
		if (!isset($this->_date))
			throw new Exception('Merci de renseigner la date de l\'opération');
	}

	public function ValidateDesignation()
	{
		if (!isset($this->_designation))
			throw new Exception('Merci de renseigner la désignation de l\'opération');
		if (strlen(trim($this->_designation)) == 0)
			throw new Exception('Merci de renseigner la désignation de l\'opération');
	}

	public function ValidatePeriodicity()
	{
		if (!isset($this->_periodicity))
			throw new Exception('Merci de renseigner la périodicité de l\'opération');
	}
	
	public function ValidatePeriodicityNumber()
	{
		if ($this->_periodicity == 'monthly')
		{
			if (!isset($this->_periodicityNumber))
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if (!is_numeric($this->_periodicityNumber))
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if ($this->_periodicityNumber < 1)
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if ($this->_periodicityNumber > 12)
				$this->_periodicityNumber = 12;
			$this->_periodicityNumber = $this->_periodicityNumber;
		}
		else
			$this->_periodicityNumber = 1;
	}

	public function ValidateRecordId()
	{
		if (!isset($this->_recordId) || $this->_recordId == '')
			throw new Exception('L\'identifiant de la ligne n\'est pas renseignée');
	}

	public function ValidateAmount()
	{
		if (!isset($this->_amount))
			throw new Exception('Merci de renseigner correctement le montant');
	
		$this->_amount = str_replace(",", ".", $this->_amount);
		if (!is_numeric($this->_amount))
			throw new Exception('Merci de renseigner correctement le montant');
	}
	
	public function ValidateFromAccount()
	{
		if (!isset($this->_fromAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
		if ($this->_fromAccount == '')
			throw new Exception('Merci de renseigner le compte d\'origine');
		if ($this->_fromAccount == $this->_toAccount)
			throw new Exception('Merci de renseigner correctement le compte d\'origine');
	}
	
	public function ValidateToAccount()
	{
		if (!isset($this->_toAccount))
			throw new Exception('Merci de renseigner le compte de destination');
		if ($this->_toAccount == '')
			throw new Exception('Merci de renseigner le compte de destination');
		if ($this->_toAccount == $this->_fromAccount)
			throw new Exception('Merci de renseigner correctement le compte de destination');
	}
}