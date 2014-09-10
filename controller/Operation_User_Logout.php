<?php
class Operation_User_Logout extends Operation_User
{
	public function Save()
	{
		$usersHandler = new UsersHandler();
		$usersHandler->UnsetSessionUser();
	}

	public function IsSessionRequired()
	{
		return false;
	}
}