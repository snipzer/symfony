<?php
namespace BlogBundle\Service;


class Speaker
{
    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function sayMyName()
    {
        return $this->_name;
    }
}

?>