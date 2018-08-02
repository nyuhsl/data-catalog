<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Dataset;


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
   * Return the rendered title and slug of a dataset given an ID
   *
   * @param int|string $id A dataset's UID
   *
   * @return Response A Response instance
   */
  public function relatedDatasetAction($id) {

    $em = $this->getDoctrine()->getManager();

    $dataset = $em->getRepository('AppBundle:Dataset')
         ->findOneBy(array('dataset_uid'=>$id));
    
    return $this->render('default/related_dataset_link.html.twig',array(
                'title' => $dataset->getTitle(),
                'uid' => $dataset->getDatasetUid(),
                'slug'=> $dataset->getSlug(),
                ));
    
  }



}
