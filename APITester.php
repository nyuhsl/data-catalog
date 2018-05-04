<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_url' => 'https://devdatacatalog.med.nyu.edu',
    'defaults' => [
        'exceptions' => false
    ]
]);

$data = array(
    'title' => "API TEST",
    'origin' => "Internal",
    'description' => 'a test dataset through the API',
    'published' => false,

);
$response = $client->post('/api/dataset', [
    'body' => json_encode($data)
]);
echo $response;
echo "\n\n";
