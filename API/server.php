<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\WebSocket\MyWebSocket;

require dirname(__DIR__) . '/vendor/autoload.php';

class MyWebSocket implements Ratchet\WebSocket\MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nouvelle connexion\n";
    }

    public function onMessage(Ratchet\ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send($msg);
            }
        }
    }

    public function onClose(Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(Ratchet\ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocket()
        )
    ),
    8080
);

echo "Serveur WebSocket démarré sur le port 8080\n";
$server->run();