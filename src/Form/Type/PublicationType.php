<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Form builder for Publication entry
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
class PublicationType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('citation', TextareaType::class, array(
      'attr' => array('rows'=>'7')
    ));
    $builder->add('url', TextType::class, array(
      'label'=>'URL',
      'required'=>false));
    $builder->add('doi', TextType::class,array(
     'required'=>false,
     'label'   => 'DOI'));
    $builder->add('save',SubmitType::class,array('label'=>'Submit'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Publication'
    ));
  }

  public function getName() {
    return 'publication';
  }

}

