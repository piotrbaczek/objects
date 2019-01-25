<?php

abstract class Base{
	protected $data = [];

	public function __set($name,$value){
		$setter = 'set' . $name;
        	if (method_exists($this, $setter)) {
			$this->$setter($value);
		} else {
			$this->data[$name] = $value;
		}
	}

	public function __get($name)
	{
		$getter = 'get' . strtoupper($name);
        	if (method_exists($this, $getter)) {
			return $this->$getter($name);
		} else {
			return $this->data[$name] ?? null;
		}
	}

	public function toArray():array
	{
		$return = [];
		foreach($this->data as $key => $item){
			if($item instanceof Base){
				$return[$key] = $item->toArray();
			}else{
				$return[$key] = $item;
			}
		}
		return $return;
	}
}

class User extends Base{

	public function setPesel(string $pesel)
	{
		$this->data['pesel'] = new Pesel($pesel);
	}

	public function getPesel()
	{
		return $this->data['pesel'];
	}
}

class Pesel extends Base{

	public function __construct(String $pesel){
		$this->data['pesel'] = $pesel;
	}
}

$user = new User();
$user->name = 'Piotr';
$user->pesel = '123';

var_dump(json_encode($user->toArray()));
?>
