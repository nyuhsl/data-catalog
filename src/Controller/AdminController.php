<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\SearchResults;
use App\Entity\SearchState;
use App\Entity\Dataset;
use App\Form\Type\DatasetType;
use App\Utils\Slugger;


/**
 * A controller to handle the Admin section
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
