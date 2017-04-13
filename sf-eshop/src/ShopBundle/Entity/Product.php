<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Behat\Transliterator\Transliterator;
use Doctrine\ORM\Event\PreUpdateEventArgs;


/**
 * Product
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $price;

    /**
     * @var int
     */
    private $stock;

    /**
     * @var \DateTime
     */
    private $releasedAt;

    /**
     * @var string
     */
    private $slug;

    private $category;

    private $seo;

    private $features;

    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param mixed $features
     **/
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * @return mixed
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * @param mixed $seo
     **/
    public function setSeo(Seo $seo)
    {
        $this->seo = $seo;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     **/
    public function setCategory(Category $category)
    {
        $this->category = $category;
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
     * @return Product
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

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set releasedAt
     *
     * @param \DateTime $releasedAt
     *
     * @return Product
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    /**
     * Get releasedAt
     *
     * @return \DateTime
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Pre persist (create only) event callback.
     *
     * Called by doctrine: see lifecycleCallbacks in the mapping file.
     * @see src/ShopBundle/Resources/config/doctrine/Product.orm.yml
     */
    public function onPrePersist()
    {
        $this->updateSlug();
    }

    /**
     * Pre update event callback.
     *
     * Called by doctrine: see lifecycleCallbacks in the mapping file.
     * @see src/ShopBundle/Resources/config/doctrine/Product.orm.yml
     *
     * @param PreUpdateEventArgs $event
     */
    public function onPreUpdate(PreUpdateEventArgs $event)
    {
        // The PreUpdateEventArgs allow us to track if some properties has been changed
        if ($event->hasChangedField('title'))
        {
            $this->updateSlug();
        }
    }

    /**
     * Updates the slug from the title.
     */
    private function updateSlug()
    {
        // Turns 'This is a great product' into 'this-is-a-great-product'
        $slug = Transliterator::urlize($this->getTitle());

        $this->setSlug($slug);
    }
}

