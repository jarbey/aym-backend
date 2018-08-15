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
use App\Repository\MeetingRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MeetingManager {

    /** @var string */
    private $converter_cmd;

    /** @var Process */
    private $process;

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
        $this->process = new Process('');

        $this->converter_cmd = 'java -classpath ' . __DIR__ . '../../bin/pptconverter.jar aym.PPTConverter'; // TODO : Replace with a real service call (through a queing system)
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

    /**
     * @param File $ppt
     * @param null $title
     * @throws \Exception
     */
    public function createMeetingFromPPT(File $ppt, $title = null) {
        if (!$ppt || !$ppt->isFile()) {
            throw new \Exception('ppt file is incorrect !');
        }

        $meeting = new Meeting();
        $meeting->setId(uniqid());
        $meeting->setTitle($title);

        $tmp_file = __DIR__ . '/../../var/' . $meeting->getId() . '.zip';

        $this->process->setCommandLine($this->converter_cmd . ' -i ' . $ppt->getPathname() . ' -o ' . $tmp_file);
        $this->process->run();

        // executes after the command finishes
        if (!$this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $za = new \ZipArchive();
        $za->open($tmp_file);

        for ($i = 0; $i < $za->numFiles; $i++) {
            $stat = $za->statIndex( $i );
            print_r( basename( $stat['name'] ) . PHP_EOL );
        }
    }

}