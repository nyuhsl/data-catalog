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
                'slug'=> $dataset->getSlug(),
                
                ));
    
  }



}
