<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventModificationRepository")
 * @ORM\Table(name="eventmodifications")
 */
class EventModification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="event_id", nullable=false)
     * @ORM\OneToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $eventId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $eventStartTime;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $eventEndTime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $modificationDate;

    /**
     * @ORM\Column(nullable=false)
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinTable(name="users")
     */
    protected $primaryInstructor;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEventId() {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     */
    public function setEventId($eventId) {
        $this->eventId = $eventId;
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
     * @return mixed
     */
    public function getModificationDate() {
        return $this->modificationDate;
    }

    /**
     * @param mixed $modificationDate
     */
    public function setModificationDate($modificationDate) {
        $this->modificationDate = $modificationDate;
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
        return "$this->id";
    }
}

