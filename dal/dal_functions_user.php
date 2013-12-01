<?php

function security_IsEmailExisting($Email)
{
	include 'database_use_start.php';

	$is_email_existing = false;

	$escaped_email = String2StringForSprintfQueryBuilder($Email);

	$query = sprintf("select email from ".$DB_TABLE_PREFIX."user where lower(email) = '%s'",
		strtolower($escaped_email));

	$result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

	$row = mysql_fetch_assoc($result);
	
	if (isset($row["email"]))
	{
		$is_email_existing = true;
	}
	
	include 'database_use_stop.php';

	return $is_email_existing;
}

function security_CreateUser($Email, $Full_name, $Password)
{
    include 'database_use_start.php';

    $escaped_email = String2StringForSprintfQueryBuilder($Email);
    $escaped_full_name = String2StringForSprintfQueryBuilder($Full_name);
    $escaped_password = String2StringForSprintfQueryBuilder($Password);
    
    $query = sprintf("insert into ".$DB_TABLE_PREFIX."user (email, full_name, password, subscription_date, user_id) values('%s', '%s', '%s', curdate(), uuid())",
        strtolower($escaped_email),
    	$escaped_full_name,
    	$escaped_password);

    $result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

    include 'database_use_stop.php';

    return true;
}

function security_UpdateUser($User_id, $Email, $Full_name, $Password)
{
    include 'database_use_start.php';

    $escaped_email = String2StringForSprintfQueryBuilder(strtolower($Email));
    $escaped_full_name = String2StringForSprintfQueryBuilder($Full_name);
    $escaped_password = String2StringForSprintfQueryBuilder($Password);

    if ($Password != '') // Password has been changed by the user
    {
        $query = sprintf("update ".$DB_TABLE_PREFIX."user set email = '%s', full_name = '%s', password = '%s' where user_id = '%s'",
            $escaped_email,
            $escaped_full_name,
            $escaped_password,
            $User_id);
    }
    else
    {
        $query = sprintf("update ".$DB_TABLE_PREFIX."user set email = '%s', full_name = '%s' where user_id = '%s'",
            $escaped_email,
            $escaped_full_name,
            $User_id);
    }
    
    $result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

    include 'database_use_stop.php';

    return true;
}

function security_GetUserRow($User_id)
{
    include 'database_use_start.php';

    $query = sprintf("select * from ".$DB_TABLE_PREFIX."user where user_id = '%s'",
        $User_id);

    $result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

    $row = mysql_fetch_assoc($result);
    
    include 'database_use_stop.php';

    return $row;
}

function security_GetLastConections($User_id)
{
	include 'database_use_start.php';

	$query = sprintf("select * from ".$DB_TABLE_PREFIX."user_connection where user_id = '%s' order by connection_date_time desc limit 1,10", 
		$User_id);

	$result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

	include 'database_use_stop.php';

	return $result;
	
}

function security_GetLastConection($User_id)
{
	include 'database_use_start.php';

	$query = sprintf("select * from ".$DB_TABLE_PREFIX."user_connection where user_id = '%s' order by connection_date_time desc limit 1,1", 
		$User_id);

	$result = mysql_query($query) or die('Erreur SQL ! '.$query.'<br />'.mysql_error());

	include 'database_use_stop.php';

	return $result;
	
}

// Get from http://www.linuxjournal.com/article/9585 written by Douglas Lovell
function security_IsEmailAddressGood($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

?>
