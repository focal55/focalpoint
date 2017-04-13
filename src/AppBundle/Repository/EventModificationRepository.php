<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventModificationRepository extends EntityRepository
{
    public function findModification(Event $event, $timestamp)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->join('AppBundle:Event', 'e', 'WITH', 'e.id = :event')
            ->where('m.modificationDate = :timestamp')
            ->setParameters(['timestamp' => $timestamp, ':event' => $event->getId()]);
        return $qb->getQuery()->getResult();
    }
}