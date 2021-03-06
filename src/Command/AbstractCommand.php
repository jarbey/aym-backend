<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 16:50
 */

namespace App\Command;


use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
abstract class AbstractCommand extends Command {
    /** @var LoggerInterface */
    private $logger;

    /**
     * AbstractManager constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger() {
        return $this->logger;
    }
}