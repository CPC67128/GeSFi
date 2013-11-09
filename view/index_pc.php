<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

if (!isset($_SESSION['account_id']))
{
	$_SESSION['account_id'] = 'all_accounts';
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
<link type="text/css" href="../3rd_party/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />	
<link type="text/css" href="gfc.css" rel="stylesheet" />
<script type="text/javascript" src="../3rd_party/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../3rd_party/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="gfc.js"></script>
</head>
<body>
<div id='topMenu' class='topMenu'>
</div>
<div id='topSecondLineMenu' class='topSecondLineMenu'>
</div>
<div id='leftMenu' class='leftMenu' align='center'>
</div>

<!-- ----------------------------------- Records display ----------------------------------- -->

<div id='content' class='content'>
<div id="records"></div>
</div>
</body>
</html>
