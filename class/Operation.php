<?php
class Operation
{
	protected $_actor;
	protected $_date;
	protected $_designation;

	public function setActor($actor)
	{
		if (!isset($actor))
			throw new Exception('Merci de renseigner l\'acteur de l\'opération');

		$this->_actor = (int) $actor;
	}

	public function setDate($date)
	{
		if (!isset($date))
			throw new Exception('Merci de renseigner la date de l\'opération');

		$this->_date = $date;
	}

	public function setDesignation($designation)
	{
		if (!isset($designation))
			throw new Exception('Merci de renseigner la désignation de l\'opération');
		if (strlen(trim($designation)) == 0)
			throw new Exception('Merci de renseigner la désignation de l\'opération');

		$this->_designation = $designation;
	}
}