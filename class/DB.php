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
		}
		catch ( Exception $e )
		{
			echo "Connection à MySQL impossible : ", $e->getMessage();
			die();
		}
	}

	function InsertRecord($accountId, $actor, $date, $amount, $designation, $charge, $category, $recordType, $recordGroupId)
	{
		if ($this->_isReadOnly)
			return 0;

		$query = sprintf("insert into ".$this->_dbTablePrefix."record (account_id, record_date, marked_as_deleted, designation, record_type, amount, actor, charge, category_id, record_group_id, record_id)
				values ('%s', '%s', 0, '%s', %s, %s, %s, %s, '%s', '%s', uuid())",
				$accountId,
				$date,
				$designation,
				$recordType,
				$amount,
				$actor,
				$charge,
				$category,
				$recordGroupId);
		//throw new Exception($query);
		$result = $this->_connection->exec($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
	
		$queryMonthYearFill = "update ".$this->_dbTablePrefix."record set record_date_month = month(record_date), record_date_year = year(record_date) where record_date_month = -1";
		$resultMonthYearFill = $this->_connection->exec($queryMonthYearFill) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

		return $result;
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
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
	
		return $result->fetch();
	}

	function Select($query)
	{
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());
			
		return $result;
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
}