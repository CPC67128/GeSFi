<?php
class CategoryHandler
{
	function GetCategory($id)
	{
		$db = new DB();
	
		$query = 'select category
			from {TABLEPREFIX}category
			where category_id = \''.$id.'\'';
		$row = $db->SelectRow($query);

		return $row['category'];
	}
	
	function GetOutcomeCategoriesForDuo($userId)
	{
		$categories = array();
	
		$db = new DB();
	
		$query = "select category_id as CategoryId,
			link_type as LinkType,
			link_id as LinkId,
			type,
			category,
			active_from as ActiveFrom,
			sort_order as SortOrder
		from {TABLEPREFIX}category
		where link_type = 'DUO'
		and link_id = '".$userId."'
		and type = 1
		order by sort_order, category";
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}
	
	function GetOutcomeCategoriesForUser($userId)
	{
		$categories = array();
	
		$db = new DB();
	
		$query = "select category_id as CategoryId,
			link_type as LinkType,
			link_id as LinkId,
			type,
			category,
			active_from as ActiveFrom,
			sort_order as SortOrder
		from {TABLEPREFIX}category
		where link_type = 'USER'
		and link_id = '".$userId."'
		and type = 1
		order by sort_order, category";
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}

	function GetIncomeCategoriesForUser($userId)
	{
		$categories = array();

		$db = new DB();
	
		$query = "select category_id as CategoryId,
				link_type as LinkType,
				link_id as LinkId,
				type,
				category,
				active_from as ActiveFrom,
				sort_order as SortOrder
			from {TABLEPREFIX}category
			where link_type = 'USER'
			and link_id = '".$userId."'
			and type = 0
			order by sort_order, category";
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}

		return $categories;
	}
}