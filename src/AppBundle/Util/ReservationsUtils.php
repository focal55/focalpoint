<?php

namespace AppBundle\Util;


use AppBundle\Entity\Event;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReservationsUtils
{

    private $em;

    /**
     * ReservationsDisplayUtils constructor.
     */
    public function __construct(ContainerInterface $container) {
        // Fetch Events.
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function getEventReservations(Event $event) {
        $repo = $this->em->getRepository('AppBundle:Reservation');
        /* @var $repo \AppBundle\Repository\ReservationRepository */
        return $repo->findEventReservations($event);
    }
}
