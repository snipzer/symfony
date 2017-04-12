<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Feature
 */
class Feature
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     **/
    public function setProducts($products)
    {
        $this->products = $products;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Feature
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}

