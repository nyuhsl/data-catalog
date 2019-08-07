<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Data Location
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
class DataLocationType extends AbstractType {

  /**
   * Build the form 
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('data_location','text',array(
      'label'=>false,
      'required'=>true,
      'attr'=>array('placeholder'=>'* Data Location'))
      );
    $builder->add('location_content','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Location Content'))
      );
    $builder->add('data_access_url','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Location URL'))
      );
    $builder->add('accession_number', 'text', array(
      'required' => false,
      'label'    => false,
      'attr'=>array('placeholder'=>'Accession Number'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\DataLocation'
    ));
  }

  public function getName() {
    return 'dataLocation';
  }

}

