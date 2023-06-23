<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Entity\SearchResults;
use App\Entity\SearchState;
use App\Entity\Dataset;
use App\Service\SolrSearchr;
use App\Form\Type\DatasetType;
use App\Form\Type\ContactFormEmailType;
use App\Utils\Slugger;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
class GeneralController extends AbstractController
{
  private $security;
  private $solr;

  public function __construct(Security $security, SolrSearchr $solrSearchr) {
    $this->security = $security;
    $this->solr = $solrSearchr;
  }


  /**
   * Display splash page, checking if we have an institution-specific version first
   *
   * @return Response A Response instance
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request) {
    if ($this->get('twig')->getLoader()->exists('institution/index.html.twig')) {
      return $this->render('institution/index.html.twig',array()); 
    }
    else {
      return $this->render('index.html.twig', array());
    }
  }


  /**
   * Performs searches and produces results pages
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/search", name="user_search_results")
   */
  public function searchAction(Request $request) {
    // forcibly exclude restricted datasets if the person isn't logged in or is sponsored individual
    if (!$this->security->isGranted('ROLE_INSTITUTIONAL_AUTHENTICATED_USER') OR $this->security->isGranted('ROLE_DENIED_ACCESS')) {
      $facetsQuery = $request->query->get('facet');
      if ($facetsQuery) {
          foreach ($facetsQuery as $key => $facet) {
            if (strpos(strtolower($facet), 'restricted_fq') !== false) {
              unset($facetsQuery[$key]);
            }
          }
          $newFacetQuery = array_values($facetsQuery);
          $newFacetQuery[] = "!restricted_fq:true";
          $request->query->set('facet', $newFacetQuery);
      } else {
          $newFacetQuery[] = "!restricted_fq:true";
          $request->query->set('facet', $newFacetQuery);
      }
    }
    
    $currentSearch = new SearchState($request);

    $this->solr->setUserSearch($currentSearch);
    $resultsFromSolr = $this->solr->fetchFromSolr();

    $results = new SearchResults($resultsFromSolr);
    // just in case we got any restricted datasets from Solr, do another check to forcibly remove them from results list
    foreach ($results->resultItems as $key=>$item) {
        if ($item->restricted == 'true' && (!$this->security->isGranted('ROLE_INSTITUTIONAL_AUTHENTICATED_USER') OR $this->security->isGranted('ROLE_DENIED_ACCESS'))) {
            unset($results->resultItems[$key]);
        }
    }

    if ($results->numResults == 0) {
      return $this->render('default/no_results.html.twig', array(
        'results' => $results,
        'currentSearch'=>$currentSearch,
      ));
    } elseif ($this->get('twig')->getLoader()->exists('institution/results.html.twig')) {
      return $this->render('institution/results.html.twig',array(
        'results' => $results,
        'currentSearch' => $currentSearch,
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

    if ($this->get('twig')->getLoader()->exists('institution/about.html.twig')) {
      return $this->render('institution/about.html.twig',array()); 
    }
    else {
      return $this->render('about.html.twig', array());
    }

  }


  /**
   * Produce How To Use the Catalog page, checking if we have an institution-
   * specific version.
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   * @Route("/how-to-use-the-catalog", name="how_to_use_catalog")
   */
  public function howToUseTheCatalogAction(Request $request) {

    if ($this->get('twig')->getLoader()->exists('institution/how_to_use_catalog.html.twig')) {
      return $this->render('institution/how_to_use_catalog.html.twig',array()); 
    }
    else {
      return $this->render('how_to_use_catalog.html.twig', array());
    }

  }


  /**
   * Produce the FAQ page, checking if we have an institution-
   * specific version.
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   * @Route("/frequently-asked-questions", name="faq")
   */
  public function faqAction(Request $request) {

    if ($this->get('twig')->getLoader()->exists('institution/faq.html.twig')) {
      return $this->render('institution/faq.html.twig',array()); 
    }
    else {
      return $this->render('faq.html.twig', array());
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
  public function contactAction(Request $request, MailerInterface $mailer) {
    $contactFormEmail = new \App\Entity\ContactFormEmail();

    // Get email addresses and institution list from parameters.yml
    $emailTo = $this->getParameter('contact_email_to');
    $emailFrom = $this->getParameter('contact_email_from');
    $affiliations = $this->getParameter('institutional_affiliation_options');
    $affiliationOptions = [];
    foreach ($affiliations as $key=>$value) {
      $affiliationOptions[$value] = $value;
    }
       
      
    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(ContactFormEmailType::class, $contactFormEmail, ['affiliationOptions'=>$affiliationOptions]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $email = $form->getData();

      // save their submission to the database first
      $em->persist($email);
      $em->flush();

      $message = (new TemplatedEmail())
        ->subject('New Feedback about Data Catalog')
        ->from($emailFrom)
        ->to($emailTo)
        ->htmlTemplate('default/feedback_email.html.twig')
        ->context(['msg' => $email]);
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
   * Produce the detailed pages for individual datasets
   *
   * @param string $dataset_uid The UID of the dataset to be viewed
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/dataset/{uid}", name="view_dataset")
   */
  public function viewAction($uid, Request $request) {
    $dataset = $this->getDoctrine()
      ->getRepository('App\Entity\Dataset')
      ->findOneBy(array('dataset_uid'=>$uid));

    // dataset not found
    if (!$dataset) {
      throw $this->createNotFoundException(
        'No dataset matching ID "' . $uid . '"'
      );
    }

    // if they're trying to view a restricted dataset, check the access!!!!
    if ($dataset->getRestricted() == true) {
        if (!$this->security->isGranted('ROLE_INSTITUTIONAL_AUTHENTICATED_USER') OR $this->security->isGranted('ROLE_DENIED_ACCESS')) {
            throw new AccessDeniedHttpException('Sorry, your role does not grant you access to this resource.');
	}
    }

    // if dataset archived
    if ($dataset->getArchived() && !$this->security->isGranted('ROLE_ADMIN')) {
      throw $this->createNotFoundException(
        'Sorry, this dataset is no longer available. Please try another search.'
      );
    }

    

		$view_access=true;

		if (!$dataset->getPublished() && !$this->security->isGranted('ROLE_ADMIN')) {
			
			$view_access=false;
			
			if ($request->get('tak') && !$dataset->getPublished()) {
	
				$tak=$this->getDoctrine()->getRepository('App\Entity\TempAccessKey')->findOneBy(array('dataset_association'=>$uid, 'uuid'=>$request->get('tak')) );
			
				if (sizeof($tak)>0) {
					
					if (!$tak->getFirstAccess()) {
						
				    $em = $this->getDoctrine()->getManager();
						$tak->setFirstAccess(  new \DateTime() );
						$em->persist($tak);
						$em->flush();
						$view_access=true;
						
					} else {
					
						$tak_ttl="PT72H";
						if ($this->getParameter('tak_ttl')) {
							$tak_ttl=$this->getParameter('tak_ttl');
						}					
						if (new \DateTime()<$tak->getFirstAccess()->modify($tak_ttl)) {
							$view_access=true;
						} else {
							throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(
								'This temporary access link has expired.', null, 403);
						}
					
					}

				}
			
			}

		}


		if ($view_access == false) {

			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(
				'Sorry, you are not authorized to view this resource.', null, 403);
		
		}

		if ($dataset->getOrigin() == 'Internal') {
            if ($this->get('twig')->getLoader()->exists('institution/view_dataset_internal.html.twig')) {
                return $this->render('institution/view_dataset_internal.html.twig',array(
                    'dataset' => $dataset,
                )); 
            } else {
                return $this->render('default/view_dataset_internal.html.twig', array(
				    'dataset' => $dataset,
			    ));
            }
		} else {
            if ($this->get('twig')->getLoader()->exists('institution/view_dataset_external.html.twig')) {
                return $this->render('institution/view_dataset_external.html.twig',array(
                    'dataset' => $dataset,
                )); 
            } else {
                return $this->render('default/view_dataset_external.html.twig', array(
                    'dataset' => $dataset,
                ));
            }
		}
  }
  
	
		
}
