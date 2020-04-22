<?php
namespace App\Entity;

use App\Utils\Slugger;

/**
 * A class representing the current set of search results
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
    'Origin' => 'origin_fq',
    'Timeframe' => 'dataset_years',
    'Geographic Coverage' => 'subject_geographic_area_fq',
    'Access Restrictions' => 'access_restrictions_fq',
    'subject_domain_fq' => 'Subject Domain',
    'origin_fq' => 'Origin',
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
    array_unshift($this->dateFacets, array('before',$this->solrResponse->facet_counts->facet_ranges->dataset_years->before));
    $this->facets['dataset_years'] = $this->dateFacets;
    $this->numResults   = $this->solrResponse->response->numFound;
    $this->resultItems  = (array) $this->solrResponse->response->docs;
    foreach ($this->resultItems as $dataset) {
      $dataset->slug = Slugger::slugify($dataset->dataset_title);
    }

    $this->facets = $this->translateFacets($this->facets);

  }


  /**
   * Making user-friendly facet names and returning them in 
   * Twig-friendly arrays
   *
   * @param array $rawFacets Facets data from Solr
   *
   * @return array $translatedFacets A sane array of facet data for Twig
   */
  protected function translateFacets($rawFacets) {
    $translatedFacets = array();
    $rawFacets = (array) $rawFacets;
    foreach ($rawFacets as $key=>$value) {
      $newFacetName = array_search($key, $this->facetMappings);
      foreach ($value as $facetItem) {
        $translatedFacets[$newFacetName][] = array(
          'facetItem' => $facetItem[0],
          'facetCount'=> $facetItem[1]
        );
      }
    }
    $timeframes = $translatedFacets['Timeframe'];
    
    $begin  = substr($timeframes[1]['facetItem'], 0, 4);
    $second = substr($timeframes[2]['facetItem'], 0, 4);
    $third  = substr($timeframes[3]['facetItem'], 0, 4);
    $fourth = substr($timeframes[4]['facetItem'], 0, 4);
    $translatedFacets['Timeframe'][0]['facetItem'] = 'Prior to ' . $begin;
    $translatedFacets['Timeframe'][1]['facetItem'] = $begin . ' - ' . ($second-1);
    $translatedFacets['Timeframe'][2]['facetItem'] = $second . ' - ' . ($third-1);
    $translatedFacets['Timeframe'][3]['facetItem'] = $third . ' - ' . ($fourth-1);
    $translatedFacets['Timeframe'][4]['facetItem'] = $fourth . ' - Present';
     
    return $translatedFacets;
  }


}
