<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * This handy controller clears the APC cache, or just prints its contents.
 * Access is limited to logged-in users with Administrator privileges.
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
class CacheController extends Controller {

  private $security;

  public function __construct(Security $security) {
    $this->security = $security;
  }

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

    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');

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

    $userIsAdmin = $this->security->isGranted('ROLE_ADMIN');

    if ($userIsAdmin) {
      return new Response(var_dump(apc_cache_info()));
    } else {
      throw new AccessDeniedException();
    }

  }
}
