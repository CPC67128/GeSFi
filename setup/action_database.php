<?php

function my_autoloader($className) {
	include $className.'.php';
}

spl_autoload_register('my_autoloader');

$db = new DB();
$scriptsFolder = new ScriptsFolder();

?>
<!DOCTYPE html>
<html>
<head>
<title>Web Application - Upgrade Wizard / (C) Steve Fuchs</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
<style type="text/css" title="currentStyle">
	@import "setup.css";
</style>
</head>
<body id="prm_body">
<h1>Web Application - Upgrade Wizard / (C) Steve Fuchs</h1>
<h2>Database Upgrade</h2>

<?php

function ApplyScriptForVersion($version)
{
	$operationResult = true;
	$operationMessage = '';

	global $db;

	if ($version == 0)
		$file = 'initial.sql';
	else if ($version > 0)
		$file = 'upgrade.'.str_pad($version, 3, "0", STR_PAD_LEFT).'.sql';

	echo 'Apply script "'.$file.'"... ';

	$sqlFileToExecute = 'scripts/'.$file;

	if (!file_exists($sqlFileToExecute))
	{
		echo "Script not existing, bypassed.<br/>";
		return $operationResult;
	}

	$f = fopen($sqlFileToExecute,'r');
	$query = fread($f,filesize($sqlFileToExecute));
	fclose($f);

	try
	{
		$db->ExecuteMultipleQueries($query);
	}
	catch (Exception $e)
	{
		$operationResult = false;
		$operationMessage = str_replace("\n", "<br />", $e->getMessage());
	}

	if ($operationResult)
	{
		echo "Script applied successfully.<br/>";
	}
	else
	{
		echo "An error occured during the script execution! ".$operationMessage."<br/>";
	}

	return $operationResult;
}


$currentDatabaseVersion = $db->GetCurrentDatabaseVersion();
$expectedDatabaseVersion = $scriptsFolder->GetExpectedDatabaseVersion();

for ($version = $currentDatabaseVersion + 1; $version <= $expectedDatabaseVersion; $version++)
{
	$operationResult = ApplyScriptForVersion($version);
	if ($operationResult)
	{
		$db->UpdateCurrentDatabaseVersion($version);
	}
	else
		break;
}

?>
<br />
<button onclick="window.location='index.php';">Back to setup wizard home page</button>
</body>
</html>