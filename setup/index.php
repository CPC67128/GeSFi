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

function my_autoloader($className) {
	include $className.'.php';
}

spl_autoload_register('my_autoloader');

try
{
	$db = new DB();
}
catch (Exception $e)
{
	$db = null;

	echo 'Connexion à la base de données impossible.<br />';
	echo $e->getMessage().'<br />';
}

$scriptsFolder = new ScriptsFolder();

if ($db != null)
{

$currentDatabaseVersion = $db->GetCurrentDatabaseVersion();
$expectedDatabaseVersion = $scriptsFolder->GetExpectedDatabaseVersion();
$upgradeDatabaseVersion = $currentDatabaseVersion != $expectedDatabaseVersion;

?>
Current database version: <?= $currentDatabaseVersion < 0 ? 'n/a' : $currentDatabaseVersion ?>
<br />
Expected database version: <?= $expectedDatabaseVersion ?>
<br />
<br />
<button onclick="window.location='action_database.php';" <?php if (!$upgradeDatabaseVersion) echo 'disabled="disabled"'; ?>>Upgrade database</button>
<br />
<?php
}
?>
<br />
<button onclick="window.location='../index.php';">Back to Application</button>
</body>
</html>