<?php

namespace WEMOOF\BackendBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;


/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email",columns={"email"})})
 * @AssertORM\UniqueEntity(fields={"email"})
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
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string")
     * @var string E-Mail-Adresse
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @var string Vorname
     */
    protected $firstname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @var string Nachname
     */
    protected $lastname;

    /**
     * @Assert\Length(max=500)
     * @ORM\Column(type="text")
     * @var string Beschreibung
     */
    protected $description;

    /**
     * @Assert\Url
     * @ORM\Column(type="text")
     * @var string URL
     */
    protected $url;

    /**
     * @Assert\Regex(pattern="^[a-zA-Z0-9_]{1,15}$")
     * @ORM\Column()
     * @var string Twitter
     */
    protected $twitter;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean", name="has_gravatar")
     * @var string Gravatar verwenden?
     */
    protected $hasGravatar;

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
}