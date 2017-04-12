<?php

namespace ShopBundle\Entity;

use Behat\Transliterator\Transliterator;
use Doctrine\ORM\Event\PreUpdateEventArgs;


/**
 * Category
 */
class Category
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
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $slug;

    private $seo;

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
     * @return Category
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
     * @return Category
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Category
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
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
     * @see src/ShopBundle/Resources/config/doctrine/Category.orm.yml
     */
    public function onPrePersist()
    {
        $this->updateSlug();
    }

    /**
     * Pre update event callback.
     *
     * Called by doctrine: see lifecycleCallbacks in the mapping file.
     * @see src/ShopBundle/Resources/config/doctrine/Category.orm.yml
     *
     * @param PreUpdateEventArgs $event
     */
    public function onPreUpdate(PreUpdateEventArgs $event)
    {
        // The PreUpdateEventArgs allow us to track if some properties has been changed
        if ($event->hasChangedField('title')) {
            $this->updateSlug();
        }
    }

    /**
     * Updates the slug from the title.
     */
    private function updateSlug()
    {
        // Turns 'This is a great category' into 'this-is-a-great-category'
        $slug = Transliterator::urlize($this->getTitle());

        $this->setSlug($slug);
    }
}

