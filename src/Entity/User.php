<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 13:07
 */

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;

class User
{
    /**
     * @var string
     *
     * @Groups({"MeetingUser", "User"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"MeetingUser", "User"})
     */
    private $type;

    /**
     * @var string
     *
     * @Groups({"MeetingUser", "User"})
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"MeetingUser", "User"})
     */
    private $avatar;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return User
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return User
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }


}