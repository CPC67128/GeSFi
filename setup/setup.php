<?php include 'dal.php'; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Steve Fuchs AppZone setup wizard</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
<style type="text/css" title="currentStyle">
	@import "setup.css";
</style>
</head>
<body id="prm_body">
<h1>Private Relationship Manager setup wizard</h1>
<?php

$upgradeAvailable = false;
$globalAreaExists = true;

function AnalyseApplicationStatus($Application_name)
{
	global $upgradeAvailable;
	global $globalAreaExists;

	?>
	<h2>Database upgrade / <?php if ($Application_name == '') echo 'global for all AppZone'; else echo strtoupper($Application_name); ?></h2>
	Current database version:
	<?php
	$current_database_version = get_current_database_version($Application_name);
	if ($current_database_version == -100)
		echo 'unexisting';
	else
		echo $current_database_version;
	?>
	<br />
	Expected database version: <?php $expected_database_version = get_expected_database_version($Application_name); echo $expected_database_version; ?>
	<?php if ($current_database_version != $expected_database_version) $upgradeAvailable = true; ?>
	<?php if ($Application_name == '' && $current_database_version == -100) $globalAreaExists = false; ?>
	<br />
	<?php
}

AnalyseApplicationStatus('');
AnalyseApplicationStatus('prm');
AnalyseApplicationStatus('gfc');
AnalyseApplicationStatus('unp');

?>
<br />
<button onclick="window.location='setup_upgrade_database.php';" <?php if (!$upgradeAvailable) echo 'disabled="disabled"'; ?>>Upgrade database</button>
<br />
<br />
<button onclick="window.location='../index.php';">Back to AppZone</button>
</body>
</html>
