<!-- chat_server.php -->
<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../vendor/autoload.php'; // Adjust path if necessary

// Chat class to handle WebSocket connections
class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    // Called when a new client connects
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";

        // Send chat history to the newly connected client
        $chatLog = $this->loadChatHistory();
        foreach ($chatLog as $message) {
            $conn->send(json_encode($message));
        }
    }

    // Called when a message is received from a client
    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        // Broadcast the message to all connected clients
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send(json_encode([
                    'user' => $data['user'],
                    'message' => $data['message'],
                    'timestamp' => $data['timestamp'] // Include the timestamp
                ]));
            }
        }

        // Save the message to chat_logs.json
        $this->saveMessageToFile($data['user'], $data['message'], $data['timestamp']);
    }

    // Called when a client disconnects
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    // Called when an error occurs
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    // Save a message to chat_logs.json
    private function saveMessageToFile($user, $message, $timestamp) {
        $logFile = __DIR__ . '/../chat_logs.json';

        // Load existing chat logs
        $chatLog = [];
        if (file_exists($logFile)) {
            $chatLog = json_decode(file_get_contents($logFile), true) ?: [];
        }

        // Add the new message with a timestamp
        $chatLog[] = [
            'user' => $user,
            'message' => $message,
            'timestamp' => $timestamp
        ];

        // Save the updated chat log
        file_put_contents($logFile, json_encode($chatLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // Load chat history from chat_logs.json
    private function loadChatHistory() {
        $logFile = __DIR__ . '/../chat_logs.json';
        return file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    }
}

// Run the WebSocket server using IoServer, HttpServer, and WsServer
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    9000 // Port number
);

echo "WebSocket server started on ws://localhost:9000\n";
$server->run();