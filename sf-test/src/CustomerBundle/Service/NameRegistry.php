<?php

namespace CustomerBundle\Service;


class NameRegistry
{
    private $names;

    public function __construct()
    {
        $this->names = ['Heisenberg', 'Toto', 'Tata',];
    }

    public function getRandomName()
    {
        return $this->names[rand(0, count($this->names) - 1)];
    }
}







