<?php

abstract class Base implements JsonSerializable
{
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);

        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }

        $this->$name = $value;
    }

    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->$getter($name);
        }

        return $this->$name ?? null;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        $return = [];

        foreach (get_object_vars($this) as $key => $item) {
            if ($item instanceof Base) {
                $return[$key] = $item->jsonSerialize();
                continue;
            }

            $return[$key] = $item;
        }
        return $return;
    }
}

/**
 * @property string firstname
 * @property string lastname
 * @property string pesel
 */
class User extends Base
{
    public function setPesel(string $pesel): void
    {
        $this->pesel = new Pesel($pesel);
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }
}

class Pesel implements JsonSerializable
{
    /**
     * @var string
     */
    private string $pesel;

    public function __construct(string $pesel)
    {
        $this->pesel = $pesel;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->pesel;
    }
}

$user = new User();
$user->firstname = 'Piotr';
$user->lastname = 'Bączek';
$user->pesel = '123';

var_dump($user, json_encode($user, JSON_PRETTY_PRINT));

