<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 12:37
 */

namespace App\Service;

use App\Entity\Meeting;
use App\Entity\User;
use App\Entity\Server;
use App\Entity\Slide;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MeetingManager {
    /** @var LoggerInterface */
    private $logger;

    /** @var Meeting */
    private $meeting;

    /**
     * AbstractManager constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;

        $this->meeting = new Meeting();
        $this->meeting->setId('1AF');
        $this->meeting->setTitre('Best presentation ever !');

        $this->meeting->setServer(new Server('https://dummyimage.com/600x400/000/fff.jpg&text={slide}', 'https://dummyimage.com/600x400/000/fff.jpg&text={slide}'));

        $this->meeting->setSlides([
            new Slide('1', 'First page'),
            new Slide('2', 'Page 2'),
            new Slide('3', 'Page 3'),
            new Slide('4', 'Page 4'),
            new Slide('5', 'Page 5'),
            new Slide('6', 'Page 6'),
            new Slide('7', 'Page 7'),
            new Slide('8', 'Page 8'),
            new Slide('9', 'Page 9'),
            new Slide('10', 'Last page'),
        ]);
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger() {
        return $this->logger;
    }

    /**
     * @param $id
     * @return Meeting
     */
    public function getMeeting($id) {
        if ($this->meeting->getId() == $id) {
            return $this->meeting;
        }

        throw new ResourceNotFoundException('Meeting ' . $id . ' does not exists !');
    }

    /**
     * @param Meeting $meeting
     * @param User $user
     */
    public function registerUser(Meeting $meeting, User $user) {
        $meeting->addUser($user);
    }


}