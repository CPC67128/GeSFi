<?php
class CategoriesHandler extends Handler
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

		$query = "select *
			from {TABLEPREFIX}category
			where category_id = '".$id."'";
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newCategory->hydrate($row);
		}
		
		return $newCategory;
	}

	/*****************************************/

	function GetOutcomeCategoriesForDuo($userId, $activeOnly=true)
	{
		return $this->GetCategoriesByTypeAndLinkType($userId, 1, 'DUO', $activeOnly);
	}
	
	function GetIncomeCategoriesForDuo($userId, $activeOnly=true)
	{
		return $this->GetCategoriesByTypeAndLinkType($userId, 0, 'DUO', $activeOnly);
	}
	
	function GetOutcomeCategoriesForUser($userId, $activeOnly=true)
	{
		return $this->GetCategoriesByTypeAndLinkType($userId, 1, 'USER', $activeOnly);
	}

	function GetIncomeCategoriesForUser($userId, $activeOnly=true)
	{
		return $this->GetCategoriesByTypeAndLinkType($userId, 0, 'USER', $activeOnly);
	}

	function GetActiveCategoriesByTypeAndLinkType($userId, $type, $linkType) // TODO TO REMOVE?
	{
		$linkId = $userId;

		if ($linkType == 'DUO')
		{
			$usersHandler = new UsersHandler();
			$currentUser = $usersHandler->GetUser($userId);
		}
	
		$categories = array();
	
		$db = new DB();

		if ($linkType == 'DUO')
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$linkType."'
				and type = ".$type."
				and marked_as_inactive = 0
				order by sort_order, category";
		}
		else
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$linkType."'
				and link_id = '".$linkId."'
				and type = ".$type."
				and marked_as_inactive = 0
				order by sort_order, category";
		}

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}

	function GetCategoriesByTypeAndLinkType($userId, $type, $linkType, $activeOnly=true)
	{
		$linkId = $userId;

		if ($linkType == 'DUO')
		{
			$usersHandler = new UsersHandler();
			$currentUser = $usersHandler->GetUser($userId);
		}

		$activeFilter = "";
		if ($activeOnly)
		{
			$activeFilter = " and marked_as_inactive = 0 ";
		}

		$categories = array();
	
		$db = new DB();

		if ($linkType == 'DUO')
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$linkType."'
				and type = ".$type."
				".$activeFilter."
				order by sort_order, category";
		}
		else
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$linkType."'
				and link_id = '".$linkId."'
				and type = ".$type."
				".$activeFilter."
				order by sort_order, category";
		}

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}

	/*****************************************/

	function GetCategoriesForUser($userId)
	{
		return $this->GetCategoriesForType($userId, 'USER');
	}

	function GetCategoriesForDuo($userId)
	{
		return $this->GetCategoriesForType($userId, 'DUO');
	}

	function GetCategoriesForType($userId, $type)
	{
		$categories = array();

		$linkId = $userId;
		if ($type == "DUO")
		{
			$usersHandler = new UsersHandler();
			$currentUser = $usersHandler->GetUser($userId);
		}
	
		$db = new DB();
	
		if ($type == "DUO")
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$type."'
				order by type, sort_order, category";
		}
		else
		{
			$query = "select *
				from {TABLEPREFIX}category
				where link_type = '".$type."'
				and link_id = '".$linkId."'
				order by type, sort_order, category";
		}

		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newCategory = new Category();
			$newCategory->hydrate($row);
			array_push($categories, $newCategory);
		}
	
		return $categories;
	}

	function InsertCategory($category, $type, $sortOrder, $linkType)
	{
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = sprintf("insert into {TABLEPREFIX}category (category_id, link_type, link_id, type, category, active_from, sort_order, marked_as_inactive)
				values (uuid(), '%s', '%s', %s, %s, CURRENT_TIMESTAMP(), %s, 0)",
				$linkType,
				$linkType == 'USER' ? '{USERID}' : '',
				$type,
				$db->ConvertStringForSqlInjection($category),
				$sortOrder == '' ? '0' : $sortOrder);

		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateCategory($categoryId, $type, $category, $sortOrder, $isInactive, $linkType)
	{
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();

		$originalSortOrder = $sortOrder;
		$continue = true;

		// Prepare the move of the category to the new position
		$updateQueries = array();
		while ($continue)
		{
			// Check if a category already exists at the targeted position
			$query = sprintf("select count(*) as total from {TABLEPREFIX}category where link_type = '%s' and link_id = '%s' and type = %s and sort_order = %s and category_id != '%s'",
					$linkType,
					$linkType == 'USER' ? '{USERID}' : '',
					$type,
					$sortOrder,
					$categoryId);

			$row = $db->SelectRow($query);

			if ($row['total'] == 0)
				$continue = false;
			else
			{
				// If this is the case, I will push the category already at this position to the next position 
				$query = sprintf("update {TABLEPREFIX}category set sort_order = sort_order + 1 where link_type = '%s' and link_id = '%s' and type = %s and sort_order = %s and category_id != '%s'",
						$linkType,
						$linkType == 'USER' ? '{USERID}' : '',
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

		// Update the category
		$sortOrder = $originalSortOrder;

		$query = sprintf("update {TABLEPREFIX}category set category = %s, sort_order = %s, marked_as_inactive = %s where category_id = '%s'",
				$db->ConvertStringForSqlInjection($category),
				$sortOrder,
				$isInactive ? "1" : "0",
				$categoryId);
		$result = $db->Execute($query);

		return $result;
	}
}