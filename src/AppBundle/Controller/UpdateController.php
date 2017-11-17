<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Dataset;
use AppBundle\Form\Type\DatasetAsUserType;
use AppBundle\Form\Type\DatasetAsAdminType;
use AppBundle\Utils\Slugger;


/**
 * A controller to handle editing datasets and other entities
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
class UpdateController extends Controller {


  /**
   * Produce the form to update an entity; validate and ingest
   *
   * @param string $entityName The type of entity to be updated
   * @param string $slug The slug of the entity to be updated
   * @param Request $request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @throws NotFoundHttpException
   *
   * @Route("/update/{entityName}/{slug}", defaults={"slug"=null}, name="update_entity")
   */
  public function updateEntity($entityName, $slug, Request $request) {

    if ($entityName == 'User') {
      $updateEntity = 'AppBundle\Entity\Security\\'.$entityName;
    } else {
      $updateEntity   = 'AppBundle\Entity\\'.$entityName;
    }
    $entityFormType = 'AppBundle\Form\Type\\' . $entityName . "Type";
    $entityTypeDisplayName = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $entityName));

    $em = $this->getDoctrine()->getManager();
    
    $userIsAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');

    if ($slug == null) {
      if ($entityName == 'Award') {
        $allEntities = $em->getRepository('AppBundle:Award')->findAllOrdered();
      } elseif ($entityName == 'ArchivedDatasets') {
          $allEntities = $em->getRepository('AppBundle:Dataset')->findAllArchived();
          $entityName = 'Dataset';
          $entityTypeDisplayName = 'Archived Dataset';
      } else {
        $allEntities = $em->getRepository($updateEntity)->findAll();
      }
      return $this->render('default/list_of_entities_to_update.html.twig', array(
        'entities'    => $allEntities,
        'entityName'  => $entityName,
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
        'displayName' => $entityTypeDisplayName
      ));
    }

    $thisEntity = $em->getRepository($updateEntity)->findOneBySlug($slug);
    if (!$thisEntity) {
      throw $this->createNotFoundException(
        'No entity of type ' . $entityName . ' was found matching this slug: ' . $slug
      );
    }

    if ($entityName == 'Dataset') {
      $datasetUid = $thisEntity->getDatasetUid();
      if ($userIsAdmin) {
        $form = $this->createForm(new DatasetAsAdminType($userIsAdmin, $datasetUid), $thisEntity);
      } else {
        $form = $this->createForm(new DatasetAsUserType($userIsAdmin, $datasetUid), $thisEntity);
      }
    }
    else {
      $form = $this->createForm(new $entityFormType(), $thisEntity);
    }
    $form->handleRequest($request);
    if ($form->isValid()) {
      $addedEntityName = $thisEntity->getDisplayName();
      $newSlug = Slugger::slugify($addedEntityName);
      $thisEntity->setSlug($newSlug);
      if (method_exists($thisEntity, 'setDateUpdated')) {
        $thisEntity->setDateUpdated(new \DateTime("now"));
      }
      if ($entityName == 'Dataset') {
        $newAuthorships = $thisEntity->getAuthorships();
        $oldDataset = $em->getRepository($updateEntity)->findOneBy(array('dataset_uid'=>$datasetUid));
        $oldAuthorships=$oldDataset->getAuthorships();
        foreach ($oldAuthorships as $oldAuthor) {
          if (!$newAuthorships->contains($oldAuthor)) {
            $oldAuthorships->removeElement($oldAuthor);
          }
        }
        foreach ($thisEntity->getAuthorships() as $authorship) {
          $authorship->setDataset($thisEntity);
          $em->persist($authorship);
        }
      }
      $em->flush();
      return $this->render('default/update_success.html.twig', array(
        'adminPage'=>true,
        'displayName'=>$entityTypeDisplayName,
        'entityName' =>$entityName,
        'addedEntityName' => $addedEntityName,
        'newSlug'    => $newSlug,));

    } else {
 
      if ($entityName == 'Dataset') {
  
        $formToRender = $userIsAdmin ? 'default/update_dataset_admin.html.twig' : 'default/update_dataset_user.html.twig';

        return $this->render($formToRender, array(
          'form'    => $form->createView(),
          'displayName'=>$entityTypeDisplayName,
          'adminPage'=>true,
          'userIsAdmin'=>$userIsAdmin,
          'slug'       =>$slug,
          'entityName' =>$entityName));
      } else {

        return $this->render('default/update.html.twig', array(
          'form'    => $form->createView(),
          'adminPage'=>true,
          'displayName'=>$entityTypeDisplayName,
          'slug'      => $slug,
          'entityName' =>$entityName));
      }
    }
  }



}
