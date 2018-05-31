<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://example.com/', // YOUR BASE URI
    'verify'=>false,
    'http_errors'=>false,
]);


/*******
 *
 * if you want to test with the JSON file instead of the below array,
 * uncomment the following line:
 *
 *******/
//$data = file_get_contents('./JSON_sample.json');


/*******
 *
 * NOTE: This data is used as an example only, to demonstrate the format
 *       that the API is expecting for each field. If you wish to ingest 
 *       this sample data, read the comments below as many fields will require
 *       the related entity to already exist in the database (e.g. if you want
 *       to apply Subject Keywords to this dataset, the keywords must already 
 *       exist in the database)
 *
 *******/
$data = array(
    "title"=> "API TEST all fields",
    "origin"=> "Internal",
    "description"=> "a test dataset through the API",
    "access_instructions"=> "test",
    "pubmed_search"=> "http://www.example.com",
    "dataset_size"=> "100MB",
    "subject_start_date"=> "1985",
    "subject_end_date"=> "Present",
    "erd_url"=> "http://www.example.com",
    "library_catalog_url"=> "http://www.example.com",
    "licensing_details"=> "testing licensing details",
    "license_expiration_date"=> array(
        "year"=>2014,
        "month"=>5,
        "day"=>25
    ),
    "subscriber"=> "0",

    // the following fields represent related entities that can be added ad hoc.
    // think of these as sub-forms, where you have to specify the field names just like 
    // we do for the main dataset.
    //
    // the examples below show required fields. e.g. if you want to add a "data_location"
    // you must include the "data_access_url", if you want to include an "authorship" you 
    // must include "displayOrder", "isCorrespondingAuthor", and "role" (which should always
    // be "Author" in this case)
    "data_locations"=> array(
      array("data_location"=>"testlocation1", "data_access_url"=>"http://www.example.com"),
      array("data_location"=>"testlocation2", "data_access_url"=>"http://www.example.com/2")
    ),
    "dataset_alternate_titles"=> array(
      array("alt_title"=>"testing this thing"),
      array("alt_title"=>"testing it again"),
    ),
    "other_resources"=> array(
      array("resource_name"=>"resource1", "resource_url"=>"http://www.example.com"),
      array("resource_name"=>"resource2", "resource_url"=>"http://www.example.com/2")
    ),
    "related_datasets"=> array(
      array("related_dataset_uid"=>"10193"),
      array("related_dataset_uid"=>"10192"),
    ),

    //
    "authorships"=> array(
      array("person"=>"Fake Person", "displayOrder"=>"1", "isCorrespondingAuthor"=>false, 'role'=>'Author'),
      array("person"=>"Another Fake Person", "displayOrder"=>"2", "isCorrespondingAuthor"=>true, 'role'=>'Author'),
    ),

    // the following fields use a controlled vocabulary, so their values must exist in the database
    // before they can be set here. we just use the entity's "displayName" property
    "subject_keywords" => array(
      "Adolescents",
      "Adult",
    ),
    "publishers" => array(
      "Vizient",
    ),
    "publications" => array(
      "Meyers K, Rodriguez K, Moeller RW, Gratch I, Markowitz M, Halkitis PN (2014) High Interest in a Long-Acting Injectable Formulation of Pre-Exposure Prophylaxis for HIV in Young Men Who Have Sex with Men in NYC: A P18 Cohort Substudy. PLoS ONE 9(12): e114700.",
    ),
    "access_restrictions" => array(
      "Internal only",
    ),
    "related_equipment" => array(
      "Microscope",
    ),
    "related_software" => array(
      "Microsoft Excel",
    ),
    "dataset_formats" => array(
      "SAS",
    ),
    "data_types" => array(
      "Surveys",
    ),
    "data_collection_standards"=> array(
      "American College of Radiology/ National Electrical Manufacturers Association 2.0 (ACR/NEMA 2.0)"
    ),
    "awards" => array(
      "67129",
    ),
    "local_experts" => array(
      "Fake Person",
    ),
    "subject_domains" => array(
      "Cancer",
    ),
    "subject_genders" => array(
      "Male",
    ),
    "subject_sexes" => array(
      "Male",
    ),
    "subject_population_ages" => array(
      "Newborn (under 1 month)",
    ),
    "subject_geographic_areas" => array(
      "National",
    ),
    "subject_geographic_area_details" => array(
      "California",
    ),
    "study_types" => array(
      "Observational",
    ),
    "subject_of_study" => array(
      "Human",
    ),
  );


$response = $client->request('POST', 'api/dataset', [
  'headers' => [
    'X-AUTH-TOKEN' => "FAKE TOKEN", // YOUR API KEY GOES HERE
  ],
  'body' => json_encode($data),
  'debug' => true
]);

$code = $response->getStatusCode();
$reason = $response->getReasonPhrase();
$body = $response->getBody();

echo "\n\n" . $code . ' ' . $reason . "\n\n" . "$body";
echo "\n\n";
