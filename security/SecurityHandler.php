<?php
class SecurityHandler
{
	public function IsSingleUserMode()
	{
		include '../configuration/configuration.php';
		return $SECURITY_SINGLE_USER_MODE;
	}

	public function GetSingleUserModeUserId()
	{
		include '../configuration/configuration.php';
		return $SECURITY_SINGLE_USER_MODE_USER_ID;
	}
}