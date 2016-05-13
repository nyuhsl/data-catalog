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
 * A controller to handle the Admin section
 */
class AdminController extends Controller
{
  /**
   * Produce the main admin dashboard
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/dashboard", name="admin_panel")
   */
  public function adminAction(Request $request) {
    

    return $this->render('default/admin-home.html.twig',array(
      'adminPage'=>true,
                ));
    
  }
  
  
  /**
   * Produce the entity management page
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/manage", name="admin_manage")
   */
  public function adminManageAction(Request $request) {
    

    return $this->render('default/admin-manage.html.twig',array(
      'adminPage'=>true,
                ));
    
  }

  /**
   * Produce the user management page
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/users", name="admin_users")
   */
  public function adminUsersAction(Request $request) {
    

    return $this->render('default/admin-users.html.twig',array(
      'adminPage'=>true,
                ));
    
  }
  
}
