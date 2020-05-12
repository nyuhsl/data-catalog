<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\DatasetViaApiType;
use App\Entity\Dataset;
use App\Utils\Slugger;


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

  private $security;

  /**
   *  We have several pseudo-entities that all relate back to the Person
   *  entity. We'll check this array so we know if we encounter one of them.
   */
  public $personEntities = array(
     'Author',
     'LocalExpert',
     'CorrespondingAuthor',
  );

  public function __construct(Security $security) {
    $this->security = $security;
  }

  /**
   * Produce the JSON output
   *
   * @param string $slug The slug of a dataset, or "all"
   * @param string $_format The output format desired
   * @param Request $request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route(
   *   "/api/Dataset/{uid}.{_format}", name="json_output_datasets",
   *   defaults={"uid": "all", "_format":"json"},
   * ) 
   * @Method("GET")
   */ 
  public function APIDatasetGetAction($uid, $_format, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();

    if ($uid == "all") {
      $datasets = $qb->select('d')
                     ->from('App:Dataset', 'd')
                     ->where('d.archived = 0 OR d.archived IS NULL')
                     ->andWhere('d.published = 1')
                     ->getQuery()->getResult();
    } else {
      $datasets = $qb->select('d')
                     ->from('App:Dataset', 'd')
                     ->where('d.dataset_uid = :uid')
                     ->andWhere('d.published = 1')
                     ->andWhere('d.archived = 0 OR d.archived IS NULL')
                     ->setParameter('uid', $uid)
                     ->getQuery()->getResult();
    }

    $output_format = $request->get('output_format', 'default');

    switch ($output_format) {
      case "default":
        // default will use the entity's jsonSerialize() method
        $content = $datasets;
        break;
      case "solr":
        // for Solr
        $content = array();
        foreach ($datasets as $dataset) {
          $content[] = $dataset->serializeForSolr();
        }
        break;
      case "complete":
        $content = array();
        foreach ($datasets as $dataset) {
          $content[] = $dataset->serializeComplete();
        }
        break;
      default:
        // default will use the entity's jsonSerialize() method
        $content = $datasets;
    }
    
    if ($_format == "json") {
      $response = new Response();
      $response->setContent(json_encode($content));
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }


  }


  /** 
   * Ingest dataset via API
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/api/Dataset")
   * @Method("POST")
   */
  public function APIDatasetPostAction(Request $request) {
    $submittedData = json_decode($request->getContent(), true);
    $dataset = new Dataset();
    $em = $this->getDoctrine()->getManager();
    $userCanSubmit = $this->security->isGranted('ROLE_API_SUBMITTER');

    $datasetUid = $em->getRepository('App:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);

    if ($userCanSubmit) {
      $form = $this->createForm(new DatasetViaApiType($userCanSubmit, $datasetUid), $dataset, array('csrf_protection'=>false));
      $form->submit($submittedData);
      if ($form->isSubmitted() && $form->isValid()) {
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


  /**
   * Ingest other entities via API
   *
   * @param string $entityName The name of the new entity
   * @param Request the current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/api/{entityName}")
   * @Method("POST")
   */
  public function APIEntityPostAction($entityName, Request $request) {
    $submittedData = json_decode($request->getContent(), true);

    if ($entityName == 'User') {
      return new Response('Users cannot be added via API', 403);
    } else {
      $addTemplate = 'add.html.twig';
    }

    $userCanSubmit = $this->security->isGranted('ROLE_API_SUBMITTER');
    
    //prefix with namespaces so it can be called dynamically
    if (in_array($entityName, $this->personEntities)) {
      $newEntity = 'App\Entity\\Person';
    } else {
      $newEntity = 'App\Entity\\' . $entityName;
    }
    $newEntityFormType = 'App\Form\Type\\' . $entityName . "Type";

    $em = $this->getDoctrine()->getManager();
    if ($userCanSubmit) {
      $form = $this->createForm(new $newEntityFormType(), 
                                new $newEntity(),
                                array('csrf_protection'=>false));
      $form->submit($submittedData);
      if ($form->isSubmitted() && $form->isValid()) {
        $entity = $form->getData();

        // Create a slug using each entity's getDisplayName method
        $addedEntityName = $entity->getDisplayName();
        $slug = Slugger::slugify($addedEntityName);
        $entity->setSlug($slug);
        
        $em->persist($entity);
        $em->flush();

        return new Response($entityName . ': "' . $addedEntityName . '" successfully added.', 201);
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


  /**
   * List related entities 
   *
   * @param string $slug The slug of an entity, or "all"
   * @param string $_format The output format desired
   * @param Request $request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route(
   *   "/api/{entityName}/{slug}.{_format}", name="json_output_related",
   *   defaults={"slug": "all", "_format":"json"},
   * ) 
   * @Method("GET")
   */ 
  public function APIEntityGetAction($entityName, $slug, $_format, Request $request) {
    if ($entityName == 'User') {
      return new Response('Users cannot be fetched via API', 403);
    }

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();
    if (in_array($entityName, $this->personEntities)) {
      $entity = 'App\Entity\\Person';
    } else {
      $entity = 'App\Entity\\' . $entityName;
    }

    if ($slug == "all") {
      $entities = $qb->select('e')
                     ->from($entity, 'e')
                     ->getQuery()->getResult();
    } else {
      $entities = $qb->select('e')
                     ->from($entity, 'e')
                     ->where('e.slug = :slug')
                     ->setParameter('slug', $slug)
                     ->getQuery()->getResult();
    }
    for ($i = 0; $i < count($entities); $i++) {
      $entities[$i] = $entities[$i]->getAllProperties();
    }

    if ($_format == "json") {
      $response = new Response();
      $response->setContent(json_encode($entities));
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }


  }

}
