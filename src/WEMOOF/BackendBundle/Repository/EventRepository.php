<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use \PhpOption\Some;
use \PhpOption\None;
use WEMOOF\BackendBundle\Entity\Event;

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

    /**
     * @return Event
     */
    function getRegisterableEvents()
    {
        // SELECT ON REGISTRATIONS
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.ticketSalesStart <= :now');
        $qb->andWhere('e.start >= :now');
        $qb->andWhere('e.numTickets > 0');
        $qb->leftJoin('e.registrations', 'er')->addSelect($qb->expr()->count('er.id'));
        $qb->andHaving('num_registrations < e.numTickets');
        $qb->setParameter('now', new \DateTime());
        $result = $qb->getQuery()->getResult();
        foreach($result as $r) {

            Debug::dump($r);
        }
        return $result;
    }

}