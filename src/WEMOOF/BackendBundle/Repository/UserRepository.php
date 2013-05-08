<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use PhpOption\None;
use PhpOption\Some;
use WEMOOF\BackendBundle\Entity\Event;
use WEMOOF\BackendBundle\Entity\User;

class UserRepository extends DoctrineEntityRepository implements UserRepositoryInterface
{
    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getUser($id)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere('u.id = :id')->setParameter('id', $id);
        $user = $qb->getQuery()->getOneOrNullResult();
        return $user === null ? None::create() : new Some($user);
    }

    /**
     * @return User[]
     */
    function getUsersWithoutPublicProfile()
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.public = 0')
            ->andWhere('u.verified = 1');
        return $qb->getQuery()->getResult();
    }
}