<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventModification;
use AppBundle\Form\EventFormType;
use AppBundle\Form\ReservationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{

    /**
     * @Route("/schedule/{timestamp}", name="schedule")
     */
    public function indexAction(Request $request, $timestamp = null) {

        $current_date = NULL;
        $next_sunday = NULL;
        $previous_sunday = NULL;

        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('AppBundle:Event');
        $events = $repo->findUpcoming($timestamp);

        $displayEvents = ['sun' => [], 'mon' => [], 'tue' => [], 'wed' => [], 'thur' => [], 'fri' => [], 'sat' => []];

        // Generate dates.
        $calendar = [];
        // Today's day number.
        $date = new \DateTime;
        $current_date = $date->format('U');

        if ($timestamp) {
            $date->setTimestamp($timestamp);
        }

        $day = $date->format('w');
        // If not already Sunday.
        if ($day > 0) {
            // Sunday.
            $date->modify('-'.$day.' days');
            $calendar['sun']['display'] = $date->format('D j');
            $calendar['sun']['date'] = $date->format('n/j/Y');

            $date->modify('next monday');
            $calendar['mon']['display'] = $date->format('D j');
            $calendar['mon']['date'] = $date->format('n/j/Y');

            $date->modify('next tuesday');
            $calendar['tue']['display'] = $date->format('D j');
            $calendar['tue']['date'] = $date->format('n/j/Y');

            $date->modify('next wednesday');
            $calendar['wed']['display'] = $date->format('D j');
            $calendar['wed']['date'] = $date->format('n/j/Y');

            $date->modify('next thursday');
            $calendar['thur']['display'] = $date->format('D j');
            $calendar['thur']['date'] = $date->format('n/j/Y');

            $date->modify('next friday');
            $calendar['fri']['display'] = $date->format('D j');
            $calendar['fri']['date'] = $date->format('n/j/Y');

            $date->modify('next saturday');
            $calendar['sat']['display'] = $date->format('D j');
            $calendar['sat']['date'] = $date->format('n/j/Y');
            $month_year = $date->format('F Y');
        }
        else {
            // Sunday, start of week.
            $calendar['sun']['display'] = $date->format('D j');
            $calendar['sun']['date'] = $date->format('n/j/Y');

            $date->modify('next monday');
            $calendar['mon']['display'] = $date->format('D j');
            $calendar['mon']['date'] = $date->format('n/j/Y');

            $date->modify('next tuesday');
            $calendar['tue']['display'] = $date->format('D j');
            $calendar['tue']['date'] = $date->format('n/j/Y');

            $date->modify('next wednesday');
            $calendar['wed']['display'] = $date->format('D j');
            $calendar['wed']['date'] = $date->format('n/j/Y');

            $date->modify('next thursday');
            $calendar['thur']['display'] = $date->format('D j');
            $calendar['thur']['date'] = $date->format('n/j/Y');

            $date->modify('next friday');
            $calendar['fri']['display'] = $date->format('D j');
            $calendar['fri']['date'] = $date->format('n/j/Y');

            $date->modify('next saturday');
            $calendar['sat']['display'] = $date->format('D j');
            $calendar['sat']['date'] = $date->format('n/j/Y');
            $month_year = $date->format('F Y');
        }

        // Build Time Picker.
        $weekPicker = new \DateTime();
        if ($timestamp) {
            $weekPicker->setTimestamp($timestamp);
        }
        else {
            $weekPicker->setTimestamp($current_date);
        }
        $weekPicker->modify('last sunday');
        $previous_sunday = $weekPicker->format('U');
        $weekPicker->modify('next sunday')->modify('next sunday');
        $next_sunday = $weekPicker->format('U');

        // Separate events into day buckets.
        if ($events) {
            // Load Event Modification Repo.
            $modRepo = $em->getRepository('AppBundle:EventModification');
            // For each event.
            foreach ($events as $event) {
                // Get the days this event is offered.
                $displayOnDays = $event->getDayOfWeek();
                foreach ($displayOnDays as $day) {
                    // Find Modifications
                    // Build a timestamp for this event.
                    $timestamp = strtotime($calendar[strtolower($day)]['date'] . ' ' . $event->getEventStartTime());
                    $mod = $modRepo->findModification($event, $timestamp);

                    $displayEvents[$day][] = [
                        'id' => $event->getId(),
                        'title' => $event->getTitle(),
                        'start' => $event->getEventStartTime(),
                        'end' => $event->getEventEndTime(),
                        'timestamp' => $timestamp,
                        'mod' => $mod
                    ];
                }
            }
        }

        return $this->render('events/schedule.html.twig', [
            'displayEvents' => $displayEvents,
            'calendar' => $calendar,
            'current_date' => $current_date,
            'previous_sunday' => $previous_sunday,
            'next_sunday' => $next_sunday,
            'month_year' => $month_year
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

        return $this->render('events/detail.html.twig', [
            'event' => $event,
            'timestamp' => is_numeric($timestamp) ? $timestamp : null,
            'modification' => $mod
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
}
