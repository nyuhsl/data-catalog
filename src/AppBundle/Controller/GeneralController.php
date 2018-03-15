<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SearchResults;
use AppBundle\Entity\SearchState;
use AppBundle\Entity\Dataset;
use AppBundle\Form\Type\DatasetType;
use AppBundle\Utils\Slugger;

/**
  *  A controller handling the main search functionality, contact and About pages,
  *  dataset views, etc.
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
class GeneralController extends Controller
{
  /**
   * Performs searches and produces results pages
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/", name="homepage")
   * @Route("/search", name="user_search_results")
   */
  public function indexAction(Request $request) {
    
    $currentSearch = new SearchState($request);

    $solr = $this->get('SolrSearchr');
    $solr->setUserSearch($currentSearch);
    $resultsFromSolr = $solr->fetchFromSolr();

    $results = new SearchResults($resultsFromSolr);

    if ($results->numResults == 0) {
      return $this->render('default/no_results.html.twig', array(
        'results' => $results,
        'currentSearch'=>$currentSearch,
      ));
    } else {
      return $this->render('default/results.html.twig',array(
                  'results' => $results,
                  'currentSearch' => $currentSearch,
                  ));
    }
    
  }
  


  
  /**
   * Produce the About page, checking if we have an institution-
   * specific version.
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   * @Route("/about", name="about")
   */
  public function aboutAction(Request $request) {

    if ($this->get('templating')->exists('institution/about.html.twig')) {
      return $this->render('institution/about.html.twig',array()); 
    }
    else {
      return $this->render('default/about.html.twig', array());
    }

  }


  /**
   * Produce the Help page
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   * @Route("/help", name="help")
   */
  public function helpAction(Request $request) {

    if ($this->get('templating')->exists('institution/help.html.twig')) {
      return $this->render('institution/help.html.twig',array()); 
    }
    else {
      return $this->render('default/help.html.twig', array());
    }

  }


  /**
   * Produce the Contact Us page and send emails to the 
   * users specified in parameters.yml
   * NOTE: The setTo() and setFrom() methods are supposed
   * to accept arrays for multiple recipients, but this appears
   * not to work.
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/contact-us", name="contact")
   */
  public function contactAction(Request $request) {
    $contactFormEmail = new \AppBundle\Entity\ContactFormEmail();

    // Get email addresses and institution list from parameters.yml
    $emailTo = $this->container->getParameter('contact_email_to');
    $emailFrom = $this->container->getParameter('contact_email_from');
    $affiliationOptions = $this->container->getParameter('institutional_affiliation_options');

    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(new \AppBundle\Form\Type\ContactFormEmailType($affiliationOptions), $contactFormEmail);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $email = $form->getData();

      // save their submission to the database first
      $em->persist($email);
      $em->flush();

      $mailer = $this->get('mailer');
      $message = $mailer->createMessage()
        ->setSubject('New Feedback about Data Catalog')
        ->setFrom($emailFrom)
        ->setTo($emailTo)
        ->setBody(
          $this->renderView(
            'default/feedback_email.html.twig',
            array('msg' => $email)
          ),
          'text/html'
        );
      $mailer->send($message);

      return $this->render('default/contact_email_send_success.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    return $this->render('default/contact.html.twig', array(
      'form' => $form->createView(),
    ));

  }


  /**
   * Produce the Submit Dataset contact page and send emails to the 
   * users specified in parameters.yml
   * NOTE: The setTo() and setFrom() methods are supposed
   * to accept arrays for multiple recipients, but this appears
   * not to work.
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/submit-dataset", name="submitdataset")
   */

  public function submitdatasetAction(Request $request) {
    $submitDatasetFormEmail = new \AppBundle\Entity\SubmitDatasetFormEmail();

    // Get email addresses and institution list from parameters.yml
    $emailTo = $this->container->getParameter('contact_email_to');
    $emailFrom = $this->container->getParameter('contact_email_from');
    $affiliationOptions = $this->container->getParameter('institutional_affiliation_options');

    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(new \AppBundle\Form\Type\SubmitDatasetFormEmailType($affiliationOptions), $submitDatasetFormEmail);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $email = $form->getData();

      // save their submission to the database first
      $em->persist($email);
      $em->flush();

      $mailer = $this->get('mailer');
      $message = $mailer->createMessage()
        ->setSubject('New Submit Dataset Entry | Data Catalog')
        ->setFrom($emailFrom)
        ->setTo($emailTo)
        ->setBody(
          $this->renderView(
            'default/submit_dataset_email.html.twig',
            array('msg' => $email)
          ),
          'text/html'
        );
      $mailer->send($message);

      return $this->render('default/submit_dataset_email_send_success.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    return $this->render('default/submit_dataset.html.twig', array(
      'form' => $form->createView(),
    ));

  }



  /**
   * Produce the detailed pages for individual datasets
   *
   * @param string $slug The slug of the dataset to be viewed
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/dataset/{slug}", name="view_dataset")
   */
  public function viewAction($slug, Request $request) {
    $dataset = $this->getDoctrine()
      ->getRepository('AppBundle:Dataset')
      ->findOneBySlug($slug);

    // dataset not found
    if (!$dataset) {
      throw $this->createNotFoundException(
        'No dataset matching title "' . $slug . '"'
      );
    }
    // dataset is unpublished, and user is not admin
    if (!$dataset->getPublished() && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      throw $this->createAccessDeniedException(
        'You are not authorized to view this resource.');
    }
    

    if ($dataset->getOrigin() == 'Internal') {
      return $this->render('default/view_dataset_internal.html.twig', array(
        'dataset' => $dataset,
      ));
    } else {
      return $this->render('default/view_dataset_external.html.twig', array(
        'dataset' => $dataset,
      ));
    }
  }

}
