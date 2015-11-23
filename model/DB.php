<?php
class DB
{
	private $_connection;
	private $_dbTablePrefix;
	private $_isReadOnly = false;
	private $_userId;
	private $_accountId;
	private $_duoId;

	public function __construct()
	{
		if (isset($_SESSION['user_id']))
			$this->_userId = $_SESSION['user_id'];

		if (isset($_SESSION['account_id']))
			$this->_accountId = $_SESSION['account_id'];

		$this->Connect();
	}

	public function __destruct()
	{
		$this->_connection = null;
	}

	private function Connect()
	{
		try
		{
			include '../configuration/configuration.php';

			$dns = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
			$utilisateur = $DB_USER;
			$motDePasse = $DB_PASSWORD;
			$this->_connection = new PDO( $dns, $utilisateur, $motDePasse );
			$this->_connection->exec("SET CHARACTER SET utf8");
			$this->_dbTablePrefix = $DB_TABLE_PREFIX;

			$this->_isReadOnly = $READ_ONLY;
		}
		catch ( Exception $e )
		{
			echo "Connection à MySQL impossible : ", $e->getMessage();
			die();
		}
	}



	function ConvertStringForSqlInjection($data)
	{
		if (get_magic_quotes_gpc())
		{
			$data = stripslashes($data); // Removes magic_quotes_gpc slashes
		}
		$data = mysql_real_escape_string($data);

		return $data;
	}

	function UpdateConfigurationField($fieldName, $fieldValue)
	{
		if ($this->_isReadOnly)
			return 0;

		$query = sprintf("update ".$this->_dbTablePrefix."configuration set %s = '%s' where user_id = '%s'",
				$fieldName,
				$fieldValue,
				$this->_userId);
		$result = $this->_connection->exec($query);
	
		return $result;
	}
	
	function SelectConfigurationRow()
	{
		$query = sprintf('select * from '.$this->_dbTablePrefix.'configuration where user_id = \'%s\'',
				$this->_userId);
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

		return $result->fetch();
	}

	function SelectRow($query)
	{
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
	
		return $result->fetch();
	}

	function Execute($query)
	{
		return $this->_Execute($query, false);
	}

	function _Execute($query, $returnQueryInException)
	{
		if ($this->_isReadOnly)
			return 0;
	
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		if ($returnQueryInException)
			throw new Exception($query);
		else
		{
			$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
			return $result->fetch();
		}
	}

	function Select($query)
	{
		return $this->_Select($query, false);
	}

	function _Select($query, $returnQueryInException)
	{
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		if ($returnQueryInException)
			throw new Exception($query);
		else
		{
			$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
			return $result;
		}
	}

	function Parse($query)
	{
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		return $query;
	}

	function GenerateUUID()
	{
		$query = sprintf('select uuid()');
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
		$row = $result->fetch();

		return $row[0];
	}

	function ReverseCategory($category1, $category2)
	{
		if ($this->_isReadOnly)
			return 0;

		$query = 'select category'.$category1.' from '.$this->_dbTablePrefix.'configuration where user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
		$row = $result->fetch();
		$category1Name = $row['category'.$category1];
		
		$query = 'update '.$this->_dbTablePrefix.'configuration set category'.$category1.' = category'.$category2.' where user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->exec($query);
		
		$query = 'update '.$this->_dbTablePrefix.'configuration set category'.$category2.' = \''.String2StringForSprintfQueryBuilder($category1Name).'\' where user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->exec($query);
		
		$query = 'update '.$this->_dbTablePrefix.'records set category = '.(20 + $category1).' where category = '.$category1.' and user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->exec($query);
		
		$query = 'update '.$this->_dbTablePrefix.'records set category = '.$category1.' where category = '.$category2.' and user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->exec($query);
		
		$query = 'update '.$this->_dbTablePrefix.'records set category = '.$category2.' where category = '.(20 + $category1).' and user_id = \''.$this->_userId.'\'';
		$result = $this->_connection->exec($query);

		return $result;
	}

	/*************************************************************************************************/

	/***** record *****/

