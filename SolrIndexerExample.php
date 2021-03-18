<?php

/**
 *
 * Fill in the URL to your Solr core and to your data catalog installation here
 *
 */
$solr_core_url = 'https://www.example.com/solr/data_catalog';
$data_catalog_base_url = 'https://www.example.com';


$db_output_url = $data_catalog_base_url . '/api/Dataset/all.json?output_format=solr';
$solr_output_url = $solr_core_url . '/select/?q=*:*&wt=json';
$solr_submit_url = $solr_core_url . '/update/json?commit=true&overwrite=true';
$solr_remove_url = $solr_core_url . '/update/?commit=true';

if (! $fh = fopen($db_output_url, 'r')) {
    exit("Could not open '{$db_output_url}'\n");
}

$db_json = stream_get_contents($fh);
$db_json_parsed = json_decode($db_json);

fclose($fh);

#
# Find items to remove

if (! $fh = fopen($solr_output_url, 'r')) {
    exit("Could not open '{$solr_output_url}'\n");
}

$solr_json = stream_get_contents($fh);

fclose($fh);

foreach (json_decode($solr_json)->response->docs as $row) {
    
    $found=0;
    foreach($db_json_parsed as $db_row) {
        
        if ($row->id == $db_row->id) {
            $found=1;
            break;
        }
        
    }
    if ($found!=1) {
        
        $to_solr = "{'delete': {'id': ".$row->id."}}";
				$context = stream_context_create([
								'http' => [
												'method' => 'POST',
												'header' => 'Content-Type: application/json' . "\r\n"
														. 'Content-Length: ' . strlen($to_solr) . "\r\n",
												'content' => $to_solr,
										]
								]);

				file_get_contents($solr_remove_url, null, $context);
        print "delete ".$row->id."\n";
        
    }

}


#
# Find new/added items

foreach ($db_json_parsed as $row) {
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

    file_get_contents($solr_submit_url, null, $context);
}

