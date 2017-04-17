<?php

namespace AppBundle\Util;


use Symfony\Component\DependencyInjection\ContainerInterface;

class ScheduleCalendarUtils {

    /**
     * @var null
     */
    private $timestamp = null;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var array
     */
    private $events;

    /**
     * @var \Doctrine\ORM\EntityManager|object
     */
    private $em;

    /**
     * @var \DateTime
     */
    private $previous_sunday;

    /**
     * @var \DateTime
     */
    private $next_sunday;

    /**
     * ScheduleCalendarUtils constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        // Fetch Events.
        $this->em = $container->get('doctrine.orm.entity_manager');
        $repo = $this->em->getRepository('AppBundle:Event');
        /* @var $repo \AppBundle\Repository\EventRepository */
        $this->events = $repo->findUpcoming();
    }

    /**
     * Configure the date object with a timestamp.
     * @param $timestamp
     */
    public function configure($timestamp) {
        $this->date = new \DateTime;
        $this->timestamp = $timestamp;

        // If a timestamp value is not null, use it to create the date object.
        if ($this->timestamp) {
            $this->date->setTimestamp($this->timestamp);
            $modDate = new \DateTime;
            $modDate->setTimestamp($this->timestamp);
        }
        else {
            $this->date->setTimestamp(time());
            $modDate = new \DateTime;
        }

        $day = $this->date->format('w');
        // If not already Sunday.
        if ($day > 0) {
            $this->previous_sunday = $modDate->modify('previous sunday')->modify('previous sunday')->format('U');
            $this->next_sunday = $modDate->modify('next sunday')->modify('next sunday')->format('U');
        }
        else {
            $this->previous_sunday = $modDate->modify('previous sunday')->format('U');
            $this->next_sunday = $modDate->modify('next sunday')->modify('next sunday')->format('U');
        }


    }

    public function getDateObject() {
        return $this->date;
    }

    public function getCurrentWeekDateObject() {
        return new \DateTime;
    }

    public function getNextSundayTimestamp() {
        return $this->next_sunday;
    }

    public function getPreviousSundayTimestamp() {
        return $this->previous_sunday;
    }

    public function getDaysOfTheWeek() {
        return ['sun' => [], 'mon' => [], 'tue' => [], 'wed' => [], 'thur' => [], 'fri' => [], 'sat' => []];
    }

    public function getCurrentWeekCalendar() {
        // Today's day of the week number.
        $day = $this->date->format('w');
        $date = $this->date;
        $calendar = null;
        $month_year = null;

        // Generate dates.
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

        return [
            'calendar' => $calendar,
            'month_year' => $month_year
        ];
    }

    /**
     * Build Display array of Events.
     * @param string $displayType
     * @return array
     */
    public function getEventsDisplay($displayType = 'week') {
        $displayEvents = [];
        $calendar = $this->getCurrentWeekCalendar();
        $calendar = $calendar['calendar'];
        // Separate events into day buckets.
        if ($this->events) {
            // Load Event Modification Repo.
            $modRepo = $this->em->getRepository('AppBundle:EventModification');
            // For each event.
            foreach ($this->events as $event) {
                // Get the days this event is offered.
                /* @var $event \AppBundle\Entity\Event */
                $displayOnDays = $event->getDayOfWeek();
                foreach ($displayOnDays as $day) {
                    // Find Modifications
                    // Build a timestamp for this event.
                    $timestamp = strtotime($calendar[strtolower($day)]['date'] . ' ' . $event->getEventStartTime());
                    /* @var $modRepo \AppBundle\Repository\EventModificationRepository */
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
        return $displayEvents;
    }
}
