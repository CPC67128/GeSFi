<?php
function SendEmail($To, $Subject, $Body)
{
	include '../configuration/configuration.php';

	if (!$EMAIL_ACTIVE)
		return true;

	$result = false;
	$headers = "From: ".$EMAIL_FROM."\r\n"."X-Mailer: php";
	if (mail($To, $Subject, $Body, $headers))
		$result = true;

	return $result;
}

function SendEmailToAdministrator($Subject, $Body)
{
	include '../configuration/configuration.php';

	return SendEmail($EMAIL_ADMINISTRATION, $Subject, $Body);
}