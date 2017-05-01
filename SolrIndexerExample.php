<?php

$output_url = '<BASE URL OF YOUR DATA CATALOG INSTALLATION>/api/dataset/all.json';
$submit_url = '<BASE URL OF YOUR SOLR INSTALLATION>/solr/data_catalog/update/json?commit=true&overwrite=true';

if (! $fh = fopen($output_url, 'r')) {
    exit("Could not open '{$output_url}'\n");
}

$json = stream_get_contents($fh);
fclose($fh);

foreach (json_decode($json) as $row) {
    if ($row->dataset_end_date && $row->dataset_start_date) {
        $end_date = $row->dataset_end_date;
        if ('Present' == $row->dataset_end_date) {
            $end_date = (int) (date('Y') + 1);
        }

        if ($row->dataset_start_date == $end_date) {
            $row->dataset_years = ["{$row->dataset_start_date}-01-01T00:00:00Z","{$end_date}-01-01T00:00:00Z"];
        }
        else {
            $row->dataset_years = [];
            for ($i = $row->dataset_start_date; $i <= $end_date; $i++) {
                $row->dataset_years[] = "{$i}-01-01T00:00:00Z";
            }
        }
    }

    foreach ($row as $k => $v) {
        if (! $v) {
            $row->{$k} = '';
        }
    }
    if ($row->date_added) {
        $from_symfony = $row->date_added->date;
        $row->date_added = trim(explode(' ', $from_symfony)[0]) . 'T00:00:00Z';
    }

    $to_solr = json_encode([$row]);
    echo $to_solr;

    $context = stream_context_create([
            'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json' . "\r\n"
                        . 'Content-Length: ' . strlen($to_solr) . "\r\n",
                    'content' => $to_solr,
                ]
            ]);

    file_get_contents($submit_url, null, $context);
}
