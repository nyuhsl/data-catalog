<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SearchResults;
use AppBundle\Entity\SearchState;
use AppBundle\Entity\Dataset;
use AppBundle\Form\Type\DatasetType;
use AppBundle\Utils\Slugger;
use Symfony\Component\Validator\Constraints as Assert;


/**
  *  A controller handling functions relating to Temporary Access Keys
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
class TakController extends Controller
{
  
  /**
   * Create a TAK for a dataset
   *
   * @param string $dataset_uid The UID of the dataset to be viewed
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/tak/gen/{uid}", name="tak_generate")
   */

  public function generateTak($uid, Request $request) {
  
		if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {

			$dataset=$this->getDoctrine()->getRepository('AppBundle:Dataset')->findOneBy(array('dataset_uid'=>$uid));

			if ($dataset) {

				do {
					$uuid=uniqid( true );
				} while($this->getDoctrine()->getRepository('AppBundle:TempAccessKey')->findOneBy(array('uuid'=>$uuid)));

				$em = $this->getDoctrine()->getManager();
				$tak = new \AppBundle\Entity\TempAccessKey;

				$tak->setUuid( $uuid );
				$tak->setGenerated(  new \DateTime() );
				$tak->setDatasetAssociation($dataset);
				
				$em->persist($tak);
				$em->flush();

				return $this->render('default/tak_generate.html.twig', array(
					'displayName' => 'Dataset',
					'uuid' => $uuid,
					'dataset_uid' => $dataset->getId(),
					'dataset_title' => $dataset->getTitle(),
					'generated' => $tak->getGenerated()
					
				));

				
			} else {

				throw $this->createNotFoundException(
					'No dataset matching ID "' . $uid . '"'
				);

			}			

		} else {
		
			throw $this->createAccessDeniedException(
				'You are not authorized to view this resource.');

		}
  
	}

  /**
   * Find all the generated TAKs for a dataset
   *
   * @param string $dataset_uid The UID of the dataset to be viewed
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/tak/get/{uid}", name="tak_get_for_uid")
   */
  
  public function getTaks($uid, Request $request) {

		$data = ['response'=>'DENIED','keys'=>[],'dataset'=>0];

		if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {

			$dataset=$this->getDoctrine()->getRepository('AppBundle:Dataset')->findOneBy(array('dataset_uid'=>$uid));

			if ($dataset) {

				$taks=$this->getDoctrine()->getRepository('AppBundle:TempAccessKey')->findBy(array('dataset_association'=>$dataset->getId() ));

				foreach($taks as $t=>$v) {

					$data['keys'][]=['id'=>$v->getId(), 'uuid'=>$v->getUuid(), 'generated'=>$v->getGenerated(), 'first_access'=>$v->getFirstAccess() ];

				}
				
				$data['response']='KEYS';
				$data['dataset']=$dataset->getId();
				
			} else {
			
				$data['response']='INVALID_UID';

			}			

		}

		return new JsonResponse($data); 

	}


  /**
   * Find all the generated TAKs for a dataset
   *
   * @param string $dataset_uid The UID of the dataset to be viewed
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/tak/delete/{uuid}", name="tak_delete_id")
   */
  
  public function deleteTak($uuid, Request $request) {

		$data = ['response'=>'DENIED', 'uuid'=>'', 'dataset'=>0];

		if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {

			$tak=$this->getDoctrine()->getRepository('AppBundle:TempAccessKey')->findOneBy(array('uuid'=>$uuid));

			if ($tak) {

				$dataset=$tak->getDatasetAssociation()->getId();

				$em = $this->getDoctrine()->getManager();
				$em->remove($tak);
        $em->flush();

				$data['response']='SUCCESS';
				$data['uuid']=$uuid;
				$data['dataset']=$dataset;
				
			} else {
			
				$data['response']='INVALID_UUID';

			}			

		}

		return new JsonResponse($data); 

	}
	
		
}
