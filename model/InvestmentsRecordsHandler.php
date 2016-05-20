<?php
class InvestmentsRecordsHandler extends Handler
{
	function GetAllRecords()
	{
		$db = new DB();
		$query = "select INR.*
			from {TABLEPREFIX}record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and marked_as_deleted = 0
			and INR.account_id = '{ACCOUNTID}'
			order by record_date desc, creation_date desc";
		$result = $db->Select($query);
		return $result;
	}

	// This function is used for the global chart
	function GetAllRecordsForAllInvestments()
	{
		$db = new DB();
		$query = "select ACC.name, INR.*
			from {TABLEPREFIX}record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			left join {TABLEPREFIX}account_user_preference as PRF on ACC.account_id = PRF.account_id and PRF.user_id = '{USERID}'
			where ACC.owner_user_id = '{USERID}'
			and ACC.type = 10
			and ACC.marked_as_closed = 0 
			and marked_as_deleted = 0
			and value is not null
			order by PRF.sort_order, ACC.account_id, INR.record_date";
		$result = $db->Select($query);
		return $result;
	}

	function GetAllRecordsForSomeInvestments($accountsId)
	{
		$db = new DB();
		$query = "select ACC.name, INR.*
			from {TABLEPREFIX}record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and ACC.account_id in (".$accountsId.")
			and marked_as_deleted = 0
			order by ACC.account_id, INR.record_date";
		$result = $db->Select($query);
		return $result;
	}
	
	function CalculateIndicators()
	{
		$db = new DB();

		// Search for account data
		$accountsHandler = new AccountsHandler();
		$account = $accountsHandler->GetCurrentActiveAccount();
		$creationDate = $account->get('creationDate');

		$yearsSinceAccountCreation = (int) ((strtotime(date("Y-m-d")) - strtotime($creationDate)) / 86400) / 365.25;

		// Search for investment records
		$query = "select INR.*
			from {TABLEPREFIX}record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and marked_as_deleted = 0
			and INR.account_id = '".$account->get('accountId')."'
			order by record_date, creation_date";
		$records = $db->Select($query);

		// Calculate
		$amountAccumulated = 0;
		$amountInvestedAccumulated = 0;
		$incomeSum = 0;
		$withdrawalSum = 0;

		$updateQueryString = "update {TABLEPREFIX}record
			set
			CALC_days_since_creation = %s,
			CALC_amount_accumulated = %s,
			CALC_amount_invested_accumulated = %s,
			CALC_gain = %s,
			CALC_yield = %s,
			CALC_yield_average = %s,
			CALC_withdrawal_sum = %s,
			CALC_income_sum = %s
			where record_id = '%s'";

		unset($creationDate);
		while ($record = $records->fetch())
		{
			if (!isset($creationDate))
				$creationDate = $record['record_date'];

			$amountAccumulated += $record['amount'];
			$amountInvestedAccumulated += $record['amount_invested'];
			$incomeSum += ($record['record_type'] == 40 ? $record['income'] : 0);
			$withdrawalSum += $record['withdrawal'];
			$daysSinceCreation = (int) (strtotime($record['record_date']) - strtotime($creationDate)) / 86400;

			unset($gain);
			unset($yield);
			unset($yieldAverage);
			if (isset($record['value']))
			{
				$gain = $record['value'] + $withdrawalSum - $amountAccumulated;

				if ($amountAccumulated != 0 && $amountInvestedAccumulated > 0)
				{
					$yield = ($record['value'] + $withdrawalSum) / $amountAccumulated;
					//$yield = (($record['value'] / $amountAccumulated) - 1) * 100;
				}

				$yearsSinceCreation = $daysSinceCreation / 365.25;

				// Calculation of the average yield
				unset($yieldAverage);
				if (floor($yearsSinceCreation) > 0)
				{
					if ($amountInvestedAccumulated > 0)
					{
						$yieldAverage = pow($yield, (1 / $yearsSinceCreation));
						//pow(abs($yield), (1 / $yearsSinceCreation)) * ($yield < 0 ? -1 : 1);
					}
				}
			}

			$query = sprintf($updateQueryString,
				$daysSinceCreation,
				$amountAccumulated,
				$amountInvestedAccumulated,
				isset($gain) ? $gain : 'null',
				isset($yield) ? ($yield - 1) * 100 : 'null',
				isset($yieldAverage) ? ($yieldAverage - 1) * 100 : 'null',
				isset($withdrawalSum) ? $withdrawalSum : 'null',
				isset($incomeSum) ? $incomeSum : 'null',
				$record['record_id']);
			$db->Execute($query);
		}
	}


	/***** investment_record *****/


	function InsertInvestmentRecord($accountId,
			$recordGroupId,
			$recordDate,
			$designation,
			$payment,
			$paymentInvested,
			$value,
			$recordType,
			$withdrawal,
			$income)
	{
		$db = new DB();
	
		$query = sprintf("insert into ".$this->_dbTablePrefix."record (account_id, record_group_id, record_date, designation, amount, amount_invested, value, record_id, record_type, withdrawal, income)
				values ('%s', '%s', '%s', '%s', %s, %s, %s, uuid(), %s, %s, %s)",
				$accountId,
				$recordGroupId == null ? "" : $recordGroupId,
				$recordDate,
				$this->ConvertStringForSqlInjection($designation),
				$payment == null ? "null" : $payment,
				$paymentInvested == null ? "null" : $paymentInvested,
				$value == null ? "null" : $value,
				$recordType == null ? "0" : $recordType,
				$withdrawal == null ? "null" : $withdrawal,
				$income == null ? "null" : $income);
		//throw new Exception($query);
	
		$result = $this->_connection->exec($query);
	
		return $result;
	}

	function InsertInvestmentRecord_IncomeSpecial($accountId, $recordGroupId, $recordDate, $designation, $income)
	{
		return $this->InsertInvestmentRecord($accountId,
				$recordGroupId,
				$recordDate,
				$designation,
				null,
				null,
				null,
				40,
				null,
				$income);
	}
	
	function DeleteInvestmentRecord($recordId)
	{
		$db = new DB();
	
		$sql = "select record_group_id from {TABLEPREFIX}record where record_id = '".$recordId."'";
		$row = $this->SelectRow($sql);
		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set marked_as_deleted = 1 where record_group_id = '".$row['record_group_id']."'";
		}
		else
		{
			$sql = "update {TABLEPREFIX}record set marked_as_deleted = 1 where record_id = '".$recordId."' and account_id = '{ACCOUNTID}'";
		}
		$result = $this->Execute($sql);
	
		if (strlen($row['record_group_id']) > 0)
		{
			$sql = "update {TABLEPREFIX}record set marked_as_deleted = 1 where record_group_id = '".$row['record_group_id']."'";
		}
		$result = $this->Execute($sql);
	
		return $result;
	}
}

