<?php

namespace WEMOOF\BackendBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LiteCQRS\Plugin\CRUD\AggregateResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;
use WEMOOF\BackendBundle\Value\MarkdownTextValue;
use WEMOOF\BackendBundle\Value\NameValue;
use WEMOOF\BackendBundle\Value\TwitterHandleValue;
use WEMOOF\BackendBundle\Value\URLValue;


/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email",columns={"email"})})
 * @AssertORM\UniqueEntity(fields={"email"})
 */
class User extends AggregateResource
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string")
     * @var string E-Mail-Adresse
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string Vorname
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string Nachname
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max=128)
     * @var string Title
     */
    protected $title;

    /**
     * @Assert\Length(max=500)
     * @ORM\Column(type="text", nullable=true)
     * @var string Beschreibung
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true, name="login_key")
     * @var string Login-Key
     */
    protected $loginKey;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string URL
     */
    protected $url;

    /**
     * @Assert\Regex(pattern="^[a-zA-Z0-9_]{1,15}$")
     * @ORM\Column(nullable=true)
     * @var string Twitter
     */
    protected $twitter;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean", name="has_gravatar")
     * @var boolean Gravatar verwenden?
     */
    protected $hasGravatar = false;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     * @var boolean Öffentliches Profil?
     */
    protected $public = false;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     * @var string E-Mail-Adresse verifiziert?
     */
    protected $verified = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="WEMOOF\BackendBundle\Entity\Registration", mappedBy="event")
     * @var Registration[]
     */
    protected $registrations;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $name = trim(sprintf("%s %s", $this->firstname, $this->lastname));
        return empty($name) ? $this->email : $name;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function hasGravatar()
    {
        return $this->hasGravatar;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param string $hasGravatar
     */
    public function setHasGravatar($hasGravatar)
    {
        $this->hasGravatar = $hasGravatar;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $loginKey
     */
    public function setLoginKey($loginKey)
    {
        $this->loginKey = $loginKey;
    }

    /**
     * @return string
     */
    public function getLoginKey()
    {
        return $this->loginKey;
    }

    /**
     * @param string $verified
     */
    public function verify()
    {
        $this->verified = true;
    }

    /**
     * @return string
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * Make profile public
     */
    public function publish()
    {
        $this->public = true;
    }

    /**
     * Make profile private
     */
    public function unpublish()
    {
        $this->public = false;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Return an array of properties that are allowed to change
     * through the create() and update() methods.
     *
     * @return array
     */
    protected function getAccessibleProperties()
    {
        return array(
            'email',
            'firstname',
            'lastname',
            'title',
            'description',
            'twitter',
            'url',
            'hasGravatar',
            'public',
            'loginKey',
            'verified',
        );
    }
}
