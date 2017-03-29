<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Form\EventFormType;
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

        // Separate events into day buckets.
        if ($events) {
            foreach ($events as $event) {
                $displayOnDays = $event->getDayOfWeek();
                foreach ($displayOnDays as $day) {
                    $displayEvents[$day][] = [
                        'id' => $event->getId(),
                        'title' => $event->getTitle(),
                        'start' => $event->getEventStartTime(),
                        'end' => $event->getEventEndTime(),
                    ];
                }
            }
        }

        // Generate dates.
        $calendar = [];
        // Todays day numner.
        $date = new \DateTime;
        $day = $date->format('w');
        // If not already Sunday.
        if ($day > 0) {
            // Sunday.
            $date->modify('-'.$day.' days');
            $calendar['sunday'] = $date->format('D j');
            $date->modify('next monday');
            $calendar['monday'] = $date->format('D j');
            $date->modify('next tuesday');
            $calendar['tuesday'] = $date->format('D j');
            $date->modify('next wednesday');
            $calendar['wednesday'] = $date->format('D j');
            $date->modify('next thursday');
            $calendar['thursday'] = $date->format('D j');
            $date->modify('next friday');
            $calendar['friday'] = $date->format('D j');
            $date->modify('next saturday');
            $calendar['saturday'] = $date->format('D j');
        }
        else {
            // Sunday, start of week.
            $calendar['sunday'] = $date->format('D j');
            $date->modify('next monday');
            $calendar['monday'] = $date->format('D j');
            $date->modify('next tuesday');
            $calendar['tuesday'] = $date->format('D j');
            $date->modify('next wednesday');
            $calendar['wednesday'] = $date->format('D j');
            $date->modify('next thursday');
            $calendar['thursday'] = $date->format('D j');
            $date->modify('next friday');
            $calendar['friday'] = $date->format('D j');
            $date->modify('next saturday');
            $calendar['saturday'] = $date->format('D j');
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
     * @Route("/event/{id}", name="event_detail")
     */
    public function detailAction(Request $request, Event $event)
    {
        return $this->render('events/detail.html.twig', [
            'event' => $event,
        ]);
    }
}
