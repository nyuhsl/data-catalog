<?php
namespace App\Entity;


/**
 * A class representing the current state of the user's search
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
