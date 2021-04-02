<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/** 
 * Form builder for User entry
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
class UserType extends AbstractType {

  private $apiKey;

  public function __construct() {
    $this->apiKey = $this->generateSecureRandomAPIKey();
  }



  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('username');
    $builder->add('firstName');
    $builder->add('lastName');
    $builder->add('roles', ChoiceType::class, array(
      'choices' => array(
        'ROLE_ADMIN' => array('Admin' => 'ROLE_ADMIN'),
        'ROLE_USER' => array('User' => 'ROLE_USER'),
        'ROLE_API_SUBMITTER' => array('API Submitter' => 'ROLE_API_SUBMITTER')
      ),
      'multiple'=>true,
      'expanded'=>true,
      'required'=>true
    ));
    $builder->add('apiKey', null, array(
      'required' => false,
      'attr' => array('readonly'=>true)
    ));

    $builder->add('save', SubmitType::class,array('label'=>'Submit'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Security\User'
    ));
  }

  private function generateSecureRandomAPIKey() {
    $apiKey = sha1(random_bytes(32));

    return $apiKey;
  }

  public function getName() {
    return 'user';
  }

}

