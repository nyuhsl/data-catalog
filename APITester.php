<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://devdatacatalog.med.nyu.edu/',
    'verify'=>false,
    'http_errors'=>false,
]);

//$data = file_get_contents('./jsontest.json');

$data = array(
    "title"=> "API TEST all fields2",
    "origin"=> "Internal",
    "description"=> "a test dataset through the API",
    "access_instructions"=> "test",
    "published"=> "0",

    // the following fields represent related entities that can be added ad hoc.
    // think of these as sub-forms, where you have to specify the field names just like 
    // we do for the main dataset entity.
    //
    // the values included below represent ALL required fields. i.e. if you want to add
    // any "other_resources" you MUST include the "resource_name" and "resource_url" fields
    // as shown here
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
      array("person"=>"Dohoon Lee", "displayOrder"=>"1", "isCorrespondingAuthor"=>false, 'role'=>'Author'),
      array("person"=>"Ron Mincy", "displayOrder"=>"2", "isCorrespondingAuthor"=>true, 'role'=>'Author'),
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
    "access_restrictions" => array(
      "NYU only",
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
      "Dohoon Lee",
    ),
    "subject_domains" => array(
      "Cancer",
    ),
    "subject_genders" => array(
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
