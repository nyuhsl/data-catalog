<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * A controller to log users in and out
 */
class SecurityController extends Controller {

  /**
   * Login handler
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/login", name="login_route")
   */
  public function loginAction(Request $request) {
    $authenticationUtils = $this->get('security.authentication_utils');

    // get login error if exists
    $error = $authenticationUtils->getLastAuthenticationError();

    //last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render(
      'security/login.html.twig',
      array(
        'last_username' => $lastUsername,
        'error'         => $error,
      )
    );
  }
  
  
  /**
   * Placeholder function for Symfony
   *
   * @Route("/login_check", name="login_check")
   */
  public function loginCheckAction(Request $request) {
  }

  /**
   * Placeholder function for Symfony
   *
   * @Route("/logout", name="logout")
   */
  public function logoutAction(Request $request) {
  }

}
