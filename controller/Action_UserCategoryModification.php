<?php
class Action_UserCategoryModification extends Action
{
	protected $_categoryId;
	protected $_category;
	protected $_type;
	protected $_sortOrder;
	protected $_delete;
	
	public function set($member, $value)
	{
		$this->$member = $value;
	}
	
	public function get($member)
	{
		$member = '_'.$member;
		if (isset($this->$member))
			return $this->$member;
		else
			throw new Exception('Unknow attribute '.$member);
	}
	
	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->set('_'.$key, $value);
		}
	}

	// -------------------------------------------------------------------------------------------------------------------
	
	public function Execute()
	{
		$handler = new CategoryHandler();

		if ($this->_categoryId == '')
		{
			$handler->InsertCategory($this->_category, $this->_type, $this->_sortOrder);
		}
		else
		{
			if ($this->_delete == 'on')
				$handler->DeleteCategory($this->_categoryId);
			else
				$handler->UpdateCategory($this->_categoryId, $this->_type, $this->_category, $this->_sortOrder);
		}
	}
}