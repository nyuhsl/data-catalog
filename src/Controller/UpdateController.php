<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Entity\Dataset;
use App\Form\Type\DatasetAsUserType;
use App\Form\Type\DatasetAsAdminType;
use App\Form\Type\UserType;
use App\Utils\Slugger;


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

  private $security;

  public function __construct(Security $security) {
    $this->security = $security;
  }


  /**
   * Produce the form to update a dataset; validate and ingest
   *
   * @param string $uid The UID of the dataset to be updated
   * @param Request $request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @throws NotFoundHttpException
   *
   * @Route("/update/Dataset/{uid}", defaults={"uid"=null}, name="update_dataset")
   */
  public function UpdateDatasetAction($uid, Request $request) {
    $em = $this->getDoctrine()->getManager();
    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');
    if ($uid == null) {
      $allEntities = $em->getRepository('App\Entity\Dataset')->findBy([], ['slug'=>'ASC']);
      return $this->render('default/list_of_entities_to_update.html.twig', array(
        'entities'    => $allEntities,
        'entityName'  => 'Dataset',
        'adminPage'   => true,
        'userIsAdmin' => $userIsAdmin,
        'displayName' => 'Dataset'
      ));
    }
    $thisEntity = $em->getRepository('App\Entity\Dataset')->findOneBy(array('dataset_uid'=>$uid));
    if (!$thisEntity) {
      throw $this->createNotFoundException(
        'No dataset with UID ' . $uid . ' was found.'
      );
    }
    if ($userIsAdmin) {
      $form = $this->createForm(DatasetAsAdminType::class, $thisEntity);
    } else {
      $form = $this->createForm(DatasetAsUserType::class, new Dataset($userIsAdmin, $uid), $thisEntity[0]);
    }
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $addedEntityName = $thisEntity->getDisplayName();
      $newSlug = Slugger::slugify($addedEntityName);
      $thisEntity->setSlug($newSlug);
      $newAuthorships = $thisEntity->getAuthorships();
      $oldDataset = $em->getRepository('App\Entity\Dataset')->findOneBy(array('dataset_uid'=>$uid));
      $oldAuthorships = $oldDataset->getAuthorships();
      foreach ($oldAuthorships as $oldAuthor) {
        if (!$newAuthorships->contains($oldAuthor)) {
          $oldAuthorships->removeElement($oldAuthor);
        }
      }
      foreach ($thisEntity->getAuthorships() as $authorship) {
        $authorship->setDataset($thisEntity);
        $em->persist($authorship);
      }
      $thisEntity->setDateUpdated(new \DateTime("now"));
      $em->flush();
      return $this->render('default/update_success.html.twig', array(
        'adminPage'       => true,
        'displayName'     => 'Dataset',
        'entityName'      => 'Dataset',
        'addedEntityName' => $addedEntityName,
        'uid'             => $uid,
        'newSlug'         => $newSlug,));
    } else {
      $formToRender = $userIsAdmin ? 'default/update_dataset_admin.html.twig' : 'default/update_dataset_user.html.twig';
      return $this->render($formToRender, array(
        'form'       => $form->createView(),
        'displayName'=> 'Dataset',
        'adminPage'  => true,
        'userIsAdmin'=> $userIsAdmin,
        'uid'        => $uid,
        'entityName' => 'Dataset'
      ));
    }
  }


  /**
   * Produce the form to update a user; validate and ingest
   *
   * @param string $user The user to be updated
   * @param Request $request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @throws NotFoundHttpException
   *
   * @Route("/update/User/{user}", defaults={"user"=null}, name="update_user")
   */
  public function UpdateUserAction($user, Request $request) {
    $em = $this->getDoctrine()->getManager();
    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');
    if ($user == null) {
      $allEntities = $em->getRepository('App\Entity\Security\User')->findBy([], ['slug'=>'ASC']);
      return $this->render('default/list_of_entities_to_update.html.twig', array(
        'entities'   => $allEntities,
        'entityName' => 'User',
        'adminPage'  => true,
        'userIsAdmin'=>$userIsAdmin,
        'displayName'=>'User',
      ));
    }
    $thisEntity = $em->getRepository('App\Entity\Security\User')->findOneBySlug($user);
    if (!$thisEntity) {
      throw $this->createNotFoundException(
        'No user \'' . $user . '\' was found'
      );
    }
    $form = $this->createForm(UserType::class, $thisEntity);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $addedUser = $thisEntity->getDisplayName();
      $newSlug = Slugger::slugify($addedUser);
      $thisEntity->setSlug($newSlug);
      if (!$thisEntity->getApiKey()) {
        foreach ($thisEntity->getRoles() as $role) {
          if ($role == 'ROLE_API_SUBMITTER') {
            $apiKey = sha1(random_bytes(32));
            $thisEntity->setApiKey($apiKey);
          }
        }
      }
      $em->flush();
      return $this->render('default/update_success.html.twig', array(
        'adminPage'       => true,
        'displayName'     => 'User',
        'entityName'      => 'User',
        'addedEntityName' => $addedUser,
        'newSlug'         => $newSlug,));
    } else {
      return $this->render('default/update_user.html.twig', array(
        'form'       => $form->createView(),
        'displayName'=>'User',
        'adminPage'  =>true,
        'userIsAdmin'=>$userIsAdmin,
        'entityName' =>'User'
      ));
    }
  }


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
  public function updateEntityAction($entityName, $slug, Request $request) {

    $updateEntity   = 'App\Entity\\'.$entityName;
    $entityFormType = 'App\Form\Type\\' . $entityName . "Type";
    $entityTypeDisplayName = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $entityName));

    $em = $this->getDoctrine()->getManager();
    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');

    if ($slug == null) {
      if ($entityName == 'ArchivedDatasets') {
          $allEntities = $em->getRepository('App:Dataset')->findAllArchived();
          $entityName = 'Dataset';
          $entityTypeDisplayName = 'Archived Dataset';
      } else {
        $allEntities = $em->getRepository($updateEntity)->findBy([], ['slug'=>'ASC']);
      }
      return $this->render('default/list_of_entities_to_update.html.twig', array(
        'entities'    => $allEntities,
        'entityName'  => $entityName,
        'adminPage'   => true,
        'userIsAdmin' => $userIsAdmin,
        'displayName' => $entityTypeDisplayName
      ));
    }

    $thisEntity = $em->getRepository($updateEntity)->findOneBySlug($slug);
    if (!$thisEntity) {
      throw $this->createNotFoundException(
        'No entity of type ' . $entityName . ' was found matching this slug: ' . $slug
      );
    }

    $form = $this->createForm($entityFormType, $thisEntity);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $addedEntityName = $thisEntity->getDisplayName();
      $newSlug = Slugger::slugify($addedEntityName);
      $thisEntity->setSlug($newSlug);
      if (method_exists($thisEntity, 'setDateUpdated')) {
        $thisEntity->setDateUpdated(new \DateTime("now"));
      }
      $em->flush();
      return $this->render('default/update_success.html.twig', array(
        'adminPage'=>true,
        'displayName'=>$entityTypeDisplayName,
        'entityName' =>$entityName,
        'addedEntityName' => $addedEntityName,
        'newSlug'    => $newSlug,));

    } else {
      return $this->render('default/update.html.twig', array(
        'form'    => $form->createView(),
        'displayName'=>$entityTypeDisplayName,
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
        'slug'       =>$slug,
        'entityName' =>$entityName));
    }
  }

}
