<?php
class Operation_Category_Modification_User extends Operation_Category
{
	public function Save()
	{
		$handler = new CategoryHandler();

		if ($this->_categoryId == '')
		{
			$handler->InsertCategory($this->_category, $this->_type, $this->_sortOrder, 'USER');
		}
		else
		{
			$isInactive = false;
			if ($this->_inactive == 'on')
				$isInactive = true;

			$handler->UpdateCategory($this->_categoryId, $this->_type, $this->_category, $this->_sortOrder, $isInactive, 'USER');
		}
	}
}