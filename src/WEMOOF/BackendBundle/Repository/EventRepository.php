<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use \PhpOption\Some;
use \PhpOption\None;

class EventRepository extends DoctrineEntityRepository implements EventRepositoryInterface
{
    /**
     * @return \PhpOption\Option
     */
    public function getNextEvent()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.start >= :now');
        $qb->setParameter('now', time());
        $qb->setMaxResults(1);
        $qb->orderBy('e.start', 'ASC');
        $event = $qb->getQuery()->getOneOrNullResult();
        return $event == null ? None::create() : new Some($event);
    }

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getEvent($id)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.id = :id')->setParameter('id', $id);
        $qb->leftJoin('e.registrations', 'er')->addSelect('er');
        $event = $qb->getQuery()->getOneOrNullResult();
        return $event === null ? None::create() : new Some($event);
    }
}