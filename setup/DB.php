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

			if (isset($DB_PORT))
				$dns = 'mysql:host=' . $DB_HOST . ';port='.$DB_PORT.';dbname=' . $DB_NAME;
			else
				$dns = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
			$utilisateur = $DB_USER;
			$motDePasse = $DB_PASSWORD;
			$this->_connection = new PDO( $dns, $utilisateur, $motDePasse );
			$this->_connection->exec("SET CHARACTER SET utf8");

			$this->_dbTablePrefix = $DB_TABLE_PREFIX;
			$this->_isReadOnly = $READ_ONLY;
		}
		catch (Exception $e)
		{
			throw new Exception("Erreur lors de la connexion : ".$e->getMessage());
		}
	}

	private function Execute($sql)
	{
		$sql = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $sql);
		$result = $this->_connection->query($sql);
			
		return $result;
	}

	public function ExecuteMultipleQueries($sql)
	{
		$sql = str_replace('{TABLEPREFIX}', $this->_dbTablePrefix, $sql);
		$this->_connection->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
	    $statement = $this->_connection->prepare($sql);
	
	    if (!$statement)
	    {
			throw new Exception($this->_connection->errorCode() . ' : ' . print_r($this->_connection->errorInfo(), true));
		}
		else
			$statement->execute();
	}

	public function GetCurrentDatabaseVersion()
	{
		$query = "select max(database_version) as current_version from {TABLEPREFIX}ccb";
		$result = $this->Execute($query);

		if ($this->_connection->errorCode() == 0 && $result->rowCount() > 0)
		{
			$row = $result->fetch();
			return $row["current_version"];
		}

		return -1;
	}

	public function UpdateCurrentDatabaseVersion($version)
	{
		$sql = "insert into {TABLEPREFIX}ccb (database_version, upgrade_date) values (".$version.", now())";
		$result = $this->Execute($sql);
	}
}
?>