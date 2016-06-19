<?php
include '../component/component_autoload.php';
include '../component/component_security.php';
$translator = new Translator();
?>
<!DOCTYPE html>
<html>
<head>
<title><?= $translator->getTranslation("GeSFi : Logiciel de comptabilité personnelle") ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="Description" content="Application en ligne gratuite de gestion financière du couple (compatibilité de couple ou comptabilité commune) écrite par Steve Fuchs">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link type="text/css" href="gesfi.css" rel="stylesheet" />

<link rel="stylesheet" href="../3rd_party/jquery-ui-1.11.4/jquery-ui.min.css">
<script src="../3rd_party/jquery-2.2.4.min.js"></script>
<script src="../3rd_party/jquery-ui-1.11.4/jquery-ui.min.js"></script>

<script src="../3rd_party/js/highcharts.js"></script>
<script src="../3rd_party/js/modules/exporting.js"></script>

<script type="text/javascript" src="gesfi.js"></script>
<script type="text/javascript" src="gesfi_operations.js"></script>
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
