<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Entity\SearchState;

/**
 * A Symfony service to translate a user query into a Solr search URL and
 * retrieve the results.
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
class SolrSearchr {

  // App-level defaults are set in parameters.yml
  protected $solrBaseURL;
  protected $solrFormat;
  protected $solrFacets;
  protected $solrDisplayFields;
  protected $solrSearchFields;

  // Request-level defaults are set here
  protected $solrSort = "dataset_title_str+asc";
  protected $solrResultsPP = 10;
  protected $solrPageNum = 1;
  protected $solrKeyword = "*";
  protected $solrFacetQuery = null;

  // Will become a SearchState object representing current user query
  protected $currentSearch;

  // Maps user-friendly values to Solr-friendly values
  protected $sortMappings = array(
                             'relevance'     => 'score+desc',
                             'date added'    => 'date_added+desc',
                             'name'          => 'dataset_title_str+asc',
                            );


  /**
   * These config parameters are set in parameters.yml and supplied by Symfony automatically
   * when this service is instantiated in GeneralController.php
   */
  public function __construct($solrServer,
                              $solrFormat,
                              $solrFacets,
                              $solrDisplayFields,
                              $solrSearchFields) {

    $this->solrBaseURL  = $solrServer;
    $this->solrFormat   = $solrFormat;
    $this->solrFacets   = $solrFacets;
    $this->solrDisplayFields   = $solrDisplayFields;
    $this->solrSearchFields   = $solrSearchFields;
  }


  /**
   * Set user search based on SearchState object properties (called in GeneralController.php)
   *
   * @param SearchState $currentSearch An object representing the user's query
   */
  public function setUserSearch(SearchState $currentSearch) {
    $this->setKeyword($currentSearch->keyword);
    $this->setSort($currentSearch->sort);
    $this->setPage($currentSearch->page);
    $this->setResultsPP($currentSearch->resultsPP);
    $this->setFacetQuery($currentSearch->facets);
  }


  /**
   * Fetch results from Solr using the URL we constructed
   *
   * @return string A HTTP response from Solr
   */
  public function fetchFromSolr() {

    $requestURL = $this->constructSolrURL();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $requestURL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if (!$resp = curl_exec($ch)) {
      trigger_error(curl_error($ch));
    }
    
    curl_close($ch);
    return $resp;

  }


  /**
   * Cobble together all our little functions to create one Solr URL
   *
   * @return string The URL we can use to fetch from Solr
   */
  protected function constructSolrURL() {

    $keywordQuery = $this->makeKeywordQuery();
    $facetQuery   = $this->makeFacetQuery();
    $datesQuery   = $this->makeDateRangeFacetQuery();
    $sortQuery    = $this->makeSortQuery();
    $startQuery   = $this->makeStartQuery();
    $rowsQuery    = $this->makeRowsQuery();
    $fieldsQuery  = $this->makeFieldsQuery();

    $method = 'select';

    $URL = $this->solrBaseURL
           . $method
           . '?'
           . $keywordQuery
           . $facetQuery
           . $datesQuery
           . $sortQuery
           . $startQuery
           . $rowsQuery
           . $fieldsQuery
           . '&wt=' . $this->solrFormat;

    if ($this->solrFormat == 'json') {
      $URL .= "&json.nl=arrarr";
    }

    return $URL;
  }


  /**
   * Produce the Keyword portion of the Solr URL
   *
   * @return string The Keyword portion of the URL
   */
  protected function makeKeywordQuery() {
    
    $base_query = "q=";
    $keyword_query_string = trim($this->solrKeyword);
    // is this query trying to return all results? if so, let it through
    if ($keyword_query_string != "*") {
      // if not, is this query trying to limit to one specific field? (i.e. access_instructions:"query") if so, let it through
      if (strpos($keyword_query_string, ":") === false) {
        // strip apostrophes from all normal queries so as not to confuse Solr
        $keyword_query_string = str_replace("'", "", $keyword_query_string);
        // construct keyword query based on fields specified in parameters.yml
        $queryComponents = "";
        foreach ($this->solrSearchFields as $fieldToSearch) {
            $queryPart = $fieldToSearch . ":\"" . $keyword_query_string . "\" OR ";
            $queryComponents .= $queryPart;
        }
        // and make extra-sure we're searching the title field
        $keyword_query_string = $queryComponents . "dataset_title:\"" . $keyword_query_string . "\"";
      } elseif (strpos($keyword_query_string, "authors:") === true) {
        // but, keep apostrophes if we're searching for proper names, these queries are double-quoted so we need an exact match
      }
    }

    return $base_query . urlencode($keyword_query_string);

  }


  /**
   * Produce the Fields portion of the Solr URL
   *
   * @return string The Fields portion of the URL
   */
  protected function makeFieldsQuery() {
    $fields_query_string = '&fl=' . join(',', $this->solrDisplayFields);

    return $fields_query_string;
  }


  /**
   * Make the Facets portion of the Solr URL
   *
   * @return string The Facets portion of the URL
   */
  protected function makeFacetQuery() {
    $facet_query_string = "&facet=true&facet.mincount=0";

    // Tell Solr which fields we're making facets out of (stored in parameters.yml)
    for($i=0, $size=count($this->solrFacets); $i<$size; ++$i) {
      $this_facet = "&facet.field=" . $this->solrFacets[$i];
      $facet_query_string .= $this_facet;
    }

    // Tell Solr if the user has filtered this search using any of the facets
    if ($this->solrFacetQuery) {
      for($i=0,$size=count($this->solrFacetQuery); $i<$size; ++$i) {
        // If it's a date facet, we need to use a different function
        if (strpos($this->solrFacetQuery[$i], 'dataset_years') !== FALSE) {
          $this_facet_string = "&fq=" . urlencode($this->makeDateRangeFilterQuery($this->solrFacetQuery[$i]));
        } else {
          $this_facet_string = "&fq=" . urlencode($this->solrFacetQuery[$i]);
        }
        $facet_query_string .= $this_facet_string;
      }
    }

    return $facet_query_string;

  }


  /**
   * Make the Date Facets part of the URL
   *
   * @param string $facet The facet to be turned into a range facet
   *
   * @return string The date range facets portion of the Solr URL
   */
  protected function makeDateRangeFilterQuery($facet) {
    $parts = explode(':', str_replace('"','',$facet));
    if (strpos($parts[1], 'Prior to') !== FALSE) {
      $start = '1700-01-01T00:00:00Z';
      $end   = substr($parts[1], -4);
      $end = ($end - 1) . '-12-29T12:59:59Z';
    } elseif (strpos($parts[1], 'Present') !== FALSE) {
      $start = substr($parts[1], 0,4) . '-01-01T00:00:00Z';
      $end   = "NOW";
    } else {
      $years = explode(' - ', $parts[1]);
      $start = $years[0] . "-01-01T00:00:00Z";
      $end   = $years[1] . "-12-29T12:59:59Z";
    }

    $date_range = $parts[0] . ":" . "[" . $start . " TO " . $end . "]";

    return $date_range;
    
  }


  /** 
   * Creates a date range facet query starting 40 years ago with an interval of 10 years b/w facets
   *
   * @return string The Date Range Facets portion of the query URL
   **/
  protected function makeDateRangeFacetQuery() {
    $date_range_query  = "&facet.range=dataset_years&f.dataset_years.facet.range.start=NOW/YEAR-40YEAR";
    $date_range_query .= "&f.dataset_years.facet.range.end=NOW/YEAR&f.dataset_years.facet.range.gap=%2b10YEAR";
    $date_range_query .= "&f.dataset_years.facet.range.hardend=true&f.dataset_years.facet.range.other=before";

    return $date_range_query;
  }


  /**
   * Produce the Sort portion of the Solr URL
   *
   * @return string The Sort portion of the URL
   */
  protected function makeSortQuery() {

    $sort_query_string = "&sort=" . $this->sortMappings[$this->solrSort];
    return $sort_query_string;

  }


  /**
   * Tell Solr what page of results we're on
   *
   * @return The Start portion of the query URL
   */
  protected function makeStartQuery() {

    $start_query_string = "&start=";
    $start_num = ($this->solrPageNum - 1) * $this->solrResultsPP;

    $start_query_string .= $start_num;

    return $start_query_string;
  }


  /** 
   * Tell Solr how many results we want to see at a time
   *
   * @return string The Rows portion of the query URL
   */
  protected function makeRowsQuery() {

    $rows_query_string = "&rows=" . $this->solrResultsPP;
    return $rows_query_string;

  }



  # GETTERS 'N' SETTERS


  /**
   * Set Keyword
   *
   * @param string The Keyword
   */
  public function setKeyword($keyword) {
    if ($keyword) {
      $this->solrKeyword = $keyword;
    }
  }


  /**
   * Set Sort
   *
   * @param string The Sorting of results
   */
  public function setSort($sort) {
    if ($sort) {
      $this->solrSort = $sort;
    }
  }


  /**
   * Set Results Per Page
   *
   * @param int The results per page
   */
  public function setResultsPP($resultsPP) {
    if ($resultsPP) {
      $this->solrResultsPP = $resultsPP;
    }
  }


  /**
   * Set Facet Query
   *
   * @param string The facet query
   */
  public function setFacetQuery($facet_query) {
    if ($facet_query) {
      $this->solrFacetQuery = $facet_query;
    }
  }


  /**
   * Set page number
   *
   * @param int The page number
   */
  public function setPage($page_num) {
    if ($page_num) {
      $this->solrPageNum = $page_num;
    }

  }


}
