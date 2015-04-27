<?php

namespace WEMOOF\BackendBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository AS DoctrineEntityRepository;
use PhpOption\None;
use PhpOption\Some;
use WEMOOF\BackendBundle\Entity\Event;
use WEMOOF\BackendBundle\Entity\Talk;
use WEMOOF\BackendBundle\Entity\User;

class TalkRepository extends DoctrineEntityRepository implements TalkRepositoryInterface
{
    /**
     * @param Event $event
     * @return \PhpOption\Option
     */
    function getTalksForEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.event = :event')->setParameter('event', $event->getId());
        $qb->andWhere('t.role = :role')->setParameter('role', Talk::ROLE_TALK);
        $qb->orderBy('t.order', 'ASC');
        $qb->leftJoin('t.speaker', 'u')->addSelect('u');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Event $event
     * @return \PhpOption\Option
     */
    function getSpotlightsForEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.event = :event')->setParameter('event', $event->getId());
        $qb->andWhere('t.role = :role')->setParameter('role', Talk::ROLE_SPOTLIGHT);
        $qb->orderBy('t.order', 'ASC');
        $qb->leftJoin('t.speaker', 'u')->addSelect('u');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return \PhpOption\Option
     */
    function getTalksForUser(User $user)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.speaker = :user')->setParameter('user', $user->getId());
        $qb->orderBy('t.created', 'ASC');
        $qb->leftJoin('t.event', 'e')->addSelect('e');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return \PhpOption\Option
     */
    function getTalk($id)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.id = :id')->setParameter('id', $id);
        $qb->leftJoin('t.event', 'e')->addSelect('e');
        $talk = $qb->getQuery()->getOneOrNullResult();
        return $talk === null ? None::create() : new Some($talk);
    }

    /**
     * @return Collection
     */
    function getTalks()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->orderBy('t.event', 'ASC');
        $qb->leftJoin('t.speaker', 'u')->addSelect('u');
        $qb->leftJoin('t.event', 'e')->addSelect('e');
        return $qb->getQuery()->getResult();
    }
}
