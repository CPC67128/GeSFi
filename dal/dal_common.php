<?php
include_once '../configuration/configuration.php';

function IsReadOnly()
{
	if (READ_ONLY)
		return true;
	return false;
}

function String2StringForSprintfQueryBuilder($String)
{
	if (get_magic_quotes_gpc())
		return $String;
	else
		return mysql_real_escape_string($String);
}

