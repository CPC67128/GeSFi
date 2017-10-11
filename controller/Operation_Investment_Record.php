<?php
class Operation_Investment_Record extends Operation
{
	protected $_date;
	protected $_amount;
	protected $_amountInvested;
	protected $_value;
	protected $_designation;
	protected $_accountId;
	protected $_recordId;

	protected $_fromAccount;
	protected $_fromDate;
	protected $_toAccount;
	protected $_toDate;
	
	protected $_periodicity;
	protected $_periodicityNumber;

	protected $_amountDisinvested;

	protected $_confirmed;

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

	public function ValidateValue()
	{
		$this->_value = $this->ParseAmount($this->_value);

		if (!isset($this->_value))
			throw new Exception('Merci de renseigner correctement la valeur');
		if (!is_numeric($this->_value))
			throw new Exception('Merci de renseigner correctement la valeur');
	}

	public function ValidateRecordId()
	{
		if (!isset($this->_recordId) || $this->_recordId == '')
			throw new Exception('L\'identifiant de la ligne n\'est pas renseignée');
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
	
	public function ValidatePayment()
	{
		if (!isset($this->_amount))
			throw new Exception('Merci de renseigner correctement le versement');

		$this->_amount = $this->ParseAmount($this->_amount);
		if (!is_numeric($this->_amount))
			throw new Exception('Merci de renseigner correctement le versement');
	}
	
	public function ValidatePaymentInvested()
	{
		if (!isset($this->_amountInvested))
			throw new Exception('Merci de renseigner correctement le montant investi');
	
		$this->_amountInvested = $this->ParseAmount($this->_amountInvested);
		if (!is_numeric($this->_amountInvested))
			throw new Exception('Merci de renseigner correctement le montant investi');
	}
	
	public function ValidateAmountDisinvested()
	{
		if (!isset($this->_amountDisinvested))
			throw new Exception('Merci de renseigner correctement le montant désinvesti');

		$this->_amountDisinvested = $this->ParseAmount($this->_amountDisinvested);
		if (!is_numeric($this->_amountDisinvested))
			throw new Exception('Merci de renseigner correctement le montant désinvesti');
	}

	public function ValidateFromAccount()
	{
		if (!isset($this->_fromAccount))
			throw new Exception('Merci de renseigner le compte d\'origine');
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

	public function ValidateToAccountAllowingUnknownAccount()
	{
		if (!isset($this->_toAccount))
			throw new Exception('Merci de renseigner le compte de destination');
		if ($this->_toAccount == $this->_fromAccount)
			throw new Exception('Merci de renseigner correctement le compte de destination');
	}

	public function ValidateFromDate()
	{
		if (!isset($this->_fromDate))
			throw new Exception('Merci de renseigner la date de l\'opération');
	}

	public function ValidateToDate()
	{
		if (!isset($this->_toDate))
			throw new Exception('Merci de renseigner la date de l\'opération');
	}

	public function ValidateConfirmed()
	{
		if (!empty($this->_confirmed))
			$this->_confirmed = 1;
		else
			$this->_confirmed = 0;
	}

	public function CalculateIndicators()
	{
		$investmentsRecordsHandler = new InvestmentsRecordsHandler();
		$investmentsRecordsHandler->CalculateIndicators();
	}
}