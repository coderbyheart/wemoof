<?php

namespace WEMOOF\WebBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="`user`", uniqueConstraints={@ORM\UniqueConstraint(name="email",columns={"email"})})
 * @AssertORM\UniqueEntity(fields={"email"})
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string")
     * @var string E-Mail-Adresse
     */
    public $email;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    public $created;
}