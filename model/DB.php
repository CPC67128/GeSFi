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

			$dns = 'mysql:host=' . $DB_HOST . ';port=3307;dbname=' . $DB_NAME;
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
		$data = $this->_connection->quote($data);

		return $data;
	}

	function SelectRow($query)
	{
		$query = str_replace('{USERID}', $this->_userId, $query);
		$query = str_replace('{ACCOUNTID}', $this->_accountId, $query);
		$query = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $query);

		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.print_r($this->_connection->errorInfo(), true));
	
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
			$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.print_r($this->_connection->errorInfo(), true));
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
			$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.print_r($this->_connection->errorInfo(), true));
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
		$result = $this->_connection->query($query) or die('Erreur SQL ! '.$query.'<br />'.print_r($this->_connection->errorInfo(), true));
		$row = $result->fetch();

		return $row[0];
	}

	/*************************************************************************************************/

}