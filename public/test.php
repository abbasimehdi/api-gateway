<?php
$ch = curl_init('http://127.0.0.1:8000/api/users');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Force IPv4

$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$error = curl_error($ch);
$errno = curl_errno($ch);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "cURL error number: $errno\n";
echo "cURL error message: $error\n";
echo "Verbose log:\n$verboseLog\n";
echo "Response:\n$response\n";