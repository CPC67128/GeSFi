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

	function GetOutcomeCategoriesForDuo($userId)
	{
		return $this->GetActiveCategoriesByTypeAndLinkType($userId, 1, 'DUO');
	}
	
	function GetIncomeCategoriesForDuo($userId)
	{
		return $this->GetActiveCategoriesByTypeAndLinkType($userId, 0, 'DUO');
	}
	
	function GetOutcomeCategoriesForUser($userId)
	{
		return $this->GetActiveCategoriesByTypeAndLinkType($userId, 1, 'USER');
	}

	function GetIncomeCategoriesForUser($userId)
	{
		return $this->GetActiveCategoriesByTypeAndLinkType($userId, 0, 'USER');
	}

	function GetActiveCategoriesByTypeAndLinkType($userId, $type, $linkType)
	{
		$linkId = $userId;

		if ($linkType == 'DUO')
		{
			$usersHandler = new UsersHandler();
			$currentUser = $usersHandler->GetUser($userId);
			$linkId = $currentUser->getDuoId();
		}
	
		$categories = array();
	
		$db = new DB();
	
		$query = "select *
			from {TABLEPREFIX}category
			where link_type = '".$linkType."'
			and link_id = '".$linkId."'
			and type = ".$type."
			and marked_as_inactive = 0
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
			$linkId = $currentUser->getDuoId();
		}
	
		$db = new DB();
	
		$query = "select *
			from {TABLEPREFIX}category
			where link_type = '".$type."'
			and link_id = '".$linkId."'
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

	function InsertCategory($category, $type, $sortOrder, $linkType)
	{
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = sprintf("insert into {TABLEPREFIX}category (category_id, link_type, link_id, type, category, active_from, sort_order)
				values (uuid(), '%s', '%s', %s, '%s', CURRENT_TIMESTAMP(), %s)",
				$linkType,
				$linkType == 'USER' ? '{USERID}' : $currentUser->get('duoId'),
				$type,
				$db->ConvertStringForSqlInjection($category),
				$sortOrder);

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
					$linkType == 'USER' ? '{USERID}' : $currentUser->get('duoId'),
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
						$linkType == 'USER' ? '{USERID}' : $currentUser->get('duoId'),
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

		$query = sprintf("update {TABLEPREFIX}category set category = '%s', sort_order = %s, marked_as_inactive = %s where category_id = '%s'",
				$category,
				$sortOrder,
				$isInactive ? "1" : "0",
				$categoryId);
		$result = $db->Execute($query);

		return $result;
	}
}