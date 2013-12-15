<?php
class Operation_Category_Modification_Duo extends Operation_Category
{
	public function Save()
	{
		$handler = new CategoryHandler();

		if ($this->_categoryId == '')
		{
			$handler->InsertCategoryDuo($this->_category, $this->_type, $this->_sortOrder);
		}
		else
		{
			if ($this->_delete == 'on')
				$handler->DeleteCategoryDuo($this->_categoryId);
			else
				$handler->UpdateCategoryDuo($this->_categoryId, $this->_type, $this->_category, $this->_sortOrder);
		}
	}
}