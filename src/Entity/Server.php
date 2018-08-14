<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:05
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServerRepository")
 */
class Server
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingServer", "Server"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingServer", "Server"})
     */
    private $slide_uri;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"MeetingServer", "Server"})
     */
    private $thumbnail_uri;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Server
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlideUri()
    {
        return $this->slide_uri;
    }

    /**
     * @param string $slide_uri
     * @return Server
     */
    public function setSlideUri(string $slide_uri)
    {
        $this->slide_uri = $slide_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailUri()
    {
        return $this->thumbnail_uri;
    }

    /**
     * @param string $thumbnail_uri
     * @return Server
     */
    public function setThumbnailUri(string $thumbnail_uri)
    {
        $this->thumbnail_uri = $thumbnail_uri;
        return $this;
    }

}