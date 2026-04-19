<?php

$project_id = "clicklogs-project-adb";

$session_id = $_POST['id'];
$device = $_POST['var'];
$taps = $_POST['taps'];

$tapArray = json_decode($taps);

$api_key = "AIzaSyA0BDyqvbDWf37srcfMSISWPzT1HAn9vSw";

foreach ($tapArray as $tapData) {

    $tapSequence = $tapData->tapSequenceNumber;
    $startTime = $tapData->startTimestamp;
    $endTime = $tapData->endTimestamp;
    $interface = $tapData->interface;

    $duration = $endTime - $startTime;

    $url = "https://firestore.googleapis.com/v1/projects/$project_id/databases/(default)/documents/tap_logs?key=$api_key";

    $data = [
        "fields" => [
            "session_id" => ["stringValue" => $session_id],
            "device" => ["stringValue" => $device],
            "tap_sequence" => ["integerValue" => $tapSequence],
            "start_time" => ["integerValue" => $startTime],
            "end_time" => ["integerValue" => $endTime],
            "duration" => ["integerValue" => $duration],
            "interface" => ["stringValue" => $interface],
            "timestamp" => ["integerValue" => $timestamp]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if ($response === false) {
        echo "CURL ERROR: " . curl_error($ch);
        exit();
    }

    curl_close($ch);
}

echo "SUCCESS";

?>
