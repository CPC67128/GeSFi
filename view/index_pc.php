<?php
include_once '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

if (!isset($_SESSION['account_id']))
{
	$accountsManager = new AccountsManager();
	$defaultAccount = $accountsManager->GetDefaultAccount();
	$_SESSION['account_id'] = $defaultAccount->getAccountId();
}

$translator = new Translator();

$actor_default = 1;

$common_acount_actor1_charge = 50;
$common_acount_actor2_charge = 50;
?>
<!DOCTYPE>
<html>
<head>
<title><?= $translator->getTranslation("BudgetFox : Logiciel de comptabilité personnelle") ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="Description" content="Application en ligne gratuite de gestion financière du couple (compatibilité de couple ou comptabilité commune) écrite par Steve Fuchs">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />	
<link type="text/css" href="gfc.css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="gfc.js"></script>
</head>
<body>
<div id='topMenu' class='topMenu'>
</div>
<div class='leftMenu' align='center'>
<!-- ----------------------------------- Accordion ----------------------------------- -->

<img id='recordsMenuIcon' class='menuIcon' src="../media/recordsMenuIcon.jpg" />
<br />
Lignes
<br />
<br />
<img id='expenseMenuIcon' class='menuIcon' src="../media/expenseMenuIcon.png" />
<br />
Dépense
<br />
<br />
<img id='incomeMenuIcon' class='menuIcon' src="../media/incomeMenuIcon.gif" />
<br />
Revenu
<br />
<br />
<img id='transferMenuIcon' class='menuIcon' src="../media/transferMenuIcon.png" />
<br />
Virement
<br />
<br />
<img id='remarkMenuIcon' class='menuIcon' src="../media/remarkMenuIcon.png" />
<br />
Remarque
<br />
<br />
<img id='balanceMenuIcon' class='menuIcon' src="../media/balanceMenuIcon.png" />
<br />
Balance
<br />
<br />
<img id='statisticsMenuIcon' class='menuIcon' src="../media/statsMenuIcon.png" />
<br />
Statistiques
<br />
<br />
<img id='configurationMenuIcon' class='menuIcon' src="../media/configurationMenuIcon.png" />
<br />
Configuration
<br />
<br />
<a href="../appzone/copyright.htm">Copyright</a>
<br />
<a href="../appzone/index.php">AppZone</a>
</div>

<!-- ----------------------------------- Records display ----------------------------------- -->

<div id='content' class='content'>
<div id="records"></div>
</div>
</body>
</html>
