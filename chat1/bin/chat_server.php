<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $logFile;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        // Define the log file path
        $this->logFile = __DIR__ . '/../chat_logs.json';
    }

    public function onOpen(ConnectionInterface $conn) {
        // Add the new connection to the list of clients
        $this->clients->attach($conn);

        // Assign a default username (you can replace this with dynamic usernames later)
        $conn->username = "User" . $conn->resourceId;

        // Send chat history to the newly connected client
        if (file_exists($this->logFile)) {
            $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
            foreach ($logs as $log) {
                $conn->send(json_encode($log));
            }
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Parse the incoming message (assume it's JSON with "message" and optionally "username")
        $data = json_decode($msg, true);
        $message = $data['message'] ?? '';
        $username = $data['username'] ?? $from->username;

        // Log the message to the JSON file
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'username' => $username,
            'message' => $message
        ];

        // Read existing logs
        $logs = [];
        if (file_exists($this->logFile)) {
            $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
        }

        // Append the new log entry
        $logs[] = $logEntry;

        // Save the updated logs back to the file
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));

        // Broadcast the message to all connected clients
        $broadcastData = [
            'username' => $username,
            'message' => $message
        ];
        foreach ($this->clients as $client) {
            $client->send(json_encode($broadcastData));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection from the list of clients
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Set up the WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080 // Port number
);

echo "WebSocket server started on port 8080\n";

// Run the server
$server->run();