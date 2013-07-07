<?php
class Category
{
	protected $_categoryId;
	protected $_linkType;
	protected $_linkId;
	protected $_type;
	protected $_category;
	protected $_activeFrom;
	protected $_sortOrder;

	public function setCategoryId($categoryId)
	{
		$this->_categoryId = $categoryId;
	}
	
	public function getCategoryId()
	{
		return $this->_categoryId;
	}
	
	public function setLinkType($value)
	{
		$this->_linkType = $value;
	}
	
	public function getLinkType()
	{
		return $this->_linkType;
	}

	public function setLinkId($value)
	{
		$this->_linkId = $value;
	}
	
	public function getLinkId()
	{
		return $this->_linkId;
	}
	
	public function setType($value)
	{
		$this->_type = $value;
	}
	
	public function getType()
	{
		return $this->_type;
	}
	
	public function setCategory($value)
	{
		$this->_category = $value;
	}
	
	public function getCategory()
	{
		return $this->_category;
	}
	
	public function setActiveFrom($value)
	{
		$this->_activeFrom = $value;
	}
	
	public function getActiveFrom()
	{
		return $this->_activeFrom;
	}

	public function setSortOrder($value)
	{
		$this->_sortOrder = $value;
	}
	
	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	
	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$key = ucfirst($key);
			$method = 'set'.$key;
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}
	
	// -------------------------------------------------------------------------------------------------------------------

	public function GetTotalExpenseByMonthAndYear($month, $year)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	public function GetTotalIncomeByMonthAndYear($month, $year)
	{
		$db = new DB();

		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	public function GetAverageExpenseByMonth()
	{
		$result = 0;

		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (22)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];

		$query = "select timestampdiff(day, active_from, curdate()) + 1 as nb_days
			from {TABLEPREFIX}category
			where category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
		$nbJours = $row['nb_days'];

		if ($nbJours > 0)
		{
			if ($nbJours < 30)
				$result = ($total / $nbJours);
			else
				$result = ($total / $nbJours) * 30;
		} 

		return $result;
	}

	public function GetAverageRevenueByMonth()
	{
		$result = 0;

		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (12)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
		$total = $row['total'];

		$query = "select timestampdiff(day, active_from, curdate()) + 1 as nb_days
			from {TABLEPREFIX}category
			where category_id = '".$this->_categoryId."'";
		$row = $db->SelectRow($query);
		$nbJours = $row['nb_days'];

		if ($nbJours > 0)
		{
			if ($nbJours < 30)
				$result = ($total / $nbJours);
			else
				$result = ($total / $nbJours) * 30;
		} 

		return $result;
	}
}