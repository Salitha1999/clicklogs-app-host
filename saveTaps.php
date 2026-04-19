<?php

// 🔹 Firebase project details
$project_id = "clicklogs-project-adb";
$api_key = "AIzaSyA0BDyqvbDWf37srcfMSISWPzT1HAn9vSw";

// 🔹 Get POST data
$session_id = $_POST['id'];
$device = $_POST['var'];
$taps = $_POST['taps'];

// 🔹 Decode tap data
$tapArray = json_decode($taps);

// 🔹 Firestore collection URL
$url = "https://firestore.googleapis.com/v1/projects/$project_id/databases/(default)/documents/tap_logs?key=$api_key";

// 🔹 Current timestamp
$timestamp = time();

// 🔹 Loop through taps
foreach ($tapArray as $tapData) {

    $tapSequence = $tapData->tapSequenceNumber;
    $startTime = $tapData->startTimestamp;
    $endTime = $tapData->endTimestamp;
    $interface = $tapData->interface;

    $duration = $endTime - $startTime;

    // 🔹 Firestore format
    $data = [
        "fields" => [
            "session_id" => ["stringValue" => $session_id],
            "device" => ["stringValue" => $device],
            "tap_sequence" => ["integerValue" => (string)$tapSequence],
            "start_time" => ["integerValue" => (string)$startTime],
            "end_time" => ["integerValue" => (string)$endTime],
            "duration" => ["integerValue" => (string)$duration],
            "interface" => ["stringValue" => $interface],
            "timestamp" => ["integerValue" => (string)$timestamp]
        ]
    ];

    // 🔹 Send request
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    // 🔹 Error handling
    if ($response === false) {
        echo "CURL ERROR: " . curl_error($ch);
        exit();
    }

    curl_close($ch);
}

// 🔹 Final response (important for frontend)
echo "Data saved successfully";

?>
