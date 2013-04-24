<?php

namespace WEMOOF\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="registration", uniqueConstraints={@ORM\UniqueConstraint(name="event_user",columns={"event_id", "user_id"})})
 */
class Registration
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
     * @var Event
     */
    protected $user;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;
}