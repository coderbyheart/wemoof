<?php

namespace WEMOOF\WebBundle\Model;

use WEMOOF\BackendBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class EditProfileModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     * @Assert\Url
     */
    public $url;

    /**
     * @var string
     * @Assert\Regex("/@[a-zA-Z0-9_]{1,15}/")
     */
    public $twitter;

    /**
     * @var boolean
     * @Assert\Type(type="boolean")
     */
    public $public = false;

    /**
     * @var boolean
     * @Assert\Type(type="boolean")
     */
    public $hasGravatar = false;

    /**
     * @var string
     */
    private $description;

    /**
     * @param User $user
     * @return EditProfileModel
     */
    public static function factory(User $user)
    {
        $model              = new self();
        $model->id          = $user->getId();
        $model->firstname   = $user->getFirstname();
        $model->lastname    = $user->getLastname();
        $model->title       = $user->getTitle();
        $model->url         = $user->getUrl();
        $model->twitter     = $user->getTwitter();
        $model->public      = $user->isPublic();
        $model->hasGravatar = $user->hasGravatar();
        $model->setDescription($user->getDescription());
        return $model;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = filter_var($lastname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = filter_var($firstname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
