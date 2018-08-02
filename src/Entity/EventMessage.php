<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 16:29
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;

class EventMessage
{
    /**
     * @var string
     * @Groups({"Event"})
     */
    private $event;

    /**
     * @var Meeting
     * @Groups({"Meeting"})
     */
    private $meeting;

    /**
     * @var User
     * @Groups({"User"})
     */
    private $user;

    /**
     * EventMessage constructor.
     * @param string $event
     */
    public function __construct(string $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     * @return EventMessage
     */
    public function setEvent(string $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param Meeting $meeting
     * @return EventMessage
     */
    public function setMeeting(Meeting $meeting)
    {
        $this->meeting = $meeting;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return EventMessage
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }


}