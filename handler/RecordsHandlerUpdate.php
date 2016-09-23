<?php
class RecordsHandlerUpdate extends Handler
{
	function UpdateRecordConfirmed($recordId, $confirmed)
	{
		return $this->UpdateRecordField($recordId, 'confirmed', $confirmed, false, true, false);
	}

	function UpdateRecordMarkedAsDeleted($recordId, $markedAsDeleted)
	{
		return $this->UpdateRecordField($recordId, 'marked_as_deleted', $markedAsDeleted, false, true, false);
	}

	function UpdateRecordAmount($recordId, $amount)
	{
		return $this->UpdateRecordField($recordId, 'amount', $amount, false, true, true);
	}

	function UpdateRecordCharge($recordId, $charge)
	{
		return $this->UpdateRecordField($recordId, 'charge', $charge, false, true, true);
	}

	function UpdateRecordDesignation($recordId, $designation)
	{
		return $this->UpdateRecordField($recordId, 'designation', $designation, true, false, false);
	}
	
	function UpdateRecordField($recordId, $fieldName, $fieldValue, $quote, $calculateBalances, $byCategory)
	{
		$db = new DB();

		if ($quote)
			$fieldValue = $db->ConvertStringForSqlInjection($fieldValue);
	
		$sql = "select record_group_id, category_id from {TABLEPREFIX}record where record_id = '".$recordId."'";
		$row = $db->SelectRow($sql);
	
		if (strlen($row['record_group_id']) > 0)
			$sql = "update {TABLEPREFIX}record set ".$fieldName." = ".$fieldValue." where record_group_id = '".$row['record_group_id']."'";
		else
			$sql = "update {TABLEPREFIX}record set ".$fieldName." = ".$fieldValue." where record_id = '".$recordId."'";

		if ($byCategory && strlen($row['category_id']) > 0)
			$sql = $sql." and category_id = '".$row['category_id']."'";

		$result = $db->Execute($sql);

		if ($calculateBalances)
		{
			if (strlen($row['record_group_id']) > 0)
				$sql = "select account_id from {TABLEPREFIX}record where record_group_id = '".$row['record_group_id']."'";
			else
				$sql = "select account_id from {TABLEPREFIX}record where record_id = '".$recordId."'";
			$result = $db->Select($sql);
		
			$accountsHandler = new AccountsHandler();
			while ($row = $result->fetch())
				$accountsHandler->CalculateAccountBalances($row['account_id']);
		}

		return $result;
	}
}