	function InsertRecord($accountId,
						  $userId,
					      $actor,
						  $recordDate,
						  $amount,
						  $designation,
						  $charge,
						  $category,
						  $recordType,
						  $confirmed,
						  $recordGroupId)
	{
		if ($this->_isReadOnly)
			return 0;
	
		$query = sprintf("insert into ".$this->_dbTablePrefix."record (account_id, user_id, record_date, marked_as_deleted, designation, record_type, amount, actor, charge, category_id, record_group_id, record_id, confirmed)
				values ('%s', '%s', '%s', 0, '%s', %s, %s, %s, %s, '%s', '%s', uuid(), %s)",
				$accountId,
				$userId,
				$recordDate,
				$this->ConvertStringForSqlInjection($designation),
				$recordType,
				$amount,
				$actor == null ? 0 : $actor,
				$charge,
				$category == null ? "" : $category,
				$recordGroupId == null ? "" : $recordGroupId,
				$confirmed);
		//throw new Exception($query);
		$result = $this->_connection->exec($query);
	
		$queryMonthYearFill = "update ".$this->_dbTablePrefix."record set record_date_month = month(record_date), record_date_year = year(record_date) where record_date_month = -1";
		$resultMonthYearFill = $this->_connection->exec($queryMonthYearFill);

		return $result;
	}

	function InsertRecord_Remark($accountId, $userId, $recordDate, $designation)
	{
		return $this->InsertRecord($accountId,
								   $userId,
								   null,
								   $recordDate,
								   0,
								   $designation,
								   0,
								   null,
								   2,
								   0,
								   null);
	}

	function InsertRecord_AmountTransfer($accountId, $userId, $recordDate, $amount, $designation, $recordType, $recordGroupId)
	{
		return $this->InsertRecord($accountId,
				$userId,
				null,
				$recordDate,
				$amount,
				$designation,
				0,
				null,
				$recordType,
				0,
				$recordGroupId);
	}
	
	function InsertRecord_AmountUse($accountId, $userId, $recordDate, $amount, $designation, $charge, $category, $recordType, $confirmed, $recordGroupId)
	{
		return $this->InsertRecord(
				$accountId,
				$userId,
				null,
				$recordDate,
				$amount,
				$designation,
				$charge,
				$category,
				$recordType,
				$confirmed,
				$recordGroupId);
	}

	function DeleteRecord($recordId)
	{
		if ($this->_isReadOnly)
			return 0;

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
		if ($this->_isReadOnly)
			return 0;
	
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

	function InsertInvestmentRecord_Remark($accountId, $recordDate, $designation)
	{
		return $this->InsertInvestmentRecord($accountId,
											 null,
											 $recordDate,
											 $designation,
											 null,
											 null,
											 null,
											 2,
											 null,
											 null);
	}
	
	function InsertInvestmentRecord_Income($accountId, $recordGroupId, $recordDate, $designation, $payment, $paymentInvested)
	{
		return $this->InsertInvestmentRecord($accountId,
									 		 $recordGroupId,
											 $recordDate,
											 $designation,
											 $payment,
											 $paymentInvested,
											 null,
											 10,
											 null,
											 null);
	}

	function InsertInvestmentRecord_Withdrawal($accountId, $recordGroupId, $recordDate, $designation, $withdrawal)
	{
		return $this->InsertInvestmentRecord($accountId,
									 		 $recordGroupId,
											 $recordDate,
											 $designation,
											 null,
											 null,
											 null,
											 20,
											 $withdrawal,
											 null);
	}

	function InsertInvestmentRecord_Value($accountId, $recordDate, $designation, $value)
	{
		return $this->InsertInvestmentRecord($accountId,
											 null,
											 $recordDate,
											 $designation,
											 null,
											 null,
											 $value,
											 30,
											 null,
											 null);
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
		if ($this->_isReadOnly)
			return 0;
	
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
	
	/***** user *****/
	// OBSOLETE
	function InsertUser(
			$email,
			$name,
			$passwordHash)
	{
		if ($this->_isReadOnly)
			return 0;
	
		$query = sprintf("insert into ".$this->_dbTablePrefix."user (email, name, password, subscription_date, user_id)
				values ('%s', '%s', '%s', now(), uuid())",
				$this->ConvertStringForSqlInjection($email),
				$this->ConvertStringForSqlInjection($name),
				$this->ConvertStringForSqlInjection($passwordHash));
		//throw new Exception($query);
	
		$result = $this->_connection->exec($query);
	
		return $result;
	}
}