<?php
session_start();
// Include the Razorpay PHP library
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Initialize Razorpay with your key and secret
$api_key = 'rzp_test_gJrqTsDY71zAi9';
$api_secret = 'xxLp9VcU1zWIZSy7dYhKQ4Ts';

$api = new Api($api_key, $api_secret);
// Ensure amount is an integer (Razorpay requires amount in paise as integer)
$amount = (int) $_SESSION['tp']*100; // Convert rupees to paise (1 rupee = 100 paise)
// Create an order
$order = $api->order->create([
    'amount' => $amount, // amount in paise (100 paise = 1 rupee)
    'currency' => 'INR',
    'receipt' => 'order_receipt_12asa3'
]);
// Get the order ID
$order_id = $order->id;

// Set your callback URL
$callback_url = "../index.php?success=1";

// Include Razorpay Checkout.js library
echo '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';

                        

// Add a script to handle the payment
echo '<script>
    function startPayment() {
        var options = {
            key: "' . $api_key . '",
            amount: ' . $order->amount . ',
            currency: "' . $order->currency . '",
            name: "Your Company Name",
            description: "Shopping ",
            image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
            order_id: "' . $order_id . '",
            theme:
            {
                "color": "#738276"
            },
            callback_url: "' . $callback_url . '"
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>';

echo '<script>
window.onload = function() {
    startPayment();
};
</script>';
?>
