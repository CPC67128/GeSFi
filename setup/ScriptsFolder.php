<?php

class ScriptsFolder
{
	private $_directory = 'scripts/';

	public function __construct()
	{
	}

	public function __destruct()
	{
	}

	public function GetExpectedDatabaseVersion()
	{
		$version = 0; // Initial version number = initial.sql

		$directory = opendir($this->_directory); 
		
		while ($file = readdir($directory))
		{
			if (preg_match("/upgrade\.([0-9]{3})\.sql$/", $file, $matches))
			{
				if (intval($matches[1]) > $version)
					$version = intval($matches[1]);  
			}
		}

		closedir($directory);
	
		return $version;
	}
}

?>