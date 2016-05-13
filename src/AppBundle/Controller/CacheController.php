<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * This handy controller clears the APC cache, or just prints its contents.
 * Access if limited to logged-in users with Administrator privileges.
 */
class CacheController extends Controller {


  /**
   * Clear the APC cache if user has admin privileges
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/admin/cache/clear", name="cache_clear")
   */
  public function clearCache(Request $request) {

    $userIsAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');

    if ($userIsAdmin) {
      apc_clear_cache();
      apc_clear_cache('user');
      apc_clear_cache('opcode');
      return new Response('success');
    } else {
      throw new AccessDeniedException();
    }

  }

  /**
   * Prints contents of APC cache if user has admin privileges
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/admin/cache/view", name="cache_info")
   */
  public function viewCache(Request $request) {

    $userIsAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');

    if ($userIsAdmin) {
      return new Response(var_dump(apc_cache_info()));
    } else {
      throw new AccessDeniedException();
    }

  }
}
