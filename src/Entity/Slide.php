<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:08
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;

class Slide
{
    /**
     * @var string
     *
     * @Groups({"MeetingSlide", "Slide"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"MeetingSlide", "Slide"})
     */
    private $title;

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


}