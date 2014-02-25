<?php
class InvestmentsRecordsManager
{
	function GetAllRecords($month)
	{
		$db = new DB();
		$query = "select INR.*
			from {TABLEPREFIX}investment_record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and marked_as_deleted = 0
			and INR.account_id = '{ACCOUNTID}'
			order by record_date desc, creation_date desc";
		$result = $db->Select($query);
		return $result;
	}

	function GetAllRecordsForAllInvestments()
	{
		$db = new DB();
		$query = "select ACC.name, INR.*
			from {TABLEPREFIX}investment_record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and marked_as_deleted = 0
			order by ACC.account_id, INR.record_date";
		$result = $db->Select($query);
		return $result;
	}

	function GetAllRecordsForSomeInvestments($accountsId)
	{
		$db = new DB();
		$query = "select ACC.name, INR.*
			from {TABLEPREFIX}investment_record INR
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
		$accountsManager = new AccountsManager();
		$account = $accountsManager->GetCurrentActiveAccount();
		$creationDate = $account->get('creationDate');

		$yearsSinceAccountCreation = (int) ((strtotime(date("Y-m-d")) - strtotime($creationDate)) / 86400) / 365.25;

		// Search for investment records
		$query = "select INR.*
			from {TABLEPREFIX}investment_record INR
			inner join {TABLEPREFIX}account ACC on ACC.account_id = INR.account_id
			where ACC.owner_user_id = '{USERID}'
			and marked_as_deleted = 0
			and INR.account_id = '".$account->get('accountId')."'
			order by record_date, creation_date";
		$records = $db->Select($query);

		// Calculate
		$paymentAccumulated = 0;
		$paymentInvestedAccumulated = 0;

		$updateQueryString = "update {TABLEPREFIX}investment_record
			set
			CALC_days_since_creation = %s,
			CALC_payment_accumulated = %s,
			CALC_payment_invested_accumulated = %s,
			CALC_gain = %s,
			CALC_yield = %s,
			CALC_yield_average = %s
			where investment_record_id = '%s'";

		unset($creationDate);
		while ($record = $records->fetch())
		{
			if (!isset($creationDate))
				$creationDate = $record['record_date'];

			$paymentAccumulated += $record['payment'];
			$paymentInvestedAccumulated += $record['payment_invested'];
			$daysSinceCreation = (int) (strtotime($record['record_date']) - strtotime($creationDate)) / 86400;

			unset($gain);
			unset($yield);
			unset($yieldAverage);
			if (isset($record['value']))
			{
				$gain = $record['value'] - $paymentAccumulated;
				if ($paymentAccumulated != 0)
					$yield = (($record['value'] / $paymentAccumulated) - 1) * 100;

				$yearsSinceCreation = (float) $daysSinceCreation / 365.25;

				if ($yearsSinceCreation != 0)
				{
					if ($yearsSinceCreation >= 1)
					{
						if ($yearsSinceAccountCreation >= 1)
							$yieldAverage = pow((float) abs($yield), (1 / $yearsSinceCreation)) * ($yield < 0 ? -1 : 1);
						else
							unset($yieldAverage);
					}
					else
					{
						$gainOver1Year = (float) ($gain / $yearsSinceCreation);
						if ($yearsSinceAccountCreation >= 1)
							$yieldAverage = ((($paymentAccumulated + $gainOver1Year) / $paymentAccumulated) - 1);
						else
							unset($yieldAverage);
					}
				}
				else
				{
					$yieldAverage = 0;
				}
			}

			$query = sprintf($updateQueryString,
				$daysSinceCreation,
				$paymentAccumulated,
				$paymentInvestedAccumulated,
				isset($gain) ? $gain : 'null',
				isset($yield) ? $yield : 'null',
				isset($yieldAverage) ? $yieldAverage : 'null',
				$record['investment_record_id']);
			$db->Execute($query);
		}
	}
}

