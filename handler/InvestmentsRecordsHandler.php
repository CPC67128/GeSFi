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
		$lastKnowValue = null;
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
				$lastKnowValue = $record['value'];

				$gain = $record['value'] + $withdrawalSum - $amountAccumulated;

				if ($amountAccumulated != 0 && $amountInvestedAccumulated > 0)
				{
					$yield = ($record['value'] + $withdrawalSum + $incomeSum) / $amountAccumulated;
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
			elseif (isset($record['income']) && isset($lastKnowValue))
			{
				$gain = $lastKnowValue + $withdrawalSum - $amountAccumulated;
			
				if ($amountAccumulated != 0 && $amountInvestedAccumulated > 0)
				{
					$yield = ($lastKnowValue + $withdrawalSum + $incomeSum) / $amountAccumulated;
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
			elseif (!isset($record['income']) && !isset($record['value']) && isset($lastKnowValue))
			{
				$lastKnowValue += $record['amount'];
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
}

