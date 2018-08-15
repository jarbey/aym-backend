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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeetingRepository")
 */
class Meeting {

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="text")
     *
     * @Groups({"Meeting"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingInfo"})
     */
    private $title;

    /**
     * @var Slide
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Slide")
     * @ORM\JoinColumn(name="current_slide_id", referencedColumnName="id")
     *
     * @Groups({"MeetingInfo"})
     */
    private $current_slide;

    /**
     * @var Server
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     *
     * @Groups({"MeetingServer"})
     */
    private $server;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="meetings")
     * @ORM\JoinTable(name="meetings_users")
     *
     * @Groups({"MeetingUser"})
     */
    private $users = [];

    /**
     * @var Slide[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Slide", inversedBy="meetings")
     * @ORM\JoinTable(name="meetings_slides")
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Meeting
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
     * @param User $new_user
     * @return bool
     */
    public function addUser(User $new_user) {
        foreach ($this->users as $user) {
            if ($user->getId() == $new_user->getId()) {
                return false;
            }
        }
        $this->users[] = $new_user;
        return true;
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
     * @param Slide $slide
     * @return $this
     */
    public function addSlide(Slide $slide) {
        if (!$this->slides) {
            $this->slides = [];
        }

        $this->slides[] = $slide;

        if ($this->current_slide == null) {
            $this->setCurrentSlide($slide);
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