<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 16:50
 */

namespace App\Command;

use App\Service\MeetingManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

class PPTConverterCommand extends AbstractCommand {

    /** @var MeetingManager */
    private $meeting_manager;

    /**
     * WebSocketServerCommand constructor.
     * @param MeetingManager $meeting_manager
     */
    public function __construct(LoggerInterface $logger, MeetingManager $meeting_manager) {
        parent::__construct($logger);
        $this->meeting_manager = $meeting_manager;
    }

    protected function configure() {
        $this
            ->setName('aym:pptconverter')
            ->setDescription('Create a meeting from ppt');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->meeting_manager->createMeetingFromPPT(new File(__DIR__ . '../../bin/test.ppt'), 'Test JAY');
    }
}