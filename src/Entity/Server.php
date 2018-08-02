<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:05
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;


class Server
{
    /**
     * @var string
     *
     * @Groups({"MeetingServer", "Server"})
     */
    private $slide_uri;

    /**
     * @var string
     *
     * @Groups({"MeetingServer", "Server"})
     */
    private $thumbnail_uri;

    /**
     * Server constructor.
     * @param string $slide_uri
     * @param string $thumbnail_uri
     */
    public function __construct(string $slide_uri, string $thumbnail_uri)
    {
        $this->slide_uri = $slide_uri;
        $this->thumbnail_uri = $thumbnail_uri;
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