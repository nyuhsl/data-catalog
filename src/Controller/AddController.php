<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dataset;
use App\Form\Type\DatasetAsAdminType;
use App\Form\Type\DatasetAsUserType;
use App\Utils\Slugger;

/*
 * A controller to handle the addition of new datasets and other entities
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
class AddController extends Controller {

  private $security;

  public function __construct(Security $security) {
    $this->security = $security;
  }

  /**
   *  We have several pseudo-entities that all relate back to the Person
   *  entity. We'll check this array so we know if we encounter one of them.
   */
  public $personEntities = array(
     'Author',
     'LocalExpert',
     'CorrespondingAuthor',
  );

  /**
   * Build the form to add a new dataset
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/add/Dataset", name="add_dataset")
   */
  public function addAction(Request $request) {
    $dataset = new Dataset();
    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');
    $em = $this->getDoctrine()->getManager();
    $datasetUid = $em->getRepository('App:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);

    if ($userIsAdmin) {
      $form = $this->createForm(DatasetAsAdminType::class, $dataset, array(
	  'datasetUid' => $datasetUid,
          'action' => $this->generateUrl('ingest_dataset')));
      return $this->render('default/add_dataset_admin.html.twig', array(
        'form'=> $form->createView(),
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
      ));
    } else {
      $form = $this->createForm(DatasetAsUserType::class, $dataset, array(
	  'datasetUid' => $datasetUid,
          'action' => $this->generateUrl('ingest_dataset')));
      return $this->render('default/add_dataset_user.html.twig', array(
        'form'=> $form->createView(),
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
      ));
    }
  

  }
  
  
  /**
   * Validate the form. Ingest if valid, send user back otherwise·
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/ingest_dataset", name="ingest_dataset")
   */
  public function ingestDataset(Request $request) {
    $dataset = new Dataset();
    $em = $this->getDoctrine()->getManager();
    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');

    $datasetUid = $em->getRepository('App:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);
    
    if ($userIsAdmin) {
      $form = $this->createForm(DatasetAsAdminType::class, $dataset, array(
	  'datasetUid' => $datasetUid));
    } else {
      $form = $this->createForm(DatasetAsUserType::class, $dataset, array(
	  'datasetUid' => $datasetUid));
    }

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $dataset = $form->getData();
      
      $addedEntityName = $dataset->getTitle();
      $slug = Slugger::slugify($addedEntityName);
      $dataset->setSlug($slug);
      
      $em->persist($dataset);
      foreach ($dataset->getAuthorships() as $authorship) {
        $authorship->setDataset($dataset);
        $em->persist($authorship);
      }
      $em->flush();
     
      return $this->render('default/add_success.html.twig', array(
        'adminPage'=>true,
        'entityName'=>'Dataset',
        'displayName'=>'Dataset',
        'addedEntityName'=>$addedEntityName,
        'userIsAdmin'=>$userIsAdmin,
        'uid'=>$datasetUid,
      ));
    } else {

      $formToRender = $userIsAdmin ? 'default/add_dataset_admin.html.twig' : 'default/add_dataset_user.html.twig';

      return $this->render($formToRender, array(
        'form' => $form->createView(),
        'userIsAdmin'=>$userIsAdmin,
        'entityName'=>'Dataset',
        'adminPage'=>true,));
    }

  }


  /**
   * Create a form to add an instance of the entity specified in the URL.
   * Also validates and ingests the object.
   *·
   * @param string $entityName The name of the new entity
   * @param Request $request The current HTTP request
   *·
   * @return Response A Response instance
   *
   * @Route("/add/{entityName}", name="add_new_entity")
   */
  public function addNewEntity($entityName, Request $request) {
    //check if form will appear in a modal
    $modal = $request->get('modal', false);
    $addTemplate = ($entityName == 'User') ? 'add_user.html.twig' : 'add.html.twig';
    $successTemplate = 'add_success.html.twig';
    $action = '/add/'.$entityName;
    if ($modal) {
      $action . '?modal=true';
      $addTemplate = "modal_" . $addTemplate;
      $successTemplate = "modal_" . $successTemplate;
    }

    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');
    
    //make user-friendly name for display
    $entityTypeDisplayName = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $entityName));
    
    //prefix with namespaces so it can be called dynamically
    if ($entityName == 'User') {
      $newEntity = 'App\Entity\Security\\' . $entityName;
    } elseif (in_array($entityName, $this->personEntities)) {
      $newEntity = 'App\Entity\\Person';
    } else {
      $newEntity = 'App\Entity\\' . $entityName;
      
    }
    $newEntityFormType = 'App\Form\Type\\' . $entityName . "Type";

    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm($newEntityFormType, 
                              new $newEntity(),
                              array(
                                'action'=>$action,
                                'method'=>'POST'));
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entity = $form->getData();

      // Create a slug using each entity's getDisplayName method
      $addedEntityName = $entity->getDisplayName();
      $slug = Slugger::slugify($addedEntityName);
      $entity->setSlug($slug);
      // also generate the API key for new API users here
      if ($entityName == 'User') {
        foreach ($entity->getRoles() as $role) {
          if ($role->getRole() == 'ROLE_API_SUBMITTER') {
            $apiKey = sha1(random_bytes(32));
            $entity->setApiKey($apiKey);
          }
        }
      }
      
      $em->persist($entity);
      $em->flush();
      
      //
      // Added, 6/28/2017, Joel Marchewka
      //
      // Retrieves the ID of the entity once it is persisted and adds it to render bundle for the twig
      $addedId=$entity->getId();
      
      return $this->render('default/'.$successTemplate, array(
        'displayName'    => $entityTypeDisplayName,
        'adminPage'=>true,
        'newSlug'=>$slug,
        'userIsAdmin'=>$userIsAdmin,
        'entityName'=>$entityName,
        'addedEntityName'=> $addedEntityName,
        'addedId'=> $addedId
      ));
    }
    return $this->render('default/'.$addTemplate, array(
      'form' => $form->createView(),
      'userIsAdmin'=>$userIsAdmin,
      'displayName' => $entityTypeDisplayName,
      'adminPage'=>true,
      'entityName' => $entityName));
      
  } 


}
