<?php

namespace CustomerBundle\Service;

class Speaker
{
    private $registry;

    public function __construct(NameRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function sayMyName()
    {
        return $this->registry->getRandomName();
    }
}
