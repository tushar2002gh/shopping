<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

// Define common responses
$responses = [
    'greeting' => [
        'patterns' => ['hello', 'hi', 'hey', 'greetings'],
        'responses' => [
            'Hello! How can I help you today?',
            'Hi there! What can I do for you?',
            'Greetings! How may I assist you?'
        ]
    ],
    'order_status' => [
        'patterns' => ['order status', 'track order', 'where is my order', 'order tracking'],
        'responses' => [
            'To check your order status, please visit your account dashboard or provide your order number.',
            'I can help you track your order. Could you please share your order number?',
            'You can track your order by logging into your account and visiting the orders section.'
        ],
        'db_query' => true
    ],
    'account_info' => [
        'patterns' => ['my account', 'account details', 'profile information', 'my profile'],
        'responses' => [
            'I can help you with your account information. Please provide your email address.',
            'To access your account details, please share your registered email.',
            'I can show you your account information. What email address did you use to register?'
        ],
        'db_query' => true
    ],
    'shipping' => [
        'patterns' => ['shipping', 'delivery', 'when will i receive', 'shipping time'],
        'responses' => [
            'Our standard shipping takes 3-5 business days. Express shipping is available for 1-2 day delivery.',
            'Shipping times vary by location. Most orders are delivered within 3-5 business days.',
            'We offer both standard and express shipping options. Would you like to know more about our shipping policies?'
        ]
    ],
    'returns' => [
        'patterns' => ['return', 'refund', 'exchange', 'send back'],
        'responses' => [
            'We have a 30-day return policy. You can initiate a return through your account dashboard.',
            'To process a return, please visit our returns page or contact our support team.',
            'We accept returns within 30 days of delivery. Would you like to know more about our return process?'
        ]
    ],
    'payment' => [
        'patterns' => ['payment', 'pay', 'credit card', 'payment method'],
        'responses' => [
            'We accept all major credit cards, PayPal, and other popular payment methods.',
            'You can pay using credit/debit cards, PayPal, or other available payment options at checkout.',
            'Our payment system is secure and supports multiple payment methods. Which payment method would you prefer?'
        ]
    ],
    'fallback' => [
        'responses' => [
            "I'm not sure I understand. Could you please rephrase your question?",
            "I'm still learning. Could you try asking that in a different way?",
            "I don't have information about that yet. Would you like to speak with a human representative?"
        ]
    ]
];

// Get user message
$userMessage = strtolower(trim($_POST['message'] ?? ''));

// Function to get customer information
function getCustomerInfo($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return null;
    }
}

// Function to get order information
function getOrderInfo($orderNumber) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
        $stmt->execute([$orderNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return null;
    }
}

// Function to find matching response
function findResponse($message, $responses) {
    global $pdo;
    
    foreach ($responses as $category => $data) {
        if ($category === 'fallback') continue;
        
        foreach ($data['patterns'] as $pattern) {
            if (strpos($message, $pattern) !== false) {
                $response = $data['responses'][array_rand($data['responses'])];
                
                // Handle database queries if needed
                if (isset($data['db_query']) && $data['db_query']) {
                    // Extract email or order number from message
                    if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $message, $email)) {
                        $customerInfo = getCustomerInfo($email[0]);
                        if ($customerInfo) {
                            $response .= "\n\nYour account information:\n";
                            $response .= "Name: " . $customerInfo['name'] . "\n";
                            $response .= "Email: " . $customerInfo['email'] . "\n";
                            $response .= "Phone: " . $customerInfo['phone'] . "\n";
                        }
                    }
                    
                    if (preg_match('/order[#\s]+([A-Z0-9]+)/i', $message, $order)) {
                        $orderInfo = getOrderInfo($order[1]);
                        if ($orderInfo) {
                            $response .= "\n\nOrder information:\n";
                            $response .= "Order Number: " . $orderInfo['order_number'] . "\n";
                            $response .= "Status: " . $orderInfo['status'] . "\n";
                            $response .= "Total: $" . $orderInfo['total'] . "\n";
                        }
                    }
                }
                
                return $response;
            }
        }
    }
    
    // If no match found, return fallback response
    return $responses['fallback']['responses'][array_rand($responses['fallback']['responses'])];
}

// Get response
$response = findResponse($userMessage, $responses);

// Return response
echo json_encode($response);
?> 