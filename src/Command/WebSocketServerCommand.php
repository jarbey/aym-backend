<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 02/08/2018
 * Time: 16:50
 */

namespace App\Command;


use App\Server\WebSocketComponent;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketServerCommand extends AbstractCommand {
    /** @var WebSocketComponent */
    private $web_socket_component;

    /**
     * WebSocketServerCommand constructor.
     * @param WebSocketComponent $web_socket_component
     */
    public function __construct(LoggerInterface $logger, WebSocketComponent $web_socket_component) {
        parent::__construct($logger);
        $this->web_socket_component = $web_socket_component;
    }

    protected function configure() {
        $this
            ->setName('aym:websocket:server')
            ->setDescription('Start websocket server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->getLogger()->info('Start WebSocket server');
        $server = IoServer::factory(
            new HttpServer(new WsServer($this->web_socket_component)),
            8081,
            '127.0.0.1'
        );
        $server->run();
    }
}