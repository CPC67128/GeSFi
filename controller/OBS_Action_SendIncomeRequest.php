<?php
class Action_SendIncomeRequest extends Action
{
	protected $_actor1AskedIncome;
	protected $_actor2AskedIncome;
	protected $_actor1Email;
	protected $_actor2Email;

	public function setActor1AskedIncome($actor1AskedIncome)
	{
		if (!isset($actor1AskedIncome))
			throw new Exception('Merci de renseigner correctement la somme');

		$this->_actor1AskedIncome = $actor1AskedIncome;
	}

	public function setActor2AskedIncome($actor2AskedIncome)
	{
		if (!isset($actor2AskedIncome))
			throw new Exception('Merci de renseigner correctement la somme');

		$this->_actor2AskedIncome = $actor2AskedIncome;
	}

	public function setActor1Email($actor1Email)
	{
		$this->_actor1Email = $actor1Email;
	}
	
	public function setActor2Email($actor2Email)
	{
		$this->_actor2Email = $actor2Email;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------
	
	public function Execute()
	{
		if (!isset($this->_actor1Email) && !isset($this->_actor2Email))
			throw new Exception('Merci de renseigner au moins un email');
		if (strlen($this->_actor1Email) == 0 && strlen($this->_actor2Email) == 0)
			throw new Exception('Merci de renseigner au moins un email');
		
		include '../configuration/configuration.php';

		$configuration = new Configuration();
		$configuration->Get();
		$translator = new Translator();

		$result = false;
		$headers = 'From: "GFC"<'.$EMAIL_FROM.'>'."\n";
		$headers .= 'Content-Type: text/html; charset="utf-8"'."\n";
		$headers .= 'Content-Transfer-Encoding: 8bit';
		
		$subject = "Appel à versement sur le compte commun";

		$body = '<html><body>';
		$body .= '<b>Le compte commun a atteint son seuil.</b>';
		$body .= '<br /><br />';
		$body .= 'Merci de procéder aux versements suivants dès que possible :<br />';
		$body .= '<ul><li>';
		$body .= $configuration->getActor1().' : '.$translator->getCurrencyValuePresentation($this->_actor1AskedIncome);
		$body .= '</li><li>';
		$body .= $configuration->getActor2().' : '.$translator->getCurrencyValuePresentation($this->_actor2AskedIncome);
		$body .= '</li></ul>';
		$body .= '<br /><br />';
		$body .= '</body></html>';

		if (strlen($this->_actor1Email) > 0)
			mail($this->_actor1Email, $subject, $body, $headers);

		if (strlen($this->_actor2Email) > 0)
			mail($this->_actor2Email, $subject, $body, $headers);
	}
}