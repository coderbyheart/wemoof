<?php

namespace WEMOOF\BackendBundle\Entity;


use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PhpOption\Option;
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
     * @ORM\Column(type="carbon")
     * @var Carbon Beginnt am
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

    /**
     * @ORM\Column(type="carbon_optional", nullable=true, name="ticket_sales_start")
     * @var Option
     */
    protected $ticketSalesStart;

    /**
     * @ORM\Column(type="integer", name="num_tickets")
     * @var int
     */
    protected $numTickets = 0;

    /**
     * @ORM\OneToMany(targetEntity="WEMOOF\BackendBundle\Entity\Registration", mappedBy="event")
     * @var Registration[]
     */
    protected $registrations;

    public function __construct()
    {
        $this->start = new \DateTime();
        $this->registrations = new ArrayCollection();
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

    public function getNumTickets()
    {
        return $this->numTickets;
    }

    /**
     * @return \DateTime
     */
    public function getTicketSalesStart()
    {
        return $this->ticketSalesStart;
    }

    /**
     * @return int
     */
    public function getNumTicketsAvailable()
    {
        return $this->getNumTickets() - $this->registrations->count();
    }

    /**
     * @return float
     */
    public function getPercentTicketsSold()
    {
        $percent = $this->getNumTickets() > 0 ? $this->registrations->count() / $this->getNumTickets() : 1;
        return sprintf("%d", $percent * 100);
    }

    /**
     * @return Registration[]
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }
}
