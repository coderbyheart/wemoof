<?php

namespace WEMOOF\BackendBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;


/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email",columns={"email"})})
 * @AssertORM\UniqueEntity(fields={"email"}, groups={"signup", "profile"})
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @Assert\NotBlank(groups={"signup", "profile"})
     * @Assert\Email(groups={"signup", "profile"})
     * @ORM\Column(type="string")
     * @var string E-Mail-Adresse
     */
    protected $email;

    /**
     * @Assert\NotBlank(groups={"profile"})
     * @ORM\Column(type="string", nullable=true)
     * @var string Vorname
     */
    protected $firstname;

    /**
     * @Assert\NotBlank(groups={"profile"})
     * @ORM\Column(type="string", nullable=true)
     * @var string Nachname
     */
    protected $lastname;

    /**
     * @Assert\Length(max=500)
     * @ORM\Column(type="text", nullable=true)
     * @var string Beschreibung
     */
    protected $description;

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
     * @var string Gravatar verwenden?
     */
    protected $hasGravatar = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->firstname, $this->lastname);
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
     * @return string
     */
    public function getHasGravatar()
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
}