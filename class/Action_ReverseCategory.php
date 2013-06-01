<?php
class Action_ReverseCategory extends Action
{
	protected $_category1;
	protected $_category2;

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$categoryId = str_replace('category', '', $key);

			if ($categoryId == 1)
				$this->_category1 = $value;
			if ($categoryId == 2)
				$this->_category2 = $value;
		}

		if (!($this->_category1 == $this->_category2
				|| $this->_category1 > 15
				|| $this->_category1 < 1
				|| $this->_category2 > 15
				|| $this->_category2 < 1))
			throw new Exception('Merci de renseigner correctement les catégories');
	}

	// -------------------------------------------------------------------------------------------------------------------
	
	public function Execute()
	{
		$db = new DB();
		$db->ReverseCategory($this->_category1, $this->_category2);
	}
}

