<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Dataset;


/**
 * A controller for producing JSON output
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
 */
class JSONController extends Controller
{
  /**
   * Produce the JSON output
   *
   * @param string $slug The slug of a dataset, or "all"
   * @param string $_format The output format desired
   *
   * @return Response A Response instance
   *
   * @Route(
   *   "/api/dataset/{slug}.{_format}", name="json_output_all",
   *   defaults={"slug": "all", "_format":"json"},
   * ) 
   */ 
  public function JSONAction($slug, $_format) {

    $em = $this->getDoctrine()->getManager();

    if ($slug == "all") {
      $datasets = $em->getRepository('AppBundle:Dataset')
         ->findBy(array('published'=>1));
    } else {
      $datasets = $em->getRepository('AppBundle:Dataset')
         ->findOneBy(array('slug'=>$slug,'published'=>1));
    }

    if ($_format == "json") {
      $jsonContent = json_encode($datasets);
      $response = new Response();
      $response->setContent($jsonContent);
      $response->headers->set('Content-Type', 'application/json');

    }

    return $response;


  }


  /**
   * Accept JSON submissions
   *
   * @TODO
   *
   * @Route(
   *   "/api/add/dataset", name="json_input"
   * )
   */
  public function JSONInputAction() {

    throw new NotFoundHttpException();

  }






}
