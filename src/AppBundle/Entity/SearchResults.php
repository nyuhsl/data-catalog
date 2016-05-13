<?php
namespace AppBundle\Entity;

use AppBundle\Utils\Slugger;

/**
 * A class representing the current set of search results
 */
class SearchResults {

  public $facets;

  public $numResults;

  public $resultItems;

  protected $solrResponse;


  /**
   * Maps Solr facet names to user-friendly facet names
   */
  public $facetMappings = array(
    'Subject Domain' => 'subject_domain_fq',
    'Timeframe' => 'dataset_years',
    'Geographic Coverage' => 'subject_geographic_area_fq',
    'Access Restrictions' => 'access_restrictions_fq',
    'subject_domain_fq' => 'Subject Domain',
    'dataset_years' => 'Dataset Timeframes',
    'subject_geographic_area_fq' => 'Geographic Coverage',
    'access_restrictions_fq' => 'Access Restrictions',
  );


  /**
   * On instantiation, translate the JSON response from Solr
   * into properties on this object
   *
   * @param string $solrResponse A JSON response from Solr
   */
  public function __construct($solrResponse) {
    $this->solrResponse = json_decode($solrResponse);
    if (isset($this->solrResponse->facet_counts->facet_fields)) {
      $this->facets = (array) $this->solrResponse->facet_counts->facet_fields;
    } 
    else {
      throw new \RuntimeException('Solr server is reachable, but returns unexpected response. Check the full URL that is being requested');
    }
    $this->dateFacets   = (array) $this->solrResponse->facet_counts->facet_ranges->dataset_years->counts;
    array_unshift($this->dateFacets, 'before',$this->solrResponse->facet_counts->facet_ranges->dataset_years->before);
    $this->facets['dataset_years'] = $this->dateFacets;
    $this->numResults   = $this->solrResponse->response->numFound;
    $this->resultItems  = (array) $this->solrResponse->response->docs;
    foreach ($this->resultItems as $dataset) {
      $dataset->slug = Slugger::slugify($dataset->dataset_title);
    }

    $this->facets = $this->translateFacets($this->facets);

  }


  /**
   * We need to get the facets info out of Solr's odd array structure
   * so templates can make use of it easier
   *
   * @param array $rawFacets Facets data from Solr
   *
   * @return array $translatedFacets A sane array of facet data for Twig
   */
  protected function translateFacets($rawFacets) {
    $translatedFacets = array();
    $rawFacets = (array) $rawFacets;
    foreach ($rawFacets as $key=>$value) {
      for ($i=0,$size=count($value);$i<$size;$i+=2) {
        $newFacetName = array_search($key, $this->facetMappings);
        $facetItemName = $rawFacets[$key][$i];
        $facetItemCount= $rawFacets[$key][$i+1];
        $translatedFacets[$newFacetName][] = array(
          'facetItem' => $facetItemName,
          'facetCount'=> $facetItemCount
        );
      }
    }
    // display names for date facets
    $translatedFacets['Timeframe'][0]['facetItem'] = 'Prior to 1975';
    $translatedFacets['Timeframe'][1]['facetItem'] = '1975 - 1984';
    $translatedFacets['Timeframe'][2]['facetItem'] = '1985 - 1994';
    $translatedFacets['Timeframe'][3]['facetItem'] = '1995 - 2004';
    $translatedFacets['Timeframe'][4]['facetItem'] = '2005 - Present';
    
    return $translatedFacets;
  }


}
