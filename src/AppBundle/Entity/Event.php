<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(name="events")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="json_array", nullable=false)
     */
    protected $dayOfWeek;

    /**
     * @ORM\Column(type="string")
     */
    protected $eventStartTime;

    /**
     * @ORM\Column(type="string")
     */
    protected $eventEndTime;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $registrationType;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"active"})
     */
    protected $status;

    /**
     * @ORM\Column(nullable=false)
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinTable(name="users")
     */
    protected $primaryInstructor;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDayOfWeek() {
        return $this->dayOfWeek;
    }

    /**
     * @param mixed $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek) {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return mixed
     */
    public function getEventStartTime() {
        return $this->eventStartTime;
    }

    /**
     * @param mixed $eventStartTime
     */
    public function setEventStartTime($eventStartTime) {
        // If value is a date object, convert to string.
        if (is_object($eventStartTime)) {
            $this->eventStartTime = $eventStartTime->format('g:ia');
        }
        else {
            $this->eventStartTime = $eventStartTime;
        }
    }

    /**
     * Change to dateTime object so symfony can show the date widget.
     * @return object $eventStartTime
     */
    public function setEventStartTimeForm() {
        $eventStartTimeForm = new \DateTime('1-1-2010 ' . $this->eventStartTime);
        $this->eventStartTime = $eventStartTimeForm;
    }

    /**
     * @return mixed
     */
    public function getEventEndTime() {
        return $this->eventEndTime;
    }

    /**
     * @param mixed $eventEndTime
     * @ORM\PrePersist
     */
    public function setEventEndTime($eventEndTime) {
        // If value is a date object, convert to string.
        if (is_object($eventEndTime)) {
            $this->eventEndTime = $eventEndTime->format('g:ia');
        }
        else {
            $this->eventEndTime = $eventEndTime;
        }
    }

    /**
     * Change to dateTime object so symfony can show the date widget.
     * @return object $eventStartTime
     */
    public function setEventEndTimeForm() {
        $eventEndTimeForm = new \DateTime('1-1-2010 ' . $this->eventEndTime);
        $this->eventEndTime = $eventEndTimeForm;
    }

    /**
     * @return mixed
     */
    public function getRegistrationType() {
        return $this->registrationType;
    }

    /**
     * @param mixed $registrationType
     */
    public function setRegistrationType($registrationType) {
        $this->registrationType = $registrationType;
    }

    /**
     * @return mixed
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPrimaryInstructor() {
        return $this->primaryInstructor;
    }

    /**
     * @param mixed $primaryInstructor
     */
    public function setPrimaryInstructor($primaryInstructor) {
        $this->primaryInstructor = $primaryInstructor;
    }

    public function __toString() {
        return $this->title . ' ' . $this->id;
    }
}

