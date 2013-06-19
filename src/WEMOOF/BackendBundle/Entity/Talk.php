<?php

namespace WEMOOF\BackendBundle\Entity;

use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Event;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;


/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\TalkRepository")
 * @ORM\Table(name="talk")
 */
class Talk
{
    const ROLE_TALK = 1;

    const ROLE_SPOTLIGHT = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @var string Name des Talks
     */
    protected $name;

    /**
     * @Assert\Length(max=500)
     * @ORM\Column(type="text", nullable=true)
     * @var string Beschreibung
     */
    protected $description;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="WEMOOF\BackendBundle\Entity\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * @var Event
     */
    protected $event;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1,max=2)
     * @ORM\Column(type="integer")
     * @var int Type of talk
     */
    protected $role = self::ROLE_TALK;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="WEMOOF\BackendBundle\Entity\User")
     * @ORM\JoinColumn(name="speaker_id", referencedColumnName="id")
     * @var User
     */
    protected $speaker;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string URL
     */
    protected $url;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string Youtube-URL
     */
    protected $youtube;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $order = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct()
    {
        $this->speaker = new User();
        $this->event   = new Event();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s: %s", $this->speaker, $this->name);
    }

    /**
     * @return \WEMOOF\BackendBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return \WEMOOF\BackendBundle\Entity\User
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isSpotlight()
    {
        return $this->getRole() === self::ROLE_SPOTLIGHT;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param string $youtube
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    }
}