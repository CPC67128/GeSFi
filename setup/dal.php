<?php

function get_current_database_version($ApplicationName)
{
	include 'dal_db_use_start.php';

	$requete = "select database_version from ".$DB_TABLE_PREFIX."sf_ccb where application_name = '".$ApplicationName."' order by database_version desc limit 1";
	$resultat = mysql_query($requete);

	if (mysql_errno() == 1146) // Table sf_ccb doesn't exist
	{
		$database_version = -100;
	}
	else if (mysql_errno() > 0)
	{
		die ('Unknown error: '.mysql_errno(). ' - '.mysql_error());
	}
	else
	{
		if (mysql_num_rows($resultat) > 0)
		{
			$ligne = mysql_fetch_assoc($resultat);
			$database_version = $ligne["database_version"];
		}
		else
		{
			$database_version = -100;
		}
		mysql_free_result($resultat);
	}

	include 'dal_db_use_stop.php';

	return $database_version;
}

function get_expected_database_version($ApplicationName)
{
	$database_version = 0;

	$dirname = 'database/';
	if ($ApplicationName != '')
	{
		$dirname = 'database_'.$ApplicationName.'/';
	}
	$dir = opendir($dirname); 
	
	while ($file = readdir($dir))
	{
		if (preg_match("/upgrade_script\.([0-9]{3})\.sql$/", $file, $matches))
		{
			if (intval($matches[1]) > $database_version)
				$database_version = intval($matches[1]);  
		}
	}

	closedir($dir);

	return $database_version;
}


function update_current_database_version($ApplicationName, $number)
{
	include 'dal_db_use_start.php';

	$requete = "insert into ".$DB_TABLE_PREFIX."sf_ccb (application_name, database_version, upgrade_date) values ('".$ApplicationName."', ".$number.", now())";
	$resultat = mysql_query($requete) or die ('Unknown error: '.mysql_errno(). ' - '.mysql_error());

	include 'dal_db_use_stop.php';
}

?>