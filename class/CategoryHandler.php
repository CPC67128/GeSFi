<?php
class CategoryHandler
{
	function GetCategoryName($id)
	{
		$db = new DB();
	
		$query = 'select category
			from {TABLEPREFIX}category
			where category_id = \''.$id.'\'';
		$row = $db->SelectRow($query);

		return $row['category'];
	}
	
	function GetCategory($id)
	{
		$db = new DB();
		$newCategory = new Category();

		$query = "select category_id as CategoryId,
			link_type as LinkType,
			link_id as LinkId,
			type,
			category,
			active_from as ActiveFrom,
			sort_order as SortOrder
			from {TABLEPREFIX}category
			where category_id = '".$id."'";
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newCategory->hydrate($row);
		}
		
		return $newCategory;
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
		and marked_as_deleted = 0
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
		and marked_as_deleted = 0
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
			and marked_as_deleted = 0
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
	
	function GetCategoriesForUser($userId)
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
			and marked_as_deleted = 0
			order by type, sort_order, category";
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}

	function GetCategoriesForDuo($userId)
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
			and link_id in (select duo_id from {TABLEPREFIX}user where user_id = '{USERID}')
			and marked_as_deleted = 0
			order by type, sort_order, category";
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}
	
	function InsertCategory($category, $type, $sortOrder)
	{
		$db = new DB();
	
		$query = sprintf("insert into {TABLEPREFIX}category (category_id, link_type, link_id, type, category, active_from, sort_order)
				values (uuid(), 'USER', '{USERID}', %s, '%s', CURRENT_TIMESTAMP(), %s)",
				$type,
				$category,
				$sortOrder);

		$result = $db->Execute($query);
	
		return $result;
	}
	
	function DeleteCategory($categoryId)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}category set marked_as_deleted = 1 where category_id = '%s'",
				$categoryId);
	
		$result = $db->Execute($query);
	
		return $result;
	}
	
	function UpdateCategory($categoryId, $type, $category, $sortOrder)
	{
		$db = new DB();

		$originalSortOrder = $sortOrder;
		$continue = true;

		$updateQueries = array();
		while ($continue)
		{
			$query = sprintf("select count(*) as total from {TABLEPREFIX}category where link_type = 'USER' and link_id = '{USERID}' and type = %s and sort_order = %s and category_id != '%s'",
					$type,
					$sortOrder,
					$categoryId);
			$row = $db->SelectRow($query);

			if ($row['total'] == 0)
				$continue = false;
			else
			{
				$query = sprintf("update {TABLEPREFIX}category set sort_order = sort_order + 1 where link_type = 'USER' and link_id = '{USERID}' and type = %s and sort_order = %s and category_id != '%s'",
						$type,
						$sortOrder,
						$categoryId);
				array_push($updateQueries, $query);
			}
			$sortOrder++;
		}

		for ($i = (count($updateQueries) - 1); $i >= 0; $i--)
		{
			$db->Execute($updateQueries[$i]);
		}

		$sortOrder = $originalSortOrder;

		$query = sprintf("update {TABLEPREFIX}category set category = '%s', sort_order = %s where category_id = '%s'",
				$category,
				$sortOrder,
				$categoryId);
		$result = $db->Execute($query);

		return $result;
	}

	function InsertCategoryDuo($category, $type, $sortOrder)
	{
		$usersHandler = new UsersHandler(); 
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = sprintf("insert into {TABLEPREFIX}category (category_id, link_type, link_id, type, category, active_from, sort_order)
				values (uuid(), 'DUO', '%s', %s, '%s', CURRENT_TIMESTAMP(), %s)",
				$currentUser->get('duoId'),
				$type,
				$category,
				$sortOrder);
	
		$result = $db->Execute($query);
	
		return $result;
	}
	
	function DeleteCategoryDuo($categoryId)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}category set marked_as_deleted = 1 where category_id = '%s'",
				$categoryId);
	
		$result = $db->Execute($query);
	
		return $result;
	}
	
	function UpdateCategoryDuo($categoryId, $type, $category, $sortOrder)
	{
		$usersHandler = new UsersHandler(); 
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$originalSortOrder = $sortOrder;
		$continue = true;
	
		$updateQueries = array();
		while ($continue)
		{
			$query = sprintf("select count(*) as total from {TABLEPREFIX}category where link_type = 'DUO' and link_id = '%s' and type = %s and sort_order = %s and category_id != '%s'",
					$currentUser->get('duoId'),
					$type,
					$sortOrder,
					$categoryId);
			$row = $db->SelectRow($query);
	
			if ($row['total'] == 0)
				$continue = false;
			else
			{
				$query = sprintf("update {TABLEPREFIX}category set sort_order = sort_order + 1 where link_type = 'DUO' and link_id = '%s' and type = %s and sort_order = %s and category_id != '%s'",
						$currentUser->get('duoId'),
						$type,
						$sortOrder,
						$categoryId);
				array_push($updateQueries, $query);
			}
			$sortOrder++;
		}
	
		for ($i = (count($updateQueries) - 1); $i >= 0; $i--)
		{
			$db->Execute($updateQueries[$i]);
		}
	
		$sortOrder = $originalSortOrder;
	
		$query = sprintf("update {TABLEPREFIX}category set category = '%s', sort_order = %s where category_id = '%s'",
				$category,
				$sortOrder,
				$categoryId);
				$result = $db->Execute($query);
	
				return $result;
	}
}