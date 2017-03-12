<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
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
        $this->eventStartTime = $eventStartTime;
    }

    /**
     * @return mixed
     */
    public function getEventEndTime() {
        return $this->eventEndTime;
    }

    /**
     * @param mixed $eventEndTime
     */
    public function setEventEndTime($eventEndTime) {
        $this->eventEndTime = $eventEndTime;
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

}

