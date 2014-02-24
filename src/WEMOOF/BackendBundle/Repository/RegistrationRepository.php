<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use \PhpOption\Some;
use \PhpOption\None;
use WEMOOF\BackendBundle\Entity\Event;
use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Registration;

class RegistrationRepository extends DoctrineEntityRepository implements RegistrationRepositoryInterface
{
    /**
     * @param User $user
     * @return Registration[]
     */
    function getRegistrationsForUser(User $user)
    {
        $qb = $this->createQueryBuilder('er');
        $qb->select('er');
        $qb->andWhere('er.user = :user');
        $qb->setParameter('user', $user);
        $qb->leftJoin('er.event', 'e')->addSelect('e');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return Registration[]
     */
    function getCancelableRegistrationsForUser(User $user)
    {
        $qb = $this->createQueryBuilder('er');
        $qb->select('er');
        $qb->andWhere('er.user = :user');
        $qb->setParameter('user', $user);
        $qb->leftJoin('er.event', 'e')->addSelect('e');
        $qb->andWhere('e.start > :now')->setParameter('now', new \DateTime());
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Event $event
     * @return Registration[]
     */
    function getGuestsForEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('er');
        $qb->select('er');
        $qb->andWhere('er.event = :event')->setParameter('event', $event);
        $qb->andWhere('er.role = :role')->setParameter('role', Registration::ROLE_GUEST);
        $qb->leftJoin('er.user', 'u')->addSelect('u');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Event $event
     * @return Registration[]
     */
    function getRegistrationsForEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('er');
        $qb->select('er');
        $qb->andWhere('er.event = :event')->setParameter('event', $event);
        $qb->leftJoin('er.user', 'u')->addSelect('u');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getRegistration($id)
    {
        $qb = $this->createQueryBuilder('er');
        $qb->andWhere('er.id = :id')->setParameter('id', $id);
        $registration = $qb->getQuery()->getOneOrNullResult();
        return $registration === null ? None::create() : new Some($registration);
    }

    /**
     * @return Registration[]
     */
    function getUnconfirmedRegistrations()
    {
        $qb = $this->createQueryBuilder('er');
        $qb->andWhere('er.confirmed IS NULL');
        $qb->leftJoin('er.event', 'e')->addSelect('e');
        $qb->leftJoin('er.user', 'u')->addSelect('u');
        return $qb->getQuery()->getResult();
    }

    /**
     * @return Registration[]
     */
    function getMissingNameRegistrations()
    {
        $qb = $this->createQueryBuilder('er');
        $qb->leftJoin('er.event', 'e')->addSelect('e');
        $qb->leftJoin('er.user', 'u')->addSelect('u');
        $qb->andWhere('e.start > :now')->setParameter('now', new \DateTime());
        $qb->andWhere('u.firstname IS NULL AND u.lastname IS NULL');
        return $qb->getQuery()->getResult();
    }

}
