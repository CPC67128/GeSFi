<?php
class Operation_Designation_Rename extends Operation_Designation
{
	protected $_designationSource;
	protected $_designationDestination;

	public function Validate()
	{
		$this->ValidateDesignationSource();
		//$this->ValidateDesignationDestination();
	}

	public function ValidateDesignationSource()
	{
		if (!isset($this->_designationSource) || strlen(trim($this->_designationSource)) == 0)
			throw new Exception('Merci de renseigner la désignation source');
	}

	public function ValidateDesignationDestination()
	{
		if (!isset($this->_designationDestination) || strlen(trim($this->_designationDestination)) == 0)
			throw new Exception('Merci de renseigner la désignation destination');
	}

	public function Save()
	{
		$recordsHandler = new RecordsHandler();
		$recordsHandler->RenameDesignation($this->_designationSource, $this->_designationDestination);
	}
}