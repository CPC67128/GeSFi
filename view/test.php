<?php
function __autoload($class_name)
{
	include '../model/'.$class_name . '.php';
}

$db = new DB();
?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
echo $db->ConvertStringForSqlInjection("D’r Hans em Schnokeloch  hät alles, was er well!
Und was er  hät, des well er net
und was er well, des  hät er net");
?></body>
</html>