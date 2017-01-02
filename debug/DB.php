<?php
include '../configuration/configuration.php';

$dns = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
echo 'DNS = '.$dns.'<br />';
$utilisateur = $DB_USER;
$motDePasse = $DB_PASSWORD;
$this->_connection = new PDO( $dns, $utilisateur, $motDePasse );
$this->_connection->exec("SET CHARACTER SET utf8");
$this->_dbTablePrefix = $DB_TABLE_PREFIX;

$this->_isReadOnly = $READ_ONLY;

echo 'Connected<br />';



/*
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

*/