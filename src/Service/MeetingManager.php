<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 12:37
 */

namespace App\Service;

use App\Entity\Meeting;
use App\Entity\Slide;
use App\Entity\User;
use App\Repository\MeetingRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /** @var ServerRepository */
    private $server_repository;

    /** @var MeetingRepository */
    private $meeting_repository;

    /** @var EntityManagerInterface */
    private $entity_manager;

    /**
     * MeetingManager constructor.
     * @param LoggerInterface $logger
     * @param MeetingRepository $meeting_repository
     * @param ServerRepository $server_repository
     * @param EntityManagerInterface $em
     */
    public function __construct(LoggerInterface $logger, MeetingRepository $meeting_repository, ServerRepository $server_repository, EntityManagerInterface $em) {
        $this->logger = $logger;
        $this->meeting_repository = $meeting_repository;
        $this->server_repository = $server_repository;
        $this->entity_manager = $em;
        $this->process = new Process('');

        $this->converter_cmd = 'java -classpath ' . __DIR__ . '/../../bin/pptconverter.jar aym.PPTConverter'; // TODO : Replace with a real service call (through a queing system)
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
     * @return \App\Entity\Server
     */
    private function getServer() {
        $servers = $this->server_repository->findAll();
        return $servers[0];
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
        $meeting->setServer($this->getServer());

        $tmp_file = __DIR__ . '/../../var/' . $meeting->getId() . '.zip';

        $this->process->setCommandLine($this->converter_cmd . ' -i ' . $ppt->getPathname() . ' -o ' . $tmp_file);
        $this->process->run();

        // executes after the command finishes
        if (!$this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        // ZIP EXTRACT
        $za = new \ZipArchive();
        $za->open($tmp_file);
        $nb_slides = $za->numFiles;

        $dir = __DIR__ . '/../../public/slides/' . $meeting->getId();

        mkdir($dir);
        $za->extractTo($dir);
        $za->close();

        // RENAME SLIDES
        for ($i = 1 ; $i <= $nb_slides ; $i++) {
            $slide_id = uniqid();

            $slide = new Slide($slide_id, 'Slide ' . $i);
            rename($dir . '/' . $i . '.jpg', $dir . '/' . $slide_id . '.jpg');

            $meeting->addSlide($slide);
            $this->entity_manager->persist($slide);
        }

        $this->entity_manager->persist($meeting);
        $this->entity_manager->flush();

        unlink($tmp_file);
    }

}