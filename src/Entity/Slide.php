<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:08
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SlideRepository")
 */
class Slide
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingSlide", "Slide"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingSlide", "Slide"})
     */
    private $title;

    /**
     * @var Meeting[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Meeting", inversedBy="slides")
     */
    private $meetings;

    /**
     * Slide constructor.
     * @param string $id
     * @param string $title
     */
    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Slide
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
     * @return Slide
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Meeting[]
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * @param Meeting[] $meetings
     * @return Slide
     */
    public function setMeetings(array $meetings)
    {
        $this->meetings = $meetings;
        return $this;
    }

}