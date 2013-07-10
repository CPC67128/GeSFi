<?php
include_once '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

if (!isset($_SESSION['account_id']))
{
	$_SESSION['account_id'] = 'dashboard';
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
<div id='leftMenu' class='leftMenu' align='center'>
</div>

<!-- ----------------------------------- Records display ----------------------------------- -->

<div id='content' class='content'>
<div id="records"></div>
</div>
</body>
</html>
