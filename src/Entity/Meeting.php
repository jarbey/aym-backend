<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:04
 */

namespace App\Entity;

use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\Annotation\Groups;

class Meeting {

    /**
     * @var string
     *
     * @Groups({"Meeting"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"MeetingInfo"})
     */
    private $titre;

    /**
     * @var Slide
     *
     * @Groups({"MeetingInfo"})
     */
    private $current_slide;

    /**
     * @var Server
     *
     * @Groups({"MeetingServer"})
     */
    private $server;

    /**
     * @var User[]
     *
     * @Groups({"MeetingUser"})
     */
    private $users;

    /**
     * @var Slide[]
     *
     * @Groups({"MeetingSlide"})
     */
    private $slides;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Meeting
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return Meeting
     */
    public function setTitre(string $titre)
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param Server $server
     * @return Meeting
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     * @return Meeting
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user) {
        // TODO : Control existing members
        $this->users[] = $user;
    }

    /**
     * @return Slide[]
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @param Slide[] $slides
     * @return Meeting
     */
    public function setSlides(array $slides)
    {
        $this->slides = $slides;

        // If no current slide defined, we set the first one
        if (($this->current_slide == null) && (count($this->slides) > 0)) {
            $this->setCurrentSlide($this->slides[0]);
        }
        return $this;
    }

    /**
     * @param $id
     * @return Slide
     * @throws EntityNotFoundException
     */
    public function getSlide($id) {
        foreach ($this->getSlides() as $slide) {
            if ($slide->getId() == $id) {
                return $slide;
            }
        }

        throw new EntityNotFoundException('Slide with id : ' . $id . ' doest not exits !');
    }

    /**
     * @return Slide
     */
    public function getCurrentSlide()
    {
        return $this->current_slide;
    }

    /**
     * @param Slide $current_slide
     * @return Meeting
     */
    public function setCurrentSlide(Slide $current_slide)
    {
        $this->current_slide = $current_slide;
        return $this;
    }

}