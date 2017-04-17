<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventModification;
use AppBundle\Form\EventFormType;
use AppBundle\Form\ReservationFormType;
use AppBundle\Form\ReservationManageFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{

    /**
     * @Route("/schedule/{timestamp}", name="schedule", defaults={"timestamp" = "today"})
     */
    public function indexAction(Request $request, $timestamp)
    {

        if ($timestamp == "today") {
            $timestamp = time();
        }

        // Build Calendar display.
        $calendarUtil = $this->get('app.util.schedule_calendar_utils');
        $calendarUtil->configure($timestamp);

        $displayEvents = $calendarUtil->getEventsDisplay('week');
        $calendar = $calendarUtil->getCurrentWeekCalendar();
        $current_date = $calendarUtil->getCurrentWeekDateObject()->format('U');
        $previous_sunday = $calendarUtil->getPreviousSundayTimestamp();
        $next_sunday = $calendarUtil->getNextSundayTimestamp();

        return $this->render('events/schedule.html.twig', [
            'displayEvents' => $displayEvents,
            'calendar' => $calendar['calendar'],
            'current_date' => $current_date,
            'previous_sunday' => $previous_sunday,
            'next_sunday' => $next_sunday,
            'month_year' => $calendar['month_year']
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(EventFormType::class);

        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $entity \AppBundle\Entity\Event */
            $entity = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Event "%s" has been created.', $entity->getTitle())
            );

            return $this->redirectToRoute('schedule');
        }

        return $this->render('events/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{id}/edit/{timestamp}", name="event_edit")
     */
    public function editAction(Request $request, Event $event, $timestamp)
    {
        $em = $this->getDoctrine()->getManager();

        // Change Event Start and End Time to dateTime objects
        $event->setEventStartTimeForm();
        $event->setEventEndTimeForm();

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /* @var $updatedEntity \AppBundle\Entity\Event */
            $updatedEntity = $form->getData();

            // Check the value of editEventOptions to determine if we are update,
            // or creating a new Override.
            $editOptionChoice = $form->get('editEventOptions')->getData();

            if ($editOptionChoice == 'all_events') {
                $em->persist($updatedEntity);
                $em->flush();

                $this->addFlash(
                    'success',
                    sprintf('Event "%s" has been updated.', $updatedEntity->getTitle())
                );
            }
            else {
                // Create new event override.
                $eventModification = new EventModification();
                $eventModification->setEventId($updatedEntity);
                $eventModification->setEventStartTime($updatedEntity->getEventStartTime());
                $eventModification->setEventEndTime($updatedEntity->getEventEndTime());
                $eventModification->setModificationDate($timestamp);
                $eventModification->setPrimaryInstructor($updatedEntity->getPrimaryInstructor());
                $em->persist($eventModification);
                $em->flush();

                $date = new \DateTime();
                $date->setTimestamp($timestamp);
                $dateDisplay = $date->format('F jS, Y');

                $this->addFlash(
                    'success',
                    sprintf('Event "%s" has been modified for %s.', $updatedEntity->getTitle(), $dateDisplay)
                );
            }

            return $this->redirectToRoute('schedule');
        }

        return $this->render('events/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{id}/{timestamp}", name="event_detail")
     */
    public function detailAction(Request $request, Event $event, $timestamp)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $modRepo = $em->getRepository('AppBundle:EventModification');
        $mod = $modRepo->findModification($event, $timestamp);

        // Get reservations.
        $reservationsUtil = $this->get('app.util.reservations_utils');
        $reservations = $reservationsUtil->getEventReservations($event);


        return $this->render('events/detail.html.twig', [
            'event' => $event,
            'timestamp' => is_numeric($timestamp) ? $timestamp : null,
            'modification' => $mod,
            'reservations' => $reservations
        ]);
    }

    /**
     * @Route("/event/{id}/reserve/{timestamp}", name="event_reservation")
     */
    public function reserveAction(Request $request, Event $event, $timestamp)
    {
        $em = $this->getDoctrine()->getManager();

        $account = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(ReservationFormType::class, null, [
            'account' => $account,
            'event' => $event,
            'date' => $timestamp
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $reservationEntity \AppBundle\Entity\Reservation */
            $reservationEntity = $form->getData();

            $em->persist($reservationEntity);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Reservation created.')
            );

            return $this->redirectToRoute('event_detail', ['id' => $event, 'timestamp' => $timestamp]);
        }

        return $this->render('events/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax/checkin/{id}/{timestamp}", name="event_checkin")
     */
    public function checkInAction(Request $request, Event $event, $timestamp)
    {

    }
}
