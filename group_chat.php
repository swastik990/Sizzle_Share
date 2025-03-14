    <title>Group Chat</title>
    <style>
        #chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .message {
            margin-bottom: 10px;
        }
        .message .username {
            font-weight: bold;
            color: #007bff;
        }
        .message .timestamp {
            font-size: 0.8em;
            color: #6c757d;
        }
    </style>

    <?php
    include('includes/header.php');

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<div class='container mt-5'>";
        echo "<p class='text-center text-danger'>You must be logged in to access the group chat.</p>";
        echo "</div>";
        exit;
    }

    // Load chat history from chat_logs.json
    $chatLogFile = __DIR__ . '/chat_logs.json';
    $chatHistory = [];
    if (file_exists($chatLogFile)) {
        $chatHistory = json_decode(file_get_contents($chatLogFile), true) ?: [];
    }
    ?>

    <div class="container my-5">
        <h1 class="mb-4 text-center fw-bold">Group Chat</h1>

        <!-- Chat Box -->
        <div id="chat-box" class="mb-4">
            <?php foreach ($chatHistory as $message): ?>
                <div class="message">
                    <span class="username"><?php echo htmlspecialchars($message['user']); ?>:</span>
                    <span><?php echo htmlspecialchars($message['message']); ?></span>
                    <small class="timestamp text-muted float-end">(<?php echo htmlspecialchars($message['timestamp']); ?>)</small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Message Input -->
        <div class="input-group">
            <input type="text" id="message-input" class="form-control" placeholder="Type a message..." aria-label="Message" aria-describedby="send-button">
            <button id="send-button" class="btn btn-primary" type="button">Send</button>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- JavaScript for WebSocket Communication -->
    <script>
        // Get the current user's name
        const currentUser = '<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>';

        // Establish WebSocket connection
        const socket = new WebSocket('ws://localhost:9000');

socket.onmessage = function(event) {
    console.log("Message received:", event.data); // Debugging

    const data = JSON.parse(event.data);
    appendMessage(data.user, data.message);
};

// Send message function
document.getElementById('send-button').addEventListener('click', function() {
    sendMessage();
});

document.getElementById('message-input').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        sendMessage();
        event.preventDefault();
    }
});

// Function to send messages
function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();

    if (message) {
        const messageData = {
            user: currentUser,
            message: message
        };

        // Send message to WebSocket server
        socket.send(JSON.stringify(messageData));

        // Immediately append message to chat without waiting for WebSocket
        appendMessage(currentUser, message);

        // Clear input field
        messageInput.value = '';
    }
}

// Function to append message to chat box
function appendMessage(user, message) {
    const chatBox = document.getElementById('chat-box');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message';
    messageDiv.innerHTML = `
        <span class="username">${user}:</span> 
        <span>${message}</span> 
        <small class="timestamp text-muted float-end">(${new Date().toLocaleTimeString()})</small>
    `;

    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
}

    </script>
    </body>
    </html>