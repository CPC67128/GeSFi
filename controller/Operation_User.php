<?php
class Operation_User extends Operation
{
	protected $_email;
	protected $_name;
	protected $_passwordMD5;
	protected $_password;
	protected $_passwordConfirmation;

	// -------------------------------------------------------------------------------------------------------------------

	public function ValidateEmail()
	{
		if (!isset($this->_email)
				|| strlen(trim($this->_email)) == 0)
			throw new Exception("Merci de renseigner l'email");
	}

	// Get from http://www.linuxjournal.com/article/9585 written by Douglas Lovell
	public function ValidateEmail_Structure()
	{
		$email = $this->_email;

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

	public function ValidatePasswordMD5()
	{
		if (!isset($this->_passwordMD5)
				|| strlen(trim($this->_passwordMD5)) == 0)
			throw new Exception("Merci de renseigner le mot de passe");
	}

	public function ValidatePassword()
	{
		if (!isset($this->_password))
			throw new Exception("Merci de renseigner le mot de passe");
	}
	
	public function ValidatePasswordConfirmation()
	{
		if (!isset($this->_passwordConfirmation)
				|| $this->_passwordConfirmation != $this->_password)
			throw new Exception("Merci de confirmer le mot de passe");
	}
	
	public function ValidateCaptcha()
	{
		// reCaptcha verification
		require_once('../3rd_party/recaptcha-php/recaptchalib.php');
		$privatekey = "6Ld6LNYSAAAAADITZeCMzL3vquoz7AqDOoeBsO3O";
		$resp = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
		
		if (!$resp->is_valid)
		{
			throw new Exception('Captcha non vérifié');
		}
	}
}