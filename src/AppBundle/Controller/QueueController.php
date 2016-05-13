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
 * A simple controller that returns information about unpublished datasets
 */
class QueueController extends Controller
{
  /**
   * Get the number of unpublished items
   *
   * @return Response A Response instance
   */
  public function queueLengthAction() {

    $em = $this->getDoctrine()->getManager();

    $queueLength = $em->getRepository('AppBundle:Dataset')
         ->countAllUnpublished();
    

    return $this->render('default/queue_notification.html.twig',array(
                'queueLength' => $queueLength,
                'adminPage'=>true,
                ));
    
  }


  /**
   * Produce a list of all the unpublished datasets
   *
   * @return Response A Response instance
   *
   * @Route("/admin/approval-queue", name="approval_queue")
   */
   public function viewApprovalQueueAction() {
     
     $em = $this->getDoctrine()->getManager();
     $approvalQueue = $em->getRepository('AppBundle:Dataset')
          ->findAllUnpublished();

     return $this->render('default/approval_queue.html.twig',array(
                 'approvalQueue' => $approvalQueue,
                 'adminPage'=>true,
                  ));

   }


  
}
