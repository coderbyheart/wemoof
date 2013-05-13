<?php

namespace WEMOOF\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use LiteCQRS\Plugin\CRUD\AggregateResource;
use PhpOption\Option;

/**
 * @ORM\Entity(repositoryClass="WEMOOF\BackendBundle\Repository\RegistrationRepository")
 * @ORM\Table(name="registration", uniqueConstraints={@ORM\UniqueConstraint(name="event_user",columns={"event_id", "user_id"})})
 */
class Registration extends AggregateResource
{
    const ROLE_GUEST   = 1;
    const ROLE_SPEAKER = 2;
    const ROLE_TEAM    = 3;

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
     * @Assert\NotBlank()
     * @Assert\Range(min=1,max=3)
     * @ORM\Column(type="integer")
     * @var int Type of registration
     */
    protected $role = self::ROLE_GUEST;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(type="carbon_optional", nullable=true)
     * @var Option
     */
    protected $confirmed;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

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
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
            'role',
            'created',
            'confirmed',
        );
    }
}
