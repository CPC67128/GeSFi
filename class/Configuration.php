<?php
class OBSOLETE_Configuration
{
	private $_actor1;
	private $_actor2;
	private $_culture;
	private $_categories;
	private $_actor1Email;
	private $_actor2Email;
	private $_actor1DefaultChargeLevel;
	private $_realJointAccountUse;
	private $_jointAccountExpectedMinimumBalance;
	private $_jointAccountExpectedMinimumCredit;
	private $_jointAccountMaximumActorExtraCredit;

	public function setActor1($actor1)
	{
		$this->_actor1 = $actor1;
	}

	public function setActor2($actor2)
	{
		$this->_actor2 = $actor2;
	}
	
	public function setCulture($culture)
	{
		$this->_culture = $culture;
	}

	public function setCategory($categoryId, $categoryName)
	{
		$this->_categories[$categoryId] = $categoryName;
	}

	public function setActor1Email($actor1Email)
	{
		$this->_actor1Email = $actor1Email;
	}
	
	public function setActor2Email($actor2Email)
	{
		$this->_actor2Email = $actor2Email;
	}

	public function setActor1DefaultChargeLevel($actor1DefaultChargeLevel)
	{
		$this->_actor1DefaultChargeLevel = $actor1DefaultChargeLevel;
	}
	
	public function setRealJointAccountUse($realJointAccountUse)
	{
		$this->_realJointAccountUse = $realJointAccountUse;
	}
	
	public function setJointAccountExpectedMinimumBalance($jointAccountExpectedMinimumBalance)
	{
		$this->_jointAccountExpectedMinimumBalance = $jointAccountExpectedMinimumBalance;
	}
	
	public function setJointAccountExpectedMinimumCredit($jointAccountExpectedMinimumCredit)
	{
		$this->_jointAccountExpectedMinimumCredit = $jointAccountExpectedMinimumCredit;
	}
	
	public function setJointAccountMaximumActorExtraCredit($jointAccountMaximumActorExtraCredit)
	{
		$this->_jointAccountMaximumActorExtraCredit = $jointAccountMaximumActorExtraCredit;
	}

	public function getActor1()
	{
		return $this->_actor1;
	}
	
	public function getActor2()
	{
		return $this->_actor2;
	}
	
	public function getCulture()
	{
		return $this->_culture;
	}
	
	public function getCategory($categoryId)
	{
		return $this->_categories[$categoryId];
	}
	
	public function getActor1Email()
	{
		return $this->_actor1Email;
	}
	
	public function getActor2Email()
	{
		return $this->_actor2Email;
	}
	
	public function getActor1DefaultChargeLevel()
	{
		return $this->_actor1DefaultChargeLevel;
	}
	
	public function getActor2DefaultChargeLevel()
	{
		return (100 - $this->_actor1DefaultChargeLevel);
	}

	public function getRealJointAccountUse()
	{
		return $this->_realJointAccountUse;
	}
	
	public function getJointAccountExpectedMinimumBalance()
	{
		return $this->_jointAccountExpectedMinimumBalance;
	}
	
	public function getJointAccountExpectedMinimumCredit()
	{
		return $this->_jointAccountExpectedMinimumCredit;
	}
	
	public function getJointAccountMaximumActorExtraCredit()
	{
		return $this->_jointAccountMaximumActorExtraCredit;
	}

	public function getDefaultActor()
	{
		return 1;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			if (strpos($key, 'category') === 0)
			{
				$categoryId = str_replace('category', '', $key);
				$this->setCategory($categoryId, $value);
			}
			else
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function Save()
	{
		$db = new DB();

		$db->UpdateConfigurationField('actor1', $this->_actor1);
		$db->UpdateConfigurationField('actor2', $this->_actor2);
		$db->UpdateConfigurationField('culture', $this->_culture);
		for ($i = 1; $i <= 15; $i++)
			$db->UpdateConfigurationField('category'.$i, $this->_categories[$i]);
		$db->UpdateConfigurationField('actor1_email', $this->_actor1Email);
		$db->UpdateConfigurationField('actor2_email', $this->_actor2Email);
		$db->UpdateConfigurationField('actor1_default_charge', $this->_actor1DefaultChargeLevel);
		$db->UpdateConfigurationField('real_joint_account_use', $this->_realJointAccountUse);
		$db->UpdateConfigurationField('joint_account_expected_minimum_balance', $this->_jointAccountExpectedMinimumBalance);
		$db->UpdateConfigurationField('joint_account_expected_minimum_credit', $this->_jointAccountExpectedMinimumCredit);
		$db->UpdateConfigurationField('joint_account_maximum_actor_extra_credit', $this->_jointAccountMaximumActorExtraCredit);
	}

	public function Get()
	{
		$db = new DB();
		$row = $db->SelectConfigurationRow();

		$this->_actor1 = $row['actor1'];
		$this->_actor2 = $row['actor2'];
		$this->_culture = $row['culture'];
		for ($i = 1; $i <= 15; $i++)
			$this->setCategory($i, $row['category'.$i]);
		$this->_actor1Email = $row['actor1_email'];
		$this->_actor2Email = $row['actor2_email'];
		$this->_actor1DefaultChargeLevel = $row['actor1_default_charge'];
		$this->_realJointAccountUse = $row['real_joint_account_use'];
		$this->_jointAccountExpectedMinimumBalance = $row['joint_account_expected_minimum_balance'];
		$this->_jointAccountExpectedMinimumCredit = $row['joint_account_expected_minimum_credit'];
		$this->_jointAccountMaximumActorExtraCredit = $row['joint_account_maximum_actor_extra_credit'];
	}
}