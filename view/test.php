<?php
include '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$db = new DB();

echo $db->ConvertStringForSqlInjection("Taxe d'habitation");