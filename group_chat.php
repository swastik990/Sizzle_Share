<?php
include('includes/header.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

    <title>Group Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container1 {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        #chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            max-width: 70%;
        }
        .message.user {
            background-color: #d1e7dd;
            align-self: flex-start;
        }
        .message.other {
            background-color: #f8d7da;
            align-self: flex-end;
        }
        .message .username {
            font-weight: bold;
            margin-right: 8px;
        }
        #message-input {
            display: flex;
            padding: 10px;
            background-color: #f9f9f9;
        }
        #message-input input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }
        #message-input button {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        #message-input button:hover {
            background-color: #0056b3;
        }
    </style>
    <div class="container1 mb-3">
        <div id="chat-box"></div>
        <div id="message-input">
            <input type="text" id="message" placeholder="Type your message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const messageInput = document.getElementById('message');

        // Get the user_id and first_name from PHP session
        const userId = <?php echo $_SESSION['user_id']; ?>;
        const firstName = "<?php echo $_SESSION['first_name']; ?>";

        // Connect to the WebSocket server
        const ws = new WebSocket('ws://localhost:8080');

        ws.onopen = () => {
            console.log('Connected to the chat server');
            // Send the user_id to the server
            ws.send(JSON.stringify({ user_id: userId }));
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', data.username === firstName ? 'user' : 'other');
            messageDiv.innerHTML = `<span class="username">${data.username}</span>: ${data.message}`;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
        };

        ws.onerror = (error) => {
            console.error('WebSocket error:', error);
        };

        ws.onclose = () => {
            console.log('Disconnected from the chat server');
        };

        function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                const data = {
                    user_id: userId,
                    message: message
                };
                ws.send(JSON.stringify(data)); // Send the message to the server
                messageInput.value = ''; // Clear the input field
            }
        }

        // Allow sending messages by pressing Enter
        messageInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
    <?php include('includes/footer.php'); ?>
</body>
</html>