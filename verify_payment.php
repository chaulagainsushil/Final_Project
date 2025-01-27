<?php
require 'db_config.php';

// Get input data from the frontend
$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'];
$amount = $input['amount'];

$secret_key = "your_test_secret_key_here";  // Replace with test secret key from Khalti

$url = "https://khalti.com/api/v2/payment/verify/";

$data = http_build_query([
    'token' => $token,
    'amount' => $amount
]);

$headers = [
    "Authorization: Key $secret_key",
    "Content-Type: application/x-www-form-urlencoded"
];

$options = [
    'http' => [
        'header' => implode("\r\n", $headers),
        'method' => 'POST',
        'content' => $data
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response) {
    $responseData = json_decode($response, true);

    if (isset($responseData['idx']) && $responseData['state']['name'] === "Completed") {
        $transactionId = $responseData['idx'];
        $status = $responseData['state']['name'];
        $paymentMethod = $responseData['type']['name'];
        $paidBy = $responseData['user']['name'];

        // Insert payment details into database
        $stmt = $conn->prepare("INSERT INTO payments (transaction_id, token, amount, status, payment_method, paid_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$transactionId, $token, $amount, $status, $paymentMethod, $paidBy]);

        echo json_encode(["success" => true, "message" => "Payment verified and stored in database."]);
    } else {
        echo json_encode(["success" => false, "message" => "Payment verification failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid response from Khalti API."]);
}
?>