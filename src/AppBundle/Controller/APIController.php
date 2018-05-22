<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\DatasetViaApiType;
use AppBundle\Entity\Dataset;
use AppBundle\Utils\Slugger;


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
 *
 */
class APIController extends Controller
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
   * @Method("GET")
   */ 
  public function APIDatasetGetAction($slug, $_format) {

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();

    if ($slug == "all") {
      $datasets = $qb->select('d')
                     ->from('AppBundle:Dataset', 'd')
                     ->where('d.archived = 0 OR d.archived IS NULL')
                     ->andWhere('d.published = 1')
                     ->getQuery()->getResult();
    } else {
      $datasets = $qb->select('d')
                     ->from('AppBundle:Dataset', 'd')
                     ->where('d.slug = :slug')
                     ->andWhere('d.published = 1')
                     ->andWhere('d.archived = 0 OR d.archived IS NULL')
                     ->setParameter('slug', $slug)
                     ->getQuery()->getResult();
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
   * Ingest dataset via API
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/api/dataset")
   * @Method("POST")
   */
  public function APIDatasetPostAction(Request $request) {
    $submittedData = json_decode($request->getContent(), true);
    $dataset = new Dataset();
    $em = $this->getDoctrine()->getManager();
    $userCanSubmit = $this->get('security.context')->isGranted('ROLE_API_SUBMITTER');

    $datasetUid = $em->getRepository('AppBundle:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);

    if ($userCanSubmit) {
      $form = $this->createForm(new DatasetViaApiType($userCanSubmit, $datasetUid), $dataset, array('csrf_protection'=>false));
      $form->submit($submittedData);
      if ($form->isValid()) {
        $dataset = $form->getData();
        // enforce that all datasets ingested via the API will start out unpublished
        $dataset->setPublished(false);
        $addedEntityName = $dataset->getTitle();
        $slug = Slugger::slugify($addedEntityName);
        $dataset->setSlug($slug);

        $em->persist($dataset);
        foreach ($dataset->getAuthorships() as $authorship) {
          $authorship->setDataset($dataset);
          $em->persist($authorship);
        }
        $em->flush();

        return new Response('Dataset Successfully Added', 201);
      } else {
          $errors = $form->getErrorsAsString();
          $response = new Response(json_encode($errors), 422);
          $response->headers->set('Content-Type', 'application/json');

          return $response;
      }
    } else {
        return new Response('Unauthorized', 401);
    }
  }



}
