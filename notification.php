<?php

// Check if the request method is POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the payload from the request body

    $payload = file_get_contents('php://input');

    

    // Decode the payload as JSON

    $payloadData = json_decode($payload, true);

    

    // Check if the status field exists in the payload

    if (isset($payloadData['status'])) {

        $status = $payloadData['status'];

        

        // Prepare the response based on the status

        if ($status === "0") {

            $response = array(

                'responseCode' => '0',

                'responseMessage' => 'Transaction Updated Successfully'

            );

            $statusCode = 200;

        } else{

            $response = array(

                'responseCode' => '1',

                'responseMessage' => 'Transaction could not be Updated'

            );

            $statusCode = 403;

        }

        // Set the response headers

        header('Content-Type: application/json');

        http_response_code($statusCode);

        

        // Send the response

        echo json_encode($response);

    } else {

        // Handle missing status field

        http_response_code(400);

        echo 'Invalid payload: status field is missing';

    }

} else {

    // Handle invalid request method

    http_response_code(405);

    echo 'Method Not Allowed';

}