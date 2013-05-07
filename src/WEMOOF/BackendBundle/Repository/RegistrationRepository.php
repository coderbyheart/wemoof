<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use \PhpOption\Some;
use \PhpOption\None;
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

}
