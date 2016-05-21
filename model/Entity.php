<?php
class Entity
{
	public function set($member, $value)
	{
		$member = '_'.$member;
		$this->$member = $value;

		if (!isset($this->$member))
			throw new Exception('Unknow attribute '.$member);
	}
	
	public function get($member)
	{
		if (isset($this->$member))
			return $this->$member;

		$member = '_'.$member;
		if (isset($this->$member))
			return $this->$member;

		$function = 'get'.$member;
		if (method_exists($this, $function))
			return $this->$function();
		else
			throw new Exception('Unknow attribute '.$member);
	}
	
	public function getIfSetOrDefault($member, $default)
	{
		if (isset($this->$member))
			return $this->$member;

		$member = '_'.$member;
		if (isset($this->$member))
			return $this->$member;
		else
			return $default;
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$key = $this->GetMemberNameFromFieldName($key);
			$this->set($key, $value);
		}
	}

	protected function GetMemberNameFromFieldName($name)
	{
		$words = explode('_', strtolower($name));
	
		$return = '';
		foreach ($words as $word) {
			$return .= ucfirst(trim($word));
		}

		return lcfirst($return);
	}
}
