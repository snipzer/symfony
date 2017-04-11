<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $pseudo;

    /**
     * @var \DateTime
     */
    private $inscriptionDate;

    /**
     * @var int
     */
    private $numberOfComment;

    /**
     * @var bool
     */
    private $isAdmin;

    private $password;

    public function __construct()
    {
        $this->inscriptionDate = new \DateTime();
        $this->numberOfComment = 0;
        $this->isAdmin = false;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
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
     * Set pseudo
     *
     * @param string $pseudo
     * @return User
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string 
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set inscriptionDate
     *
     * @param \DateTime $inscriptionDate
     * @return User
     */
    public function setInscriptionDate($inscriptionDate)
    {
        $this->inscriptionDate = $inscriptionDate;

        return $this;
    }

    /**
     * Get inscriptionDate
     *
     * @return \DateTime 
     */
    public function getInscriptionDate()
    {
        return $this->inscriptionDate;
    }

    /**
     * Set numberOfComment
     *
     * @param integer $numberOfComment
     * @return User
     */
    public function setNumberOfComment($numberOfComment)
    {
        $this->numberOfComment = $numberOfComment;

        return $this;
    }

    /**
     * Get numberOfComment
     *
     * @return integer 
     */
    public function getNumberOfComment()
    {
        return $this->numberOfComment;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
}
