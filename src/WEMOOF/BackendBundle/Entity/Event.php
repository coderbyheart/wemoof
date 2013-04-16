<?php

namespace WEMOOF\BackendBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;


/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 * @AssertORM\UniqueEntity(fields={"email"})
 */
class Event
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
     * @ORM\Column(type="string")
     * @var string Name der Veranstaltung
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     * @var string Beginnt am
     */
    protected $start;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string URL
     */
    protected $xing;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string URL
     */
    protected $facebook;

    /**
     * @Assert\Url
     * @ORM\Column(type="text", nullable=true)
     * @var string URL
     */
    protected $googleplus;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct()
    {
        $this->start = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }


    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $googleplus
     */
    public function setGoogleplus($googleplus)
    {
        $this->googleplus = $googleplus;
    }

    /**
     * @return string
     */
    public function getGoogleplus()
    {
        return $this->googleplus;
    }

    /**
     * @param string $xing
     */
    public function setXing($xing)
    {
        $this->xing = $xing;
    }

    /**
     * @return string
     */
    public function getXing()
    {
        return $this->xing;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s am %s", $this->name, strftime("%d.%m.%Y", $this->start->getTimestamp()));
    }
}