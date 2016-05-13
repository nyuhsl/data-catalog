<?php
namespace AppBundle\Entity;


/**
 * A class representing the current state of the user's search
 */
class SearchState {

  public $facets;
  public $resultsPP;
  public $keyword;
  public $sort;
  public $page;

 /**
  * On instantiation, translate request parameters into object properties
  *
  * @param Request The current HTTP request
  */
  public function __construct($request) {

    $this->facets = $request->query->get('facet');
    $this->keyword = $request->query->get('keyword');
    $this->resultsPP = ($request->query->get('results')) ? $request->query->get('results') : '10';
    $this->page = ($request->query->get('page')) ? $request->query->get('page') : '0';
    $this->sort = $request->query->get('sort');
    
    if ($this->keyword && !$this->sort) {
      // if a keyword was entered, but sort wasn't specified
      $this->sort = 'relevance';
    }
    elseif (!$this->sort) {
      // if no keyword, and no sort specified (this is the default)
      $this->sort = 'name';
    }
    else {
      // if no keyword, but sort is specified, then
      // pass, using the sort from Request, set above
    }

  }



}
