<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 12:27
 */

namespace App\Server;

use App\Entity\EventMessage;
use App\Entity\Meeting;
use App\Entity\User;
use App\Service\MeetingManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketComponent implements MessageComponentInterface {
    const REQUEST_JOIN = 'REQUEST_JOIN';
    const JOIN = 'JOIN';
    const INFO_MEETING = 'INFO_MEETING';

    const LEAVE = 'LEAVE';

    const REQUEST_SLIDE = 'REQUEST_SLIDE';
    const SLIDE = 'SLIDE';

    /** @var \SplObjectStorage */
    private $clients;

    /** @var [] */
    private $users_connection;

    /** @var MeetingManager */
    private $meeting_manager;
    /** @var LoggerInterface */
    private $logger;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(LoggerInterface $logger, MeetingManager $meeting_manager, SerializerInterface $serializer) {
        $this->logger = $logger;
        $this->clients = new \SplObjectStorage();
        $this->meeting_manager = $meeting_manager;
        $this->serializer = $serializer;
        $this->logger->info('Create WebSocketComponent');
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->logger->debug('New connection');
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $closedConnection) {
        $this->logger->debug('Close connection');
        $this->clients->detach($closedConnection);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->logger->info('An error has occurred: ' . $e->getTraceAsString());
    }

    public function onMessage(ConnectionInterface $from, $message) {
        $this->logger->debug('Receive message: ' . $message);
        try {
            // Try to decode message
            $server_message = json_decode($message);
            if ($server_message != null) {
                $this->dispatchEvent($from, $server_message);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getTraceAsString());
            $from->send('{"error": "' . $e->getMessage() . '"}');
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param $message
     * @return null
     * @throws \Exception
     */
    private function dispatchEvent(ConnectionInterface $from, $message) {
        if (isset($message->event)) {
            switch ($message->event) {
                case self::REQUEST_JOIN:
                    $meeting = $this->getMeetingFromMessage($message);
                    $user = $this->getUserFromMessage($message);

                    $this->registerUser($meeting, $user, $from);

                    // SEND TO ALL EVENT_JOIN
                    $this->broadcastMeetingMessage($meeting, $this->getEventJoin($meeting, $user));

                    // SEND TO from EVENT_MEETING_INFO
                    $this->sendUserMessage($user, $this->getEventInfoMeeting($meeting));
                    break;
                case self::REQUEST_SLIDE:
                    $meeting = $this->getMeetingFromMessage($message);
                    $meeting->setCurrentSlide($meeting->getSlide($this->getCurrentSlideFromMessage($message)));

                    // SEND TO ALL EVENT_JOIN
                    $this->broadcastMeetingMessage($meeting, $this->getEventSlide($meeting));
                    break;
            }
        }
        return null;
    }

    /**
     * @param $message
     * @return \App\Entity\Meeting
     * @throws \Exception
     */
    private function getMeetingFromMessage($message) {
        if (isset($message->meeting)) {
            if (isset($message->meeting->id)) {
                $id = $message->meeting->id;
                return $this->meeting_manager->getMeeting($id);
            }
        }
        throw new \Exception ('Invalid meeting definition');
    }

    /**
     * @param $message
     * @return string
     * @throws \Exception
     */
    private function getCurrentSlideFromMessage($message) {
        if (isset($message->meeting) && isset($message->meeting->current_slide) && isset($message->meeting->current_slide->id)) {
            return $message->meeting->current_slide->id;
        }
        throw new \Exception ('Invalid slide definition');
    }

    /**
     * @param $message
     * @return User
     * @throws \Exception
     */
    private function getUserFromMessage($message) {
        if (isset($message->user)) {
            $user = new User();
            $user->setId($message->user->id);
            $user->setAvatar($message->user->avatar);
            $user->setName($message->user->name);
            $user->setType($message->user->type);

            return $user;
        } else {
            throw new \Exception ('Invalid user definition');
        }
    }

    /**
     * @param Meeting $meeting
     * @param User $user
     * @return string
     */
    private function getEventJoin(Meeting $meeting, User $user) {
        $event_message = new EventMessage(self::JOIN);
        $event_message->setMeeting($meeting);
        $event_message->setUser($user);

        return $this->getMessage($event_message, ['Event', 'Meeting', 'User']);
    }

    /**
     * @param Meeting $meeting
     * @param User $user
     * @return string
     */
    private function getEventInfoMeeting(Meeting $meeting) {
        $event_message = new EventMessage(self::INFO_MEETING);
        $event_message->setMeeting($meeting);

        return $this->getMessage($event_message, ['Event', 'Meeting', 'MeetingInfo', 'MeetingServer', 'MeetingUser', 'MeetingSlide']);
    }

    /**
     * @param Meeting $meeting
     * @return string
     */
    private function getEventSlide(Meeting $meeting) {
        $event_message = new EventMessage(self::SLIDE);
        $event_message->setMeeting($meeting);

        return $this->getMessage($event_message, ['Event', 'Meeting', 'MeetingInfo', 'Slide']);
    }

    /**
     * @param EventMessage $event_message
     * @param array $contexts
     * @return string
     */
    private function getMessage(EventMessage $event_message, $contexts = []) {
        $broadcast_messaged = $this->serializer->serialize($event_message, 'json', SerializationContext::create()->setGroups($contexts));
        $this->logger->info('Message to send : ' . $broadcast_messaged);
        return $broadcast_messaged;
    }

    /**
     * @param Meeting $meeting
     * @param string $message
     * @throws \Exception
     */
    private function broadcastMeetingMessage(Meeting $meeting, $message) {
        // Send message to all meeting users
        foreach ($meeting->getUsers() as $user) {
            $this->sendUserMessage($user, $message);
        }
    }

    /**
     * @param User $user
     * @param string $message
     * @throws \Exception
     */
    private function sendUserMessage(User $user, $message) {
        if ($message != null) {
            $this->getUserConnection($user)->send($message);
        }
    }

    /**
     * @param Meeting $meeting
     * @param User $user
     * @param ConnectionInterface $connection
     */
    private function registerUser(Meeting $meeting, User $user, ConnectionInterface $connection) {
        $this->meeting_manager->registerUser($meeting, $user);
        // Register connection
        $this->users_connection[$user->getId()] = $connection;
    }

    /**
     * @param User $user
     * @return ConnectionInterface
     * @throws \Exception
     */
    private function getUserConnection(User $user) {
        if (array_key_exists($user->getId(), $this->users_connection)) {
            return $this->users_connection[$user->getId()];
        }

        throw new \Exception('Connection does not exists for User : ' . $user->getId());
    }
}