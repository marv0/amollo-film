<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: debug.php");
    exit;
}

require_once 'config.php'; // Include your config.php file that contains the database connection details

// Check if the session variables exist
if (isset($_SESSION['transactionReference']) && isset($_SESSION['orderId'])) {
    $transactionReference = $_SESSION['transactionReference'];
    $orderId = $_SESSION['orderId'];
    
// Pass the transaction reference to the checkTransaction function
    $transactionChecker = new TransactionChecker();
    $resp = $transactionChecker->checkTransaction($transactionReference);
} else {
    echo '<p>Session variables not set.</p>';
}

class TransactionChecker {
    private $apiUrl = "https://testids.interswitch.co.ke/api/v1/merchant/transactions";
    private $clientId = 'IKIA6C606D0831914E1A4694DF84622147D1BB8D5364'; // Replace with your Interswitch API client ID
    private $clientSecretKey = 'DBHSMQecasN+5kNZI6+RjkfnAq3ihOyjczSzr8Tq2MpGhl5dn5zJQhAopGi6/chQ'; // Replace with your Interswitch API client secret key
    private $signatureMethod = 'SHA512';

    public function checkTransaction($transactionRef) {
        // Append the transactionRef to the API URL
        $url = $this->apiUrl . '/' . urlencode(strval ($transactionRef));

        // Make a GET request to the API
        $response = $this->makeGetRequest($url);

        if ($response === false) {
        echo "Error: Unable to connect to the API.\n";
        } 
        
        else {
            $data = $response;
            if (isset($data['responseCode']) && in_array($data['responseCode'], ['00', '0'])) {
               header("location: update_db.php");
               return 'success';
            } 
            else {
                header("location: payment.php");
                return 'failure';
            }
        }
        
        // Close the connection
        mysqli_close($link);
        }

    private function makeGetRequest($url) {
        // Generate InterswitchAuth headers
        $headers = $this->generateInterswitchAuth('GET', $url); // Pass $url as the second parameter

        // Initiate a cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the cURL session and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $response = false;
        }

        // Close the cURL session
        curl_close($ch);

        //var_dump($response);
        $responseData = json_decode($response, true);
        return $responseData;
    }

    private function generateInterswitchAuth($httpMethod, $resourceUrl) {
        $AUTHORIZATION_REALM = 'InterswitchAuth';
        $ISO_8859_1 = 'ISO-8859-1';
        $MERCHANT_ID = 'KENAIJA001';

        // Timezone MUST be Africa.
        $lagosTimeZone = new DateTimeZone('Africa/Nairobi');
        $calendar = new DateTime('now', $lagosTimeZone);

        // Timestamp must be in seconds.
        $timestamp = $calendar->getTimestamp();

        $uuid = uniqid();
        $nonce = str_replace('-', '', $uuid);

        $clientIdBase64 = base64_encode($this->clientId);
        $authorization = $AUTHORIZATION_REALM . ' ' . $clientIdBase64;
        
        $resourceUrl = str_replace("http://", "https://", $resourceUrl);
        if ($httpMethod === "GET") {
            $resourceUrl .= "?null";
        }
        $encodedResourceUrl = urlencode($resourceUrl);
        $signatureCipher = $httpMethod . '&' . $encodedResourceUrl . '&' .
            $timestamp . '&' . $nonce . '&' . $this->clientId . '&' . $this->clientSecretKey;

        $signature = base64_encode(hash($this->signatureMethod, $signatureCipher, true));

        $headers = [
            'Authorization: ' . $authorization,
            'Timestamp: ' . $timestamp,
            'Nonce: ' . $nonce,
            'SignatureMethod: ' . $this->signatureMethod,
            'Signature: ' . $signature,
            'Content-Type: application/json',
            'merchantID: ' . $MERCHANT_ID,
        ];
        return $headers;
    }
}

?>