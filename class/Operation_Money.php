<?php
class Operation_Money extends Operation
{
	protected $_periodicity;
	protected $_periodicityNumber;

	public function setPeriodicity($periodicity)
	{
		if (!isset($periodicity))
			throw new Exception('Merci de renseigner la périodicité de l\'opération');
		$this->_periodicity = $periodicity;
	}
	
	public function setPeriodicityNumber($periodicityNumber)
	{
		if ($this->_periodicity == 'monthly')
		{
			if (!isset($periodicityNumber))
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if (!is_numeric($periodicityNumber))
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if ($periodicityNumber < 1)
				throw new Exception('Merci de renseigner correctement le nombre de périodicité');
			if ($periodicityNumber > 12)
				$periodicityNumber = 12;
			$this->_periodicityNumber = $periodicityNumber;
		}
		else
			$this->_periodicityNumber = 1;
	}
}