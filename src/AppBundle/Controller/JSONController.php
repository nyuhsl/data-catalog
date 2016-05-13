<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Dataset;


/**
 * A controller for producing JSON output
 */
class JSONController extends Controller
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
   */ 
  public function JSONAction($slug, $_format) {

    $em = $this->getDoctrine()->getManager();

    if ($slug == "all") {
      $datasets = $em->getRepository('AppBundle:Dataset')
         ->findBy(array('published'=>1));
    } else {
      $datasets = $em->getRepository('AppBundle:Dataset')
         ->findOneBy(array('slug'=>$slug,'published'=>1));
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
   * Accept JSON submissions
   *
   * @TODO
   *
   * @Route(
   *   "/api/add/dataset", name="json_input"
   * )
   */
  public function JSONInputAction() {

    throw new NotFoundHttpException();

  }






}
