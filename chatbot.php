<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .chat-header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
        }
        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
        }
        .bot-message {
            background: #e9ecef;
            color: #212529;
        }
        .chat-input {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }
        .typing-indicator {
            display: none;
            padding: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <h3>Customer Support Chatbot</h3>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">
                    Hello! How can I help you today?
                </div>
            </div>
            <div class="typing-indicator" id="typingIndicator">
                Bot is typing...
            </div>
            <div class="chat-input">
                <form id="chatForm" class="d-flex">
                    <input type="text" id="userInput" class="form-control me-2" placeholder="Type your message...">
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#chatForm').on('submit', function(e) {
                e.preventDefault();
                const userInput = $('#userInput').val();
                if (userInput.trim() === '') return;

                // Add user message to chat
                addMessage(userInput, 'user');
                $('#userInput').val('');

                // Show typing indicator
                $('#typingIndicator').show();

                // Send message to backend
                $.ajax({
                    url: 'process_chat.php',
                    method: 'POST',
                    data: { message: userInput },
                    success: function(response) {
                        $('#typingIndicator').hide();
                        addMessage(response, 'bot');
                    },
                    error: function() {
                        $('#typingIndicator').hide();
                        addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                    }
                });
            });

            function addMessage(message, sender) {
                const messageDiv = $('<div>').addClass('message').addClass(sender + '-message').text(message);
                $('#chatMessages').append(messageDiv);
                $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
            }
        });
    </script>
</body>
</html> 