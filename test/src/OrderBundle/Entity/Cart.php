<?php

namespace OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cart
 */
class Cart
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $status;

    /**
     * @var ArrayCollection
     */
    private $lines;

    /**
     * @var Customer
     */
    private $customer;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     **/
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return $lines
     */
    public function getLines()
    {
        return $this->lines;
    }


    public function addLines(Line $line)
    {
        // On ajoute la ligne au panier
        $this->lines->add($line);
        // Et le panier à la ligne
        $line->setCart($this);


        return $this;
    }

    /**
     * @param ArrayCollection $line
     * @return Cart
     **/
    public function setLines($lines)
    {
        $this->lines = $lines;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Cart
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Cart
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Cart
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
