<?php
include '../configuration/configuration.php';
include 'dal.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Private Relationship Manager setup wizard</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
<style type="text/css" title="currentStyle">
	@import "setup.css";
</style>
</head>
<body id="prm_body">
<h1>Private Relationship Manager setup wizard</h1>
<?php

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
$mysqli->multi_query("SET NAMES 'utf8'");

/* check connection */
 if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}

function apply_base_script($ApplicationName)
{
	global $mysqli;
	global $DB_TABLE_PREFIX;

	echo '<h3>Apply base script</h3>';

	if ($ApplicationName == '')
		$sqlFileToExecute = 'database/base_script.sql';
	else
		$sqlFileToExecute = 'database_'.$ApplicationName.'/base_script.sql';
	$f = fopen($sqlFileToExecute,'r');
	$query = fread($f,filesize($sqlFileToExecute));
	fclose($f);

	$query = str_replace("sf_", $DB_TABLE_PREFIX."sf_", $query);
	/* execute multi query */
	if ($mysqli->multi_query($query))
	{
		do
		{
			/* store first result set */
			if ($result = $mysqli->store_result())
			{
				while ($row = $result->fetch_row())
				{
					printf("%s<br />", $row[0]);
				}
				$result->free();
			}

			if ($mysqli->more_results())
			{
				$continue = true;
				$mysqli->next_result();
			}
			else
				$continue = false;
		}
		while ($continue);
	}

	echo 'Base script applied!<br />';

	update_current_database_version($Application_name, 0);

	echo 'Current database version updated!<br />';
}

function apply_upgrade_script($ApplicationName, $number)
{
	global $mysqli;
	global $DB_TABLE_PREFIX;

	echo '<h3>Apply upgrade script '.str_pad($number, 3, "0", STR_PAD_LEFT).'</h3>';

	if ($ApplicationName == '')
		$sqlFileToExecute = 'database/upgrade_script.'.str_pad($number, 3, "0", STR_PAD_LEFT).'.sql';
	else
		$sqlFileToExecute = 'database_'.$ApplicationName.'/upgrade_script.'.str_pad($number, 3, "0", STR_PAD_LEFT).'.sql';
	$f = fopen($sqlFileToExecute,'r');
	$query = fread($f,filesize($sqlFileToExecute));
	fclose($f);

	$query = str_replace("sf_", $DB_TABLE_PREFIX."sf_", $query);

	/* execute multi query */
	if ($mysqli->multi_query($query))
	{
		do
		{
			/* store first result set */
			if ($result = $mysqli->store_result())
			{
				while ($row = $result->fetch_row())
				{
					printf("%s<br />", $row[0]);
				}
				$result->free();
			}

			if ($mysqli->more_results())
			{
				$continue = true;
				$mysqli->next_result();
			}
			else
				$continue = false;
		}
		while ($continue);
	}

	echo 'Upgrade script applied!<br />';

	update_current_database_version($ApplicationName, $number);

	echo 'Current database version updated!<br />';
}

function UpgradeAppZoneApplication($application_name)
{
	global $mysqli;

	$current_database_version = get_current_database_version($application_name);
	$expected_database_version = get_expected_database_version($application_name);

	if ($current_database_version == -100)
	{
		apply_base_script($application_name);
		$current_database_version = 0;
	}

	for ($script_number = $current_database_version + 1; $script_number <= $expected_database_version; $script_number++)
	{
		apply_upgrade_script($application_name, $script_number, $mysqli);
	}
}

?>

<h2>Database upgrade / global for all AppZone</h2>
<?php UpgradeAppZoneApplication(''); ?>

<h2>Database upgrade / PRM</h2>
<?php UpgradeAppZoneApplication('prm'); ?>

<h2>Database upgrade / GFC</h2>
<?php UpgradeAppZoneApplication('gfc'); ?>

<h2>Database upgrade / UNP</h2>
<?php UpgradeAppZoneApplication('unp'); ?>

<h2>Database upgrade / WorldMap</h2>
<?php UpgradeAppZoneApplication('worldmap'); ?>

<?php

$mysqli->close();

?>
<br />
<button onclick="window.location='setup.php';">Back to setup wizard home page</button>
<br />
<br />
<button onclick="window.location='../index.php';">Back to AppZone</button>
</body>
</html>