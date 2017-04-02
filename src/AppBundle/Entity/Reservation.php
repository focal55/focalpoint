<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 * @ORM\Table(name="reservation")
 */
class Reservation
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(nullable=false)
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinTable(name="users")
     */
    protected $attendee;

    /**
     * @ORM\Column(nullable=false)
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Event")
     * @ORM\JoinTable(name="events")
     */
    protected $event;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $eventDate;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $checkedIn;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $paid;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $assignment;

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
    public function getAttendee() {
        return $this->attendee;
    }

    /**
     * @param mixed $attendee
     */
    public function setAttendee($attendee) {
        $this->attendee = $attendee;
    }

    /**
     * @return mixed
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event) {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getEventDate() {
        return $this->eventDate;
    }

    /**
     * @param mixed $eventDate
     */
    public function setEventDate($eventDate) {
        $this->eventDate = $eventDate;
    }

    /**
     * @return mixed
     */
    public function getCheckedIn() {
        return $this->checkedIn;
    }

    /**
     * @param mixed $checkedIn
     */
    public function setCheckedIn($checkedIn) {
        $this->checkedIn = $checkedIn;
    }

    /**
     * @return mixed
     */
    public function getPaid() {
        return $this->paid;
    }

    /**
     * @param mixed $paid
     */
    public function setPaid($paid) {
        $this->paid = $paid;
    }

    /**
     * @return mixed
     */
    public function getAssignment() {
        return $this->assignment;
    }

    /**
     * @param mixed $assignment
     */
    public function setAssignment($assignment) {
        $this->assignment = $assignment;
    }
}


