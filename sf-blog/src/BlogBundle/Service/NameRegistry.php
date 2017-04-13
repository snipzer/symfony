<?php
namespace BlogBundle\Service;


class NameRegistry
{
    private $names;

    public function __construct()
    {
        $this->names = [
            'Heisenberg',
            'Toto',
            'Tata',
            'Titi',
            'Tutu',
        ];
    }

    public function getRandomName()
    {
        return $this->names[rand(0, count($this->names) - 1 )];
    }
}

?>