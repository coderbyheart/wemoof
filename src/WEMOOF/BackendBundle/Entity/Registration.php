<?php

namespace WEMOOF\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use LiteCQRS\Plugin\CRUD\AggregateResource;

/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\RegistrationRepository")
 * @ORM\Table(name="registration", uniqueConstraints={@ORM\UniqueConstraint(name="event_user",columns={"event_id", "user_id"})})
 */
class Registration extends AggregateResource
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
     * @ORM\ManyToOne(targetEntity="WEMOOF\BackendBundle\Entity\Event", inversedBy="registrations")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     * @var Event
     */
    protected $event;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="WEMOOF\BackendBundle\Entity\User", inversedBy="registrations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var User
     */
    protected $user;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @return \WEMOOF\BackendBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \WEMOOF\BackendBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
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
            'event',
            'user',
            'created'
        );
    }
}
