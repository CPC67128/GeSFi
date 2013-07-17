<?php
include '../configuration/configuration.php';
if ($SECURITY_SINGLE_USER_MODE)
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: index.php");
	exit();
}

session_start();

$EMAIL = 'nonexisting';
$USER_ID = -1;

define('EMAIL', $_SESSION["email"]);
define('USER_ID', $_SESSION["user_id"]);

session_unset();
session_destroy();
?>
<html>
<header>
<meta charset="utf-8">
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<script type="text/javascript">
window.location = 'login.php';
</script>
</header>
<body>
Déconnection en cours...
</body>
</html>