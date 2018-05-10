<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://devdatacatalog.med.nyu.edu/',
    'verify'=>false,
    'http_errors'=>false,
]);

$data = array(
    'title' => "API TESTtt",
    'origin' => "Internal",
    'description' => 'a test dataset through the API',
    'published' => false,
    'subject_keywords' => array(
      '1',
      '2'
    )
);

$response = $client->request('POST', 'api/dataset', [
  'headers' => [
    'X-AUTH-TOKEN' => 1234,
  ],
  'body' => json_encode($data),
  'debug' => true
]);

$code = $response->getStatusCode();
$reason = $response->getReasonPhrase();
$body = $response->getBody();

echo "\n\n" . $code . ' ' . $reason . "\n\n" . "$body";
echo "\n\n";
