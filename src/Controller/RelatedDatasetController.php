<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dataset;


/**
 * A controller that returns the title and slug of a related dataset
 * so it can be displayed in the Twig template
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
class RelatedDatasetController extends Controller
{
  /**
   * Given a list of related datasets, fetch ones that are publicly-visible,
   * and render a list for display in a dataset record
   *
   * @param int|string $id A dataset's UID
   *
   * @return Response A Response instance
   */
  public function relatedDatasetAction($relatedDatasets) {

    $em = $this->getDoctrine()->getManager()->getRepository('App:Dataset');;

    $datasetsForDisplay = array();
    foreach ($relatedDatasets as $related) {
      // find dataset IF it is published AND not archived
      $relatedDataset = $em->findOneBy(array(
                             'dataset_uid' => $related->getRelatedDatasetUid(), 
                             'published'   => 1,
                             'archived'    => 0
                        ));
      if ($relatedDataset) {
        $section = array('dataset' => $relatedDataset);
        $notes = $related->getRelationshipNotes();
        if ($notes) {
          $section['relationshipNotes'] = $notes;
        }
        $datasetsForDisplay[] = $section;
      }
    }
    
    if ($datasetsForDisplay) {
      return $this->render('default/related_dataset_links.html.twig',array(
                  'relatedDatasets' => $datasetsForDisplay,
                  ));
    } else {
      // return empty response so the "Related Datasets" field will not appear
      return new Response();
    } 
  }

}
