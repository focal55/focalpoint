<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Form\EventFormType;
use AppBundle\Form\ReservationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{

    /**
     * @Route("/schedule", name="schedule")
     */
    public function indexAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('AppBundle:Event');
        $events = $repo->findUpcoming();
        $displayEvents = ['sun' => [], 'mon' => [], 'tue' => [], 'wed' => [], 'thur' => [], 'fri' => [], 'sat' => []];

        // Generate dates.
        $calendar = [];
        // Today's day numner.
        $date = new \DateTime;
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
        }

        // Separate events into day buckets.
        if ($events) {
            // For each event.
            foreach ($events as $event) {
                // Get the days this event is offered.
                $displayOnDays = $event->getDayOfWeek();
                foreach ($displayOnDays as $day) {
                    dump($calendar[strtolower($day)]['date']);
                    $displayEvents[$day][] = [
                        'id' => $event->getId(),
                        'title' => $event->getTitle(),
                        'start' => $event->getEventStartTime(),
                        'end' => $event->getEventEndTime(),
                        'timestamp' => strtotime($calendar[strtolower($day)]['date'] . ' ' . $event->getEventStartTime())
                    ];
                }
            }
        }

        return $this->render('events/schedule.html.twig', [
            'displayEvents' => $displayEvents,
            'calendar' => $calendar
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
     * @Route("/event/{id}/edit", name="event_edit")
     */
    public function editAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();

        // Change Event Start and End Time to dateTime objects
        $event->setEventStartTimeForm();
        $event->setEventEndTimeForm();

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $updateEntity \AppBundle\Entity\Event */
            $updatedEntity = $form->getData();

            $em->persist($updatedEntity);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Event "%s" has been updated.', $updatedEntity->getTitle())
            );

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
        return $this->render('events/detail.html.twig', [
            'event' => $event,
            'timestamp' => is_numeric($timestamp) ? $timestamp : null,
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
