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
use App\Repository\MeetingRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MeetingManager {
    /** @var LoggerInterface */
    private $logger;

    /** @var Meeting[] */
    private $meetings = [];

    /** @vaar MeetingRepository */
    private $meeting_repository;

    /**
     * MeetingManager constructor.
     * @param LoggerInterface $logger
     * @param MeetingRepository $meeting_repository
     */
    public function __construct(LoggerInterface $logger, MeetingRepository $meeting_repository) {
        $this->logger = $logger;
        $this->meeting_repository = $meeting_repository;
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
        if (array_key_exists($id, $this->meetings)) {
            // Cache fetch
            return $this->meetings[$id];
        } else {
            // DB load
            $meeting = $this->meeting_repository->find($id);
            if ($meeting) {
                $this->meetings[$id] = $meeting;
                return $meeting;
            }
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